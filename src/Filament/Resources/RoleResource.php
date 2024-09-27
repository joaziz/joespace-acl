<?php

namespace Joespace\ACL\Filament\Resources;


use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Joespace\ACL\Models\Role;
use Joespace\Core\Filament\BaseResource;
use Spatie\Permission\Models\Permission;

class RoleResource extends BaseResource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "ACL";

    public static function form(Form $form): Form
    {

        $permissionsInputs = Permission::all()
            ->groupBy("group")
            ->map(function ($col) {
                return $col->pluck("name", "name")->toArray();
            })
            ->reduce(function ($res, $val, $key) {
                if (!$res)
                    $res = collect();


                $checkList = Forms\Components\CheckboxList::make("permissions")
                    ->label(Str::title($key))
                    ->options($val)
                    ->columnSpanFull()
//                    ->bulkToggleable()
                    ->columns(3);


                $res->put($key, $checkList);
                return $res;
            });

        return $form
            ->schema([
                Section::make('New Role')
                    ->description('Provide the name of the role')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),
                Section::make("Permissions List")
                    ->description("please select the needed permission")
                    ->schema($permissionsInputs->values()->toArray())->columns(3)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->width(50),
                Tables\Columns\TextColumn::make('users.name')->badge()->limitList(2)->color("success")->wrap()->width(100),
                Tables\Columns\TextColumn::make('permissions.name')->badge()->limitList()->wrap()->width(300),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
//self::AttachUserAction()
//                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
//                TextEntry::make("name")

            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

//    public static function getPages(): array
//    {
//        return [
//            'index' => Pages\ListRoles::route('/'),
//            'create' => Pages\CreateRole::route('/create'),
//            'edit' => Pages\EditRole::route('/{record}/edit'),
//            "list" => Pages\ListUsers::route("/{record}/users")
//        ];
//    }

//    public static function getRecordSubNavigation(Page $page): array
//    {
//        return $page->generateNavigationItems([
//            Pages\ListUsers::class,
//        ]);
//    }
//    private static function AttachUserAction(): Tables\Actions\Action
//    {
//        return Tables\Actions\Action::make("attach_users")
//            ->color("warning")
//            ->icon("heroicon-s-user-group")
//            ->label("Allowed Users")
//            ->visible(true)
//            ->fillForm(fn(Role $record): array => ["allowed_users" => $record->users()->whereNot("id", Filament::auth()->user()->getAuthIdentifier())->pluck("id")])
//            ->form([
//                Select::make("allowed_users")
//                    ->label("Allowed Users")
//                    ->multiple()
//                    ->options(User::whereNot("id", Filament::auth()->user()->getAuthIdentifier())->pluck("name", "id"))->searchable()
//            ])->action(function (array $data, Role $record) {
//
//            });
//            ;
//    }
}
