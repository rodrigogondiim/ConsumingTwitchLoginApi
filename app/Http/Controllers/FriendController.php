<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\{IndexUserRequest, StoreFriendRequest};
use App\Http\Services\FriendService;
use App\Models\Friend;
use Illuminate\Http\JsonResponse;

class FriendController extends Controller
{

    public function __construct(private FriendService $service)
    {   
    }

    public function index(): JsonResponse
    {
        $result = $this->service->index();
        return response()->json($result);
    }

    public function store(StoreFriendRequest $request): JsonResponse
    {
        $result = $this->service->store($request->user->id);
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

    public function showAcceptFriends(Friend $friend): JsonResponse
    {
        $result = $this->service->showAcceptFriends($friend);
        return response()->json($result);
    }

    public function showRecuseFriends(Friend $friend): JsonResponse
    {
        $result = $this->service->showRecuseFriends($friend);
        return response()->json($result);
    }
    
}
