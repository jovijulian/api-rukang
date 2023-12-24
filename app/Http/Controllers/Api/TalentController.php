<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Talent;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use App\Libraries\FilesLibrary;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\TalentResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TalentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'fullname';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND talents.fullname LIKE '%$search_term%'";
            }

            $paginate = Talent::query()->select(['talents.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Talent::query()
                ->count();

            // paging response.
            $response = TalentResource::collection($paginate);
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
            'fullname' => ['required', 'string', 'min:1', 'max:100'],
            'phone_number' => ['required'],
            'address' => ['required'],

        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $talentData = new Talent();

        // input data talent
        $talentId = Uuid::uuid4()->toString();
        $talentData->id = $talentId;
        $talentData->fullname = $data['fullname'];
        $talentData->email = $data['email'];
        $talentData->phone_number = $data['phone_number'];
        $talentData->about_me = $data['about_me'];
        $talentData->address = $data['fullname'];
        $talentData->category_id1 = $data['category_id1'];
        $talentData->category_name1 = $data['category_name1'];
        $talentData->category_id2 = $data['category_id2'];
        $talentData->category_name2 = $data['category_name2'];
        $talentData->category_id3 = $data['category_id3'];
        $talentData->category_name4 = $data['category_name4'];

        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $imageId = (new FilesLibrary())
                        ->saveImage(
                            $request->file($key),
                            'images/profile-talent',
                            false,
                            300,
                            300,
                            'profile-talent'
                        );
                    if ($key == 'image_profile') {
                        $talentData->image_profile = $imageId;
                    } elseif ($key == 'image_profile2') {
                        $talentData->image_profile2 = $imageId;
                    } elseif ($key == 'image_profile3') {
                        $talentData->image_profile3 = $imageId;
                    } elseif ($key == 'image_profile4') {
                        $talentData->image_profile4 = $imageId;
                    }
                } else {
                    $key_id = !empty($request->$key . '_old') ? $request->$key . '_old' : null;
                    $talentData->$key = $key_id;
                }
            }
        }
        $talentData->created_at = $timeNow;
        $talentData->updated_at = null;

        // save talent
        $talentData->save();

        return $talentData;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validate = $this->validateCreate($request->all());
            if ($validate->fails()) {
                throw new ValidationException($validate);
            }
            $model = $this->create($request->all(), $request);
            DB::commit();

            // return
            $single = new TalentResource($model);
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
            $model = Talent::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Talent tidak ada", 404);
            }
            $single = new TalentResource($model);
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
            'fullname' => ['required', 'string', 'min:1', 'max:100'],
            'phone_number' => ['required'],
            'address' => ['required'],

        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find talent by id
        $talentData = Talent::find($id);

        if (empty($talentData)) {
            throw new \Exception("Invalid talent id", 406);
        }
        $talentData->id = $id;
        $talentData->fullname = $data['fullname'];
        $talentData->email = $data['email'];
        $talentData->phone_number = $data['phone_number'];
        $talentData->about_me = $data['about_me'];
        $talentData->address = $data['fullname'];
        $talentData->category_id1 = $data['category_id1'];
        $talentData->category_name1 = $data['category_name1'];
        $talentData->category_id2 = $data['category_id2'];
        $talentData->category_name2 = $data['category_name2'];
        $talentData->category_id3 = $data['category_id3'];
        $talentData->category_name4 = $data['category_name4'];
        $talentData->updated_at = $timeNow;
        //Save
        $talentData->save();

        return $talentData;
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
            $single = new TalentResource($data);
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
        $order = $columns[$request->has('order.0.column')] ? 'fullname'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Talent::offset($start)->limit($limit)->orderBy($order, $dir)->get()->map(function ($talent) {
                if ($talent->image_profile !== null) {
                    $talent->image_profile = url(Storage::url($talent->image_profile));
                }
                if ($talent->image_profile2 !== null) {
                    $talent->image_profile2 = url(Storage::url($talent->image_profile2));
                }
                if ($talent->image_profile3 !== null) {
                    $talent->image_profile3 = url(Storage::url($talent->image_profile3));
                }
                if ($talent->image_profile4 !== null) {
                    $talent->image_profile4 = url(Storage::url($talent->image_profile4));
                }
                return $talent;
            });
            $totalData = $data->count();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND fullname LIKE '%" . trim($search) . "%'";
                $conditions .= " OR email LIKE '%" . trim($search) . "%'";
                $conditions .= " OR phone_number LIKE '%" . trim($search) . "%'";
                $conditions .= " OR rating LIKE '%" . trim($search) . "%'";
                $conditions .= " OR category_name1 LIKE '%" . trim($search) . "%'";
                $conditions .= " OR category_name2 LIKE '%" . trim($search) . "%'";
                $conditions .= " OR category_name3 LIKE '%" . trim($search) . "%'";
                $conditions .= " OR category_name4 LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Talent::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get()
                ->map(function ($talent) {
                    if ($talent->image_profile !== null) {
                        $talent->image_profile = url(Storage::url($talent->image_profile));
                    }
                    if ($talent->image_profile2 !== null) {
                        $talent->image_profile2 = url(Storage::url($talent->image_profile2));
                    }
                    if ($talent->image_profile3 !== null) {
                        $talent->image_profile3 = url(Storage::url($talent->image_profile3));
                    }
                    if ($talent->image_profile4 !== null) {
                        $talent->image_profile4 = url(Storage::url($talent->image_profile4));
                    }
                    return $talent;
                });

            //QUERI CUSTOM
            $totalFiltered = Talent::whereRaw($conditions)->count();
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

        $talent = Talent::find($id);

        $talent->delete();

        return $talent;
    }
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $this->delete($id);
            DB::commit();
            // return
            return ResponseStd::okNoOutput("Talent berhasil dihapus.");
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

    protected function editImageProfile($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find talent by id
        $talentData = Talent::find($id);

        if (empty($talentData)) {
            throw new \Exception("Invalid talent id", 406);
        }
        $talentData->id = $id;
        // Input data image
        $dataImageId = null;
        $dataImageId2 = null;
        $dataImageId3 = null;
        $dataImageId4 = null;
        foreach ($request->file() as $key => $file) {
            if ($request->hasFile($key)) {
                if ($request->file($key)->isValid()) {
                    $imageId = (new FilesLibrary())
                        ->saveImageAspectRatio(
                            $talentData->image_profile,
                            $talentData->image_profile2,
                            $talentData->image_profile3,
                            $talentData->image_profile4,
                            $request->file($key),
                            'images/profile-talent',
                            'profile-talent'
                        );
                    if ($key == 'image_profile') {
                        $dataImageId = $imageId;
                    } elseif ($key == 'image_profile2') {
                        $dataImageId2 = $imageId;
                    } elseif ($key == 'image_profile3') {
                        $dataImageId3 = $imageId;
                    } elseif ($key == 'image_profile4') {
                        $dataImageId4 = $imageId;
                    }
                    // $dataImageId = $imageId;
                }
            }
        }
        if ($dataImageId !== null) {
            // Updating image.
            $talentData->image_profile = $dataImageId;
            $talentData->image_profile2 = $dataImageId2;
            $talentData->image_profile3 = $dataImageId3;
            $talentData->image_profile4 = $dataImageId4;
        }
        $talentData->updated_at = $timeNow;
        //Save
        $talentData->save();

        return $talentData;
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateImageProfile($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $this->editImageProfile($id, $request->all(), $request);

            DB::commit();

            // return.
            $single = new TalentResource($data);
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
     * Display a listing of the resource.
     */
    public function talentByCategory(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'fullname';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $category_id = $request->input('category_id');
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND talents.fullname LIKE '%$search_term%'";
            }
            $paginate = Talent::query()->select(['talents.*'])
            ->where('category_id1', $category_id)
            ->orWhere('category_id2', $category_id)
            ->orWhere('category_id3', $category_id)
            ->orWhere('category_id4', $category_id)
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Talent::query()
                ->count();

            // paging response.
            $response = TalentResource::collection($paginate);
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