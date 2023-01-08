<?php

namespace App\Providers;

use App\Enum\FriendStatus;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('status-friend', function (User $user) {
            $friend = request()->friend;
            return ($friend->to_user_id === $user->id and $friend->status === FriendStatus::PENDENT->value) ?
                Response::allow() : 
                Response::deny('You not has permission.');
        });
    }
}
