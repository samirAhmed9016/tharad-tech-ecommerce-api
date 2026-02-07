<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderItemResource\Pages;
use App\Filament\Resources\OrderItemResource\RelationManagers;
use App\Models\OrderItem;
use Filament\Forms;
use Filament\Forms\Components\Grid as ComponentsGrid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;

class OrderItemResource extends Resource
{
    protected static ?string $model = OrderItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';




    public static function getNavigationLabel(): string
    {
        return __('Order Items');
    }
    public static function getPluralLabel(): string
    {
        return __('Order Items');
    }

    public static function getLabel(): string
    {
        return __('Order Item');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('product_id')
                    ->required()
                    ->maxLength(36),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('subtotal')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('order.order_number')
                    ->label(__('Order Number'))
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->color('primary'),

                TextColumn::make('order.user.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('product.title')
                    ->label(__('Product'))
                    // ->getStateUsing(function ($record) {
                    //     $title = $record->product->title ?? [];
                    //     return is_array($title) ? ($title['en'] ?? $title['ar'] ?? '—') : $title;
                    // })
                    ->translateLabel()
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('quantity')
                    ->label(__('Quantity'))
                    ->numeric()
                    ->sortable()
                    ->summarize(Sum::make()),

                TextColumn::make('price')
                    ->label(__('Unit Price'))
                    ->prefix('EGP ')
                    ->money('EGP')
                    ->sortable(),

                TextColumn::make('subtotal')
                    ->label(__('Subtotal'))
                    ->prefix('EGP ')
                    ->money('EGP')
                    ->sortable()
                    ->summarize(Sum::make()->prefix('EGP '))
                    ->weight('bold'),

                TextColumn::make('order.status')
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

                TextColumn::make('order.payment_status')
                    ->label(__('Payment'))
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->toggleable(),

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
                SelectFilter::make('order_id')
                    ->label(__('Order'))
                    ->relationship('order', 'order_number')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('product_id')
                    ->label(__('Product'))
                    ->relationship('product', 'id')
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $title = $record->title ?? [];
                        return is_array($title) ? ($title['en'] ?? $title['ar'] ?? $record->id) : $title;
                    })
                    ->searchable()
                    ->preload(),
                Tables\Filters\Filter::make('order_status')
                    ->label(__('Order Status'))
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->multiple(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $query->when($data['status'] ?? null, function ($q, $statuses) {
                            $q->whereHas('order', function ($q) use ($statuses) {
                                $q->whereIn('status', $statuses);
                            });
                        });
                    }),
                Tables\Filters\Filter::make('payment_status')
                    ->label(__('Payment Status'))
                    ->form([
                        Forms\Components\Select::make('payment_status')
                            ->label(__('Payment Status'))
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->multiple(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        $query->when($data['payment_status'] ?? null, function ($q, $statuses) {
                            $q->whereHas('order', function ($q) use ($statuses) {
                                $q->whereIn('payment_status', $statuses);
                            });
                        });
                    }),

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
                    ->query(fn(Builder $query) => $query->where('subtotal', '>', 500)),

                Tables\Filters\Filter::make('today')
                    ->label(__('Today'))
                    ->query(fn(Builder $query) => $query->whereDate('created_at', today())),

            ], layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrderItems::route('/'),
            // 'edit' => Pages\EditOrderItem::route('/{record}/edit'),
        ];
    }
}
