<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Libraries\ResponseStd;
use App\Models\Constants;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Http\Controllers\AccessTokenController as ParentAccessTokenController;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser as JwtParser;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\RefreshToken;

class LoginController extends ParentAccessTokenController
{
    use \Illuminate\Foundation\Validation\ValidatesRequests;

    public function __construct(AuthorizationServer $server, TokenRepository $tokens, JwtParser $jwt)
    {
        parent::__construct($server, $tokens, $jwt);
    }

    public function issueToken(ServerRequestInterface $request): \Illuminate\Http\Response|JsonResponse
    {
        $body = $request->getParsedBody();
        $this->validate($body, [
            'username' => 'required',
            'password' => 'required',
        ]);
        try {
            //Constants Data
            $tmp['client_id'] = Constants::CLIENT_ID_USER;
            $tmp['client_secret'] = Constants::CLIENT_SECRET_USER;
            $tmp['grant_type'] = Constants::GRANT_TYPE;
            $tmp['scope'] = Constants::SCOPE;
            $model = User::query()->where('email', $body['username'])->first();
            $verified = !empty($model->isActive);


            // if verified is false => email not verify

            $responseData = (new UserResource($model));
            $this->ValidateEmail($model, $body['password']);
            if ($verified === false) {
                throw new BadRequestHttpException("Login gagal, Akun Anda belum diverifikasi oleh admin.");
            }
            $data = array_merge($tmp, $body);


            $response = parent::issueToken($request->withParsedBody($data));
            if ($response->getStatusCode() === JsonResponse::HTTP_OK && !empty($body['fcm_token'])) {
                $model->update(['fcm_token' => $body['fcm_token']]);
            }

            $oauthContent = json_decode($response->getContent());
            $dataResponse = [
                'token_type' => $oauthContent->token_type,
                'access_token' => $oauthContent->access_token,
                'expires_in' => $oauthContent->expires_in,
                'refresh_token' => $oauthContent->refresh_token,
                'fcm_token' => $body['fcm_token'] ?? null
            ];

            $dataResponse['user'] = $responseData;

            return ResponseStd::okSingle((object)$dataResponse);
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . __FUNCTION__ . ' ' . $e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else if ($e instanceof BadRequestHttpException) {
                    return ResponseStd::fail($e->getMessage(), $e->getStatusCode());
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    protected function ValidateEmail($data, $inputPWD)
    {
        if (empty($data) || Hash::check($inputPWD, $data->password) === false) {
            throw new BadRequestHttpException("Email atau password salah.");
        }
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return void
     */
    public function validate($data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($data, $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new HttpResponseException(ResponseStd::validation($validator, 'POST'));
        }
    }

    public function logout()
    {
        DB::beginTransaction();
        try {
            // Get the currently authenticated user
            $user = Auth::user();

            if (empty($user)) {
                return ResponseStd::fail("Pengguna tidak terautentikasi.", 401);
            }

            $token = $user->token();
            // Revoke the user's access token
            $token->revoke();
            RefreshToken::where('access_token_id', $token->id)->update(['revoked' => true]);
            DB::commit();
            return ResponseStd::okNoOutput("Anda telah berhasil logout.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ResponseStd::fail($e->getMessage(), $e->getCode());
        }
    }
}
