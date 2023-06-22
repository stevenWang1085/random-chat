<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFriendRequest;
use App\Management\Services\UserFriendService;
use App\Management\Transformers\UserFriendTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserFriendController extends Controller
{
    private $service;
    private $transform;

    public function __construct()
    {
        $this->service = new UserFriendService();
        $this->transform = new UserFriendTransformer();
    }

    public function list(UserFriendRequest $request, $user_id)
    {
        try {
            $filters = [
                'user_id'  => $user_id,
                'status'   => $request->status,
                'page'     => $request->page ?? 1,
                'paginate' => $request->paginate ?? 20,
            ];
            $data = $this->service->index($filters);
            if ($data->isEmpty()) {
                return $this->responseMaker(101, []);
            }
            $result = $this->transform->transformFriendList($data, $filters);
            $response = $this->responseMaker(100, $result);
        } catch (\Exception $exception) {
            Log::error('friend list error', [
                'line' => $exception->getTrace()[0],
                'msg' => $exception->getMessage()
            ]);
            $response = $this->responseMaker(500, $exception->getMessage());
        }

        return $response;
    }

    public function store(UserFriendRequest $request)
    {
        try {
            DB::beginTransaction();
            $filters = [
                'from_user_id' => Auth::id(),
                'to_user_id'   => $request->to_user_id,
                'status'       => $request->status
            ];
            $data = $this->service->store($filters);
            DB::commit();
            $response = $this->responseMaker($data['code'], null);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            if ($exception->getCode() == '23000') {
                $response = $this->responseMaker(701, $exception->getMessage());
            } else {
                $response = $this->responseMaker(500, $exception->getMessage());
            }

        }

        return $response;
    }

    public function update(UserFriendRequest $request)
    {
        try {
            DB::beginTransaction();
            $filters = [
                'user_friend_id' => $request->user_friend_id,
                'status'         => $request->status
            ];
            $this->service->update($filters);
            DB::commit();
            $response = $this->responseMaker(300, null);
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            $response = $this->responseMaker(500, $exception->getMessage());
        }

        return $response;
    }
}
