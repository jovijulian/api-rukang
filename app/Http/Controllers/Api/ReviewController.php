<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Order;
use Ramsey\Uuid\Uuid;
use App\Models\Review;
use App\Models\Talent;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use App\Libraries\FilesLibrary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'talent_name';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND reviews.talent_name LIKE '%$search_term%'";
            }

            $paginate = Review::query()->select(['reviews.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Review::query()
                ->count();

            // paging response.
            $response = ReviewResource::collection($paginate);
            return ResponseStd::pagedFrom($response, $paginate, $countAll);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error($e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */

    protected function validateCreate(array $data)
    {
        $arrayValidator = [
            'rating' => ['required'],
            'comment' => ['required'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create($transaction_id, array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $reviewData = new Review();

        // input data review
        $getTransaction = Transaction::where('id', $transaction_id)->first();
        $getTalent = Order::where('id', $getTransaction->order_id)->first();
        $reviewId = Uuid::uuid4()->toString();
        $reviewData->id = $reviewId;
        $reviewData->customer_id = auth()->user()->id;
        $reviewData->customer_name = auth()->user()->fullname;
        $reviewData->talent_id = $getTalent->talent_id;
        $reviewData->talent_name = $getTalent->talent_name;
        $reviewData->transaction_id = $transaction_id;
        $reviewData->review_date = $timeNow;
        $reviewData->rating = $data['rating'];
        $reviewData->comment = $data['comment'];

        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $imageId = (new FilesLibrary())
                        ->saveImage(
                            $request->file($key),
                            'images/review',
                            false,
                            300,
                            300,
                            'review'
                        );
                    if ($key == 'review_photo') {
                        $reviewData->review_photo = $imageId;
                    } elseif ($key == 'review_photo2') {
                        $reviewData->review_photo2 = $imageId;
                    } elseif ($key == 'review_photo3') {
                        $reviewData->review_photo3 = $imageId;
                    } elseif ($key == 'review_photo4') {
                        $reviewData->review_photo4 = $imageId;
                    }
                } else {
                    $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                    $reviewData->$key = $key_id;
                }
            }
        }
        $reviewData->created_at = $timeNow;
        $reviewData->updated_at = null;

        // save review
        $reviewData->save();

        $allRatings = Review::where('talent_id', $getTalent->talent_id)->pluck('rating');

        // Hitung rata-rata rating
        $averageRating = $allRatings->average();

        // Perbarui kolom "rating" pada model "Talent" dengan nilai rata-rata
        $talentData = Talent::find($getTalent->talent_id);
        $talentData->rating = $averageRating;
        $talentData->save();

        return $reviewData;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($transaction_id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateCreate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = $this->create($transaction_id, $request->all(), $request);
            DB::commit();

            // return
            $single = new ReviewResource($model);
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error($e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $model = Review::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Review tidak ada", 404);
            }
            $single = new ReviewResource($model);
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error(__CLASS__ . ":" . __FUNCTION__ . ' ' . $e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     */

    protected function validateUpdate(array $data)
    {
        $arrayValidator = [
            'rating' => ['required'],
            'comment' => ['required'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find review by id
        $reviewData = Review::find($id);

        if (empty($reviewData)) {
            throw new \Exception("Invalid review id", 406);
        }
        $reviewData->id = $id;
        $reviewData->customer_id;
        $reviewData->customer_name;
        $reviewData->rating = $data['rating'];
        $reviewData->comment = $data['comment'];
        $reviewData->updated_at = $timeNow;
        //Save
        $reviewData->save();

        $allRatings = Review::where('talent_id', $reviewData->talent_id)->pluck('rating');

        // Hitung rata-rata rating
        $averageRating = $allRatings->average();

        // Perbarui kolom "rating" pada model "Talent" dengan nilai rata-rata
        $talentData = Talent::find($reviewData->talent_id);
        $talentData->rating = $averageRating;
        $talentData->save();

        return $reviewData;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateUpdate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $data = $this->edit($id, $request->all(), $request);

            DB::commit();

            // return.
            $single = new ReviewResource($data);
            return ResponseStd::okSingle($single);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error($e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    public function datatable(Request $request)
    {
        //SETUP
        $columns = array();

        foreach ($request->columns as $columnData) {
            $columns[] = $columnData['data'];
        }
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->has('order.0.column')] ? 'talent_name'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Review::offset($start)->limit($limit)->orderBy($order, $dir)->get();

            $totalData = $data->count();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND talent_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR customer_name LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Review::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = Review::whereRaw($conditions)->count();
            $totalData = $totalFiltered;
        }
        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        return json_encode($json_data);
    }

    protected function delete($id)
    {

        $review = Review::where('id', $id)->first();
        FilesLibrary::deleteReview($review);
        // $review->review_photo::
        $review->delete();

        return $review;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("Review berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error($e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }

    public function indexPerTalent(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $talent_id = $request->input('talent_id');
            $limit = $request->has('limit') ? $request->input('limit') : 100;
            $sort = $request->has('sort') ? $request->input('sort') : 'review_date';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 100) {
                $limit = 100;
            }



            $paginate = Review::query()->select(['reviews.*'])
                ->where('talent_id', $talent_id)
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Order::query()
                ->count();

            // paging response.
            $response = ReviewResource::collection($paginate);
            return ResponseStd::pagedFrom($response, $paginate, $countAll);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            if ($e instanceof ValidationException) {
                return ResponseStd::validation($e->validator);
            } else {
                Log::error($e->getMessage());
                if ($e instanceof QueryException) {
                    return ResponseStd::fail(trans('error.global.invalid-query'));
                } else {
                    return ResponseStd::fail($e->getMessage(), $e->getCode());
                }
            }
        }
    }
}
