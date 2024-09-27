<?php

namespace Joespace\ACL\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Joespace\ACL\Filament\Resources\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function mount($record): void
    {
        /**
         * @var $record User
         */
        parent::mount($record);
        $record = $this->record;
        $this->form->fill(array_merge(
            $record->toArray(),
            ["roles" => $record->roles()->pluck("name")->toArray()]
        ));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /**
         * @var $record User
         */

        $data = collect($data);
        $record->update($data->except("roles")->toArray());
        if ($data->offsetExists("roles"))
            $record->syncRoles($data->only("roles"));
        return $record;
    }

}
