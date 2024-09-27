<?php

namespace Joespace\ACL\Filament\Resources\RoleResource\Pages;

use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Joespace\ACL\Filament\Resources\RoleResource;
use Spatie\Permission\Models\Role;

class ListUsers extends Page
{
    use InteractsWithRecord;

    protected static string $resource = RoleResource::class;

    protected static string $view = 'filament.resources.role-resource.pages.list-users';


    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        /**
         * @var $record Role
         */
        $record = $this->record;
        dd($record->users);
    }

}
