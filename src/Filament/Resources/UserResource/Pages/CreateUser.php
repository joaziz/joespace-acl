<?php

namespace Joespace\ACL\Filament\Resources\UserResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Joespace\ACL\Filament\Resources\UserResource;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
