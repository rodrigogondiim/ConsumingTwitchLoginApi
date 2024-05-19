<?php

namespace App\Http\Services;

use App\Models\User;
use App\Enum\UserType;
use Illuminate\Database\Eloquent\Collection;

class UserService
{

    public function index(?string $search = null): Collection
    {
        return User::select('id', 'name', 'picture')->whereNot(
            fn($q) => $q->whereId(auth()->user()->id)
        )->when($search, fn($q) => $q->where('name', 'ilike', "%$search%"))->get();
    }

    public function show(string $id): Collection
    {
        return User::select('id', 'name', 'picture')->whereNot(
            fn($q) => $q->whereId(auth()->user()->id)
        )->whereId($id)->get();
    }

    public function store(string $name, string $email, string $password): User
    {
        $image = 'https://static-cdn.jtvnw.net/user-default-pictures-uv/215b7342-def9-11e9-9a66-784f43822e80-profile_image-150x150.png';
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'picture' => $image, 
            'type' => UserType::NORMAL
        ];

        return User::create($data);
    }

    public function view(bool $view): void
    {
        User::whereId(auth()->user()->id)
            ->update(['view_notification' => $view]);
    }

}
