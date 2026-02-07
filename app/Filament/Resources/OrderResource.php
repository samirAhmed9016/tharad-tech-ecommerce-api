<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function getNavigationLabel(): string
    {
        return __('Orders');
    }

    public static function getPluralLabel(): string
    {
        return __('Orders');
    }

    public static function getLabel(): string
    {
        return __('Order');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->label(__('Order Number'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('user_id')
                    ->label(__('User ID'))
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('shipping_name')
                    ->label(__('Shipping Name'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('shipping_phone')
                    ->label(__('Shipping Phone'))
                    ->tel()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Textarea::make('shipping_address')
                    ->label(__('Shipping Address'))
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('shipping_city')
                    ->label(__('Shipping City'))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('shipping_postal_code')
                    ->label(__('Shipping Postal Code'))
                    ->maxLength(255),

                Forms\Components\TextInput::make('total_price')
                    ->label(__('Total Price'))
                    ->required()
                    ->numeric(),

                Forms\Components\Select::make('status')
                    ->label(__('Status'))
                    ->required()
                    ->options([
                        'Accepted' => __('Accepted'),
                        'Rejected' => __('Rejected'),
                        'Canceled' => __('Canceled'),
                        'Delivered' => __('Delivered'),
                        'Client Received' => __('Client Received'),
                        'Driver Received' => __('Driver Received'),
                    ]),

                Forms\Components\Select::make('payment_method')
                    ->label(__('Payment Method'))
                    ->required()
                    ->options([
                        'cash' => __('cash'),
                        'online' => __('online'),
                        'wallet' => __('wallet'),
                        'online_wallet' => __('online_wallet'),
                    ]),

                Forms\Components\Select::make('payment_status')
                    ->label(__('Payment Status'))
                    ->required()
                    ->options([
                        'Failed payment' => __('Failed payment'),
                        'Expired' => __('Expired'),
                        'Refunded' => __('Refunded'),
                    ]),

                Forms\Components\Textarea::make('notes')
                    ->label(__('Notes'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('order_number')
                    ->label(__('Order Number'))
                    ->searchable()
                    ->sortable()
                    ->color('primary'),

                TextColumn::make('user.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('shipping_city')
                    ->label(__('City'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('total_price')
                    ->label(__('Total'))
                    ->prefix('EGP ')
                    ->money('EGP')
                    ->sortable()
                    ->summarize(Sum::make()->prefix('EGP '))
                    ->weight('bold'),

                TextColumn::make('status')
                    ->label(__('Order Status'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label(__('Payment'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('payment_method')
                    ->label(__('Method'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('shipping_phone')
                    ->label(__('Phone'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('user_id')
                    ->label(__('Customer'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('status')
                    ->label(__('Order Status'))
                    ->options([
                        'pending' => __('Pending'),
                        'processing' => __('Processing'),
                        'completed' => __('Completed'),
                        'cancelled' => __('Cancelled'),
                    ])
                    ->multiple(),

                SelectFilter::make('payment_status')
                    ->label(__('Payment Status'))
                    ->options([
                        'pending' => __('Pending'),
                        'paid' => __('Paid'),
                        'failed' => __('Failed'),
                    ])
                    ->multiple(),

                SelectFilter::make('payment_method')
                    ->label(__('Payment Method'))
                    ->options([
                        'credit_card' => __('Credit Card'),
                        'debit_card' => __('Debit Card'),
                        'cash_on_delivery' => __('Cash on Delivery'),
                        'bank_transfer' => __('Bank Transfer'),
                    ]),

                Tables\Filters\Filter::make('date_range')
                    ->label(__('Date Range'))
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label(__('Start Date')),
                        Forms\Components\DatePicker::make('end_date')
                            ->label(__('End Date')),
                    ])
                    ->columns(2)
                    ->query(function (Builder $query, array $data) {
                        $query
                            ->when(
                                $data['start_date'] ?? null,
                                fn($q, $d) => $q->whereDate('created_at', '>=', $d)
                            )
                            ->when(
                                $data['end_date'] ?? null,
                                fn($q, $d) => $q->whereDate('created_at', '<=', $d)
                            );
                    }),

                Tables\Filters\Filter::make('high_value')
                    ->label(__('High Value (>500 EGP)'))
                    ->query(fn(Builder $query) => $query->where('total_price', '>', 500)),

                Tables\Filters\Filter::make('today')
                    ->label(__('Today'))
                    ->query(fn(Builder $query) => $query->whereDate('created_at', today())),

                Tables\Filters\Filter::make('pending_orders')
                    ->label(__('Pending Orders'))
                    ->query(fn(Builder $query) => $query->where('status', 'pending')),

                Tables\Filters\Filter::make('unpaid_orders')
                    ->label(__('Unpaid Orders'))
                    ->query(fn(Builder $query) => $query->where('payment_status', 'pending')),
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
