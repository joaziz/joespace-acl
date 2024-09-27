<?php

namespace Joespace\ACL\Filament\Resources\RoleResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Joespace\ACL\Filament\Resources\RoleResource;
use Spatie\Permission\Models\Role;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /**
         * @var $model Role
         */
        $model = static::getModel()::create(["name" => $data["name"]]);
        $model->syncPermissions($data["permissions"]);
        return $model;
    }
}
