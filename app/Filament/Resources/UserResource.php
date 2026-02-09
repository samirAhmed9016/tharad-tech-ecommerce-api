<?php

namespace App\Filament\Resources;

use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use BezhanSalleh\FilamentShield\Traits\HasShieldFormComponents;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\{Builder, Collection, SoftDeletingScope};
use Filament\Tables\Actions\{EditAction, DeleteAction, RestoreAction, ForceDeleteAction, BulkActionGroup};
use Carbon\Carbon;
use Filament\Forms\Components\Placeholder;


class UserResource extends Resource implements HasShieldPermissions
{
    use HasShieldFormComponents;
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;

    public static function getPluralModelLabel(): string
    {
        return 'Users';
    }
    /**
     * Get navigation badge with total users count
     */
    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count();
    }

    /**
     * Get badge color
     */
    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    /**
     * Get singular label
     */
    public static function getLabel(): string
    {
        return __('User');
    }

    /**
     * Get plural label
     */
    public static function getPluralLabel(): string
    {
        return __('Users');
    }
    public static function getNavigationLabel(): string
    {
        return __('Users');
    }



    /**
     * Define form schema for creating and editing users
     */
    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Tabs::make(__('User Information'))
                    ->columnSpanFull()
                    ->tabs([
                        // === INFORMATION TAB ===
                        Tabs\Tab::make(__('Information'))
                            ->icon('heroicon-s-information-circle')
                            ->schema([
                                Section::make(__('Personal Information'))
                                    ->columnSpan('full')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label(__('Full Name'))
                                            ->required()
                                            ->placeholder(__('Enter user full name'))
                                            ->columnSpan(1),

                                        TextInput::make('email')
                                            ->label(__('Email Address'))
                                            ->email()
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->placeholder(__('user@example.com'))
                                            ->prefixIcon('heroicon-o-envelope')
                                            ->columnSpan(1),

                                        TextInput::make('password')
                                            ->label(__('Password'))
                                            ->password()
                                            ->revealable()
                                            ->dehydrated(fn($state) => filled($state))
                                            ->required(fn(string $context) => $context === 'create')
                                            ->rule('confirmed')
                                            ->placeholder(__('Enter password'))
                                            ->prefixIcon('heroicon-o-lock-closed')
                                            ->maxLength(255)
                                            ->columnSpan(1),

                                        TextInput::make('password_confirmation')
                                            ->label(__('Confirm Password'))
                                            ->password()
                                            ->revealable()
                                            ->dehydrated(false)
                                            ->required(fn(string $context) => $context === 'create')
                                            ->placeholder(__('Confirm password'))
                                            ->maxLength(255)
                                            ->columnSpan(1),

                                        Forms\Components\Select::make('type')
                                            ->label(__('User Type'))
                                            ->relationship('roles', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable()
                                            ->columnSpan(2),

                                        Toggle::make('email_verified_at')
                                            ->label(__('Email Verified'))
                                            ->inline(false)
                                            ->columnSpan(1),
                                    ]),
                            ]),

                        // === EMAIL VERIFICATION TAB ===
                        Tabs\Tab::make(__('Email Verification'))
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                Section::make(__('Email Verification Details'))
                                    ->columnSpan('full')
                                    ->columns(2)
                                    ->schema([
                                        Toggle::make('is_email_verified')
                                            ->label(__('Email Verified'))
                                            ->disabled()
                                            ->columnSpan(1),

                                        TextInput::make('email_verified_at')
                                            ->label(__('Verified At'))
                                            ->disabled()
                                            ->columnSpan(1),
                                    ]),
                            ]),

                        // === SYSTEM TAB ===
                        Tabs\Tab::make(__('System'))
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make(__('System Information'))
                                    ->columns(2)
                                    ->schema([

                                        Placeholder::make('id')
                                            ->label(__('User ID'))
                                            ->content(fn($record) => $record?->id),

                                        Placeholder::make('created_at')
                                            ->label(__('Created At'))
                                            ->content(
                                                fn($record) =>
                                                $record?->created_at
                                                    ? $record->created_at->diffForHumans() . ' (' . $record->created_at->format('Y-m-d h:i A') . ')'
                                                    : '-'
                                            ),

                                        Placeholder::make('updated_at')
                                            ->label(__('Last Updated'))
                                            ->content(
                                                fn($record) =>
                                                $record?->updated_at
                                                    ? $record->updated_at->diffForHumans() . ' (' . $record->updated_at->format('Y-m-d h:i A') . ')'
                                                    : '-'
                                            ),

                                        Placeholder::make('deleted_at')
                                            ->label(__('Deleted At'))
                                            ->content(
                                                fn($record) =>
                                                $record?->deleted_at
                                                    ? $record->deleted_at->diffForHumans() . ' (' . $record->deleted_at->format('Y-m-d h:i A') . ')'
                                                    : __('Not deleted')
                                            ),

                                    ]),
                            ]),
                    ])
                    ->activeTab(1),
            ]);
    }

    /**
     * Define table schema for listing users
     */
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->columns([
                // Row index
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                // User name
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),

                // Email
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->copyable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                // Filter by user type
                SelectFilter::make('type')
                    ->label(__('User Type'))
                    ->options([
                        'user' => __('Regular User'),
                        'admin' => __('Administrator'),
                    ])
                    ->native(false),
            ])
            ->actions([
                EditAction::make()
                    ->label(__('Edit')),

                DeleteAction::make()
                    ->label(__('Delete')),

                RestoreAction::make()
                    ->label(__('Restore')),

                ForceDeleteAction::make()
                    ->label(__('Force Delete')),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('delete')
                        ->label(__('Delete Selected'))
                        ->action(fn(Collection $records) => $records->each->delete())
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('restore')
                        ->label(__('Restore Selected'))
                        ->action(fn(Collection $records) => $records->each->restore())
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('forceDelete')
                        ->label(__('Permanently Delete Selected'))
                        ->action(fn(Collection $records) => $records->each->forceDelete())
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('activate')
                        ->label(__('Activate Selected'))
                        ->action(fn(Collection $records) => $records->each(fn(User $user) => $user->update(['is_active' => true])))
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label(__('Deactivate Selected'))
                        ->action(fn(Collection $records) => $records->each(fn(User $user) => $user->update(['is_active' => false])))
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    /**
     * Get resource pages
     */
    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\UserResource\Pages\ListUsers::route('/'),
            'create' => \App\Filament\Resources\UserResource\Pages\CreateUser::route('/create'),
            'edit' => \App\Filament\Resources\UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }




    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->where('type', '!=', 'user');
    }



    public static function getPermissionPrefixes(): array
    {
        return [
            'view_any',
            'create',
            'update',
            'delete',
            'force_delete',
            'restore',
        ];
    }
}
