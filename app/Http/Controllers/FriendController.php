<?php

namespace App\Http\Controllers;

use App\Http\Requests\FriendshipRequest;
use App\Http\Requests\StoreFriendRequest;
use App\Http\Resources\IndexFriendResource;
use App\Http\Services\FriendService;
use App\Models\Friend;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{

    public function __construct(private FriendService $service)
    {
    }

    public function index()
    {
        $result = $this->service->index();
        return IndexFriendResource::collection($result)->response();
    }

    public function store(StoreFriendRequest $request): JsonResponse
    {
        DB::beginTransaction();
        $result = $this->service->store($request->user->id);
        DB::commit();
        return response()->json($result);
    }

    public function show(Friend $friend): JsonResponse
    {
        $result = $this->service->show($friend->id);
        return response()->json($result);
    }

    public function showPendencyFriends(): JsonResponse
    {
        $result = $this->service->showPendencyFriends();
        return response()->json($result);
    }

    public function friendship(FriendshipRequest $request, Friend $friend): JsonResponse
    {
        $result = $this->service->friendship($friend, ...$request->validate());
        return response()->json($result);
    }
}
