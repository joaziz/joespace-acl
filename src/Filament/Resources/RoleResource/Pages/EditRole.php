<?php

namespace Joespace\ACL\Filament\Resources\RoleResource\Pages;
use Joespace\ACL\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;
//    protected static string $view = 'filament.resources.roles.pages.edit';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $data = collect($data);
        $record->update($data->only("name")->toArray());
        $record->syncPermissions($data->only("permissions"));
        return $record;
    }

    public function mount( $record): void
    {
        /**
         * @var $record Role
         */
        parent::mount($record);
       $record = $this->record;

        $this->form->fill([
            "name"=>$record->name,
            'permissions' =>$record->permissions->pluck("name")
        ]);
    }
}
