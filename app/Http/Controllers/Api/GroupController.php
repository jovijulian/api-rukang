<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Libraries\ResponseStd;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\GroupResource;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'group_name';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            // Jika dari frontend memaksa limit besar.
            if ($limit > 10) {
                $limit = 10;
            }

            if (!empty($search_term)) {
                $conditions .= " AND groups.group_name LIKE '%$search_term%'";
            }

            $paginate = Group::query()->select(['groups.*'])
                ->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Group::query()
                ->count();

            // paging response.
            $response = GroupResource::collection($paginate);
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
            'group_name' => ['required', 'string', 'min:1', 'max:100'],
        ];

        return Validator::make($data, $arrayValidator);
    }
    protected function create(array $data, Request $request)
    {

        $timeNow = Carbon::now();
        $groupData = new Group();

        // input data process
        $groupData->group_name = $data['group_name'];
        $groupData->created_at = $timeNow;
        $groupData->updated_at = null;
        $groupData->created_by = auth()->user()->fullname;
        $groupData->updated_by = null;

        // save process
        $groupData->save();

        return $groupData;
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
            $single = new GroupResource($model);
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
            $model = Group::query()->find($id);
            if (empty($model)) {
                throw new \Exception("Kelompok tidak ada", 404);
            }
            $single = new GroupResource($model);
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
            'group_name' => ['required', 'string', 'min:1', 'max:100', 'unique:groups,group_name,NULL,id'],
        ];
        return Validator::make($data, $arrayValidator);
    }

    protected function edit($id, array $data, Request $request)
    {
        $timeNow = Carbon::now();

        // Find group by id
        $groupData = Group::find($id);

        if (empty($groupData)) {
            throw new \Exception("Invalid group id", 406);
        }
        $groupData->id = $id;
        $groupData->group_name = $data['group_name'];
        $groupData->updated_at = $timeNow;
        $groupData->updated_by = auth()->user()->fullname;
        //Save
        $groupData->save();

        return $groupData;
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
            $single = new GroupResource($data);
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
     * Remove the specified resource from storage.
     */
    // protected function delete($id)
    // {

    //     $group = Group::find($id);
    //     if ($group == null) {
    //         throw new \Exception("Kelompok tidak ada", 404);
    //     }

    //     $user = User::query()->where('group_id', $group->id)->first();

    //     if ($user != null) {
    //         return throw new \Exception("Data Kelompok digunakan oleh User", 409);
    //     }
    //     $group->deleted_by = auth()->user()->fullname;
    //     $group->save();

    //     $group->delete();

    //     return $group;
    // }
    // public function destroy(string $id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $this->delete($id);
    //         DB::commit();
    //         // return
    //         return ResponseStd::okNoOutput("Kelompok berhasil dihapus.");
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         if ($e instanceof ValidationException) {
    //             return ResponseStd::validation($e->validator);
    //         } else {
    //             Log::error($e->getMessage());
    //             if ($e instanceof QueryException) {
    //                 return ResponseStd::fail(trans('error.global.invalid-query'));
    //             } else {
    //                 return ResponseStd::fail($e->getMessage(), $e->getCode());
    //             }
    //         }
    //     }
    // }

    public function getGroups(Request $request): JsonResponse
    {
        try {
            $search_term = $request->input('search');
            $limit = $request->has('limit') ? $request->input('limit') : 10;
            $sort = $request->has('sort') ? $request->input('sort') : 'updated_at';
            $order = $request->has('order') ? $request->input('order') : 'ASC';
            $conditions = '1 = 1';
            if (!empty($search_term)) {
                $conditions .= " AND group_name LIKE '%$search_term%'";
            }
            $paginate = Group::query()->whereRaw($conditions)
                ->orderBy($sort, $order)
                ->paginate($limit);

            $countAll = Group::query()->count();
            $response = GroupResource::collection($paginate);
            return ResponseStd::pagedFrom($response, $paginate, $countAll);
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

    public function datatable(Request $request)
    {
        //SETUP
        $columns = array();

        foreach ($request->columns as $columnData) {
            $columns[] = $columnData['data'];
        }
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->has('order.0.column')] ? 'group_name'  : $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        //QUERI CUSTOM
        $totalData = Group::count();
        if (empty($request->input('search.value'))) {
            //QUERI CUSTOM
            $data = Group::offset($start)->limit($limit)->orderBy($order, $dir)->get();
            $totalFiltered = $totalData;
        } else {
            $search = $request->input('search.value');
            $conditions = '1 = 1';
            if (!empty($search)) {
                $conditions .= " AND group_name LIKE '%" . trim($search) . "%'";
                $conditions .= " OR created_by LIKE '%" . trim($search) . "%'";
                $conditions .= " OR updated_by LIKE '%" . trim($search) . "%'";
            }
            //QUERI CUSTOM
            $data =  Group::whereRaw($conditions)
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            //QUERI CUSTOM
            $totalFiltered = Group::whereRaw($conditions)->count();
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );
        return json_encode($json_data);
    }
}
