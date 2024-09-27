<?php

namespace Joespace\ACL\Filament\Resources;

use App\Models\User;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Joespace\ACL\Models\Role;
use Joespace\Core\Filament\BaseResource;

class UserResource extends BaseResource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = "ACL";

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('Verified')
                    ->default(function (User $u) {
                        return !!$u->email_verified_at;
                    })->boolean(),
                TextColumn::make('active')
                    ->badge()
                    ->formatStateUsing(fn(bool $state): string => $state ? "Active" : "Deactivated")
                    ->color(fn(bool $state): string => $state ? "success" : "warning"),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('roles.name')->badge()->color("info")
                    ->separator(','),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                ActionGroup::make([

                    Action::make("change_password")
                        ->icon("heroicon-c-shield-exclamation")
                        ->color("danger")
//                        ->slideOver()
                        ->modalWidth(MaxWidth::Small)
                        ->visible(Filament::auth()->user()->hasPermissionTo("change_password"))
                        ->closeModalByClickingAway(false)
                        ->form([
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->confirmed()
                                ->label('Password')
                                ->required()
                                ->rules([
                                    fn(): Closure => function (string $attribute, $value, Closure $fail) {
                                        if (Str::length($value) < '8') {
                                            $fail('The :attribute length must be 8 characters or more.');
                                        }
                                    },
                                ])
                            ,
                            Forms\Components\TextInput::make('password_confirmation')
                                ->password()
                                ->label('Password')
                                ->required(),
                        ])
                        ->action(function (array $data, User $record): void {

                            $record->password = Hash::make($data["password"]);
                            $record->save();

                            Notification::make("Password Changed")->title("Password Changed")->success()->send();
                        }),
                    Action::make('Activate')
                        ->color("success")
                        ->icon("heroicon-c-check-circle")
                        ->hidden(fn(User $record): bool => $record->active || !Filament::auth()->user()->hasPermissionTo("change_status"))
                        ->action(function (User $record) {
                            $record->active = true;
                            $record->save();
                        }),
                    Action::make('Deactivate')
                        ->color("warning")->icon("heroicon-c-x-circle")
                        ->requiresConfirmation()
                        ->visible(fn(User $record): bool => Filament::auth()->user()->hasPermissionTo("change_status")&&$record->active && Filament::auth()->user()->getAuthIdentifier() != $record->getAuthIdentifier())
                        ->action(function (User $record) {
                            $record->active = false;
                            $record->save();
                        }),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make("Basic Details")
                    ->description("Please provide the basic details for the user")->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                    ])->columns(2),

                Forms\Components\Section::make("Login Details")
                    ->description("Please provide a strange password for secure user login")->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->visibleOn("create")
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password_verification')
                            ->password()
                            ->visibleOn("create")
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
                Forms\Components\Section::make("Roles and permissions Details")
                    ->hidden(!Filament::auth()->user()->hasPermissionTo("list_roles"))
                    ->schema(
                    [
                        Select::make('roles')
                            ->visibleOn("edit")
                            ->native(false)
                            ->searchable()
                            ->multiple()
                            ->options(Role::all()->pluck('name', 'name'))
                    ]
                )
                    ->visibleOn("edit")
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }


}
