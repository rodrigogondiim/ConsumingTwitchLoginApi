<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\{IndexUserRequest, StoreUserRequest, ViewNotificationUserRequest};
use App\Http\Resources\ShowUserResource;
use App\Http\Services\UserService;
use App\Models\User;
use Illuminate\Http\{JsonResponse, Response};

class UserController extends Controller
{

    public function __construct(private UserService $service)
    {   
    }

    public function index(IndexUserRequest $req): JsonResponse
    {
        $result = $this->service->index(...$req->validated());
        return response()->json($result);
    }

    public function show(string $name)
    {
        $result = User::whereName($name)
            ->whereNot(fn($q) => $q->whereName(auth()->user()->name))
            ->first();
        return response()->json($result ? new ShowUserResource($result) : []);
    }

    public function store(StoreUserRequest $req): JsonResponse
    {
        $result = $this->service->store(...$req->validated());
        return response()->json($result);
    }

    public function view(ViewNotificationUserRequest $req): Response
    {
        $this->service->view(...$req->validated());
        return response()->noContent();
    }
}
