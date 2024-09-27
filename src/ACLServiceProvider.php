<?php

namespace Joespace\ACL;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Joespace\ACL\Models\Role;
use Joespace\ACL\Polices\RolePolicy;
use Joespace\ACL\Polices\UserPolicy;

class ACLServiceProvider extends ServiceProvider
{

    public function boot(): void
    {

//        $this->publishesMigrations([
//            __DIR__ . '/../database/migrations' => database_path('migrations'),
//        ]);
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');


        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(User::class, UserPolicy::class);


    }

    public function register(): void
    {

    }
}
