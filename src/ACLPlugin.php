<?php

namespace Joespace\ACL;

use Filament\Panel;
use Joespace\ACL\Filament\Resources\RoleResource;
use Joespace\ACL\Filament\Resources\UserResource;
use Joespace\Core\Contracts\Filament\ActivePluginInterface;
use Joespace\Core\Contracts\PermissionFactoryInterface;
use Joespace\Core\Support\ACL\PermissionFactory;
use Joespace\Core\Support\ACL\PermissionItem;

class ACLPlugin implements ActivePluginInterface
{


    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return "acl";
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            UserResource::class,
            RoleResource::class
        ]);
    }

    public function boot(Panel $panel): void
    {
        // TODO: Implement boot() method.
    }

    public function GetPermissionsLookup(): PermissionFactoryInterface
    {
       return PermissionFactory::Make()
           ->MakeCRUDItems("user")
           ->Merge(PermissionFactory::Make()->MakeCRUDItems("role"))
           ->Add(new PermissionItem("change_password","users"))
           ->Add(new PermissionItem("change_status","users"));
    }
}
