<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getNavigationLabel(): string
    {
        return __('Products');
    }

    public static function getPluralLabel(): string
    {
        return __('Products');
    }

    public static function getLabel(): string
    {
        return __('Product');
    }

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Section::make([
                    SpatieMediaLibraryFileUpload::make('image')
                        ->label(__('Image'))
                        ->collection('products')
                        ->image()
                        ->required()
                        ->columnSpan('full')
                        ->required(fn(string $operation): bool => $operation === 'create'),
                ])
                    ->description(__('upload an image for the product'))
                    ->collapsible(),
                Section::make([
                    TextInput::make('title')
                        ->label(__('Title'))
                        ->required()
                        ->translatable(),
                    Textarea::make('description')
                        ->label(__('Description'))
                        ->rows(1)
                        ->translatable()
                ])->description(__('Product description'))->columnSpan('full')->collapsible(),

                section::make([
                    Grid::make(3)
                        ->schema([
                            Select::make('category_id')
                                ->label(__('Category'))
                                ->relationship(
                                    name: 'category',
                                    titleAttribute: 'title',
                                    modifyQueryUsing: fn(Builder $query) => $query->active()
                                )
                                ->searchable()
                                ->nullable()
                                ->preload(),

                            TextInput::make('price')
                                ->label(__('Unit Price'))
                                ->required()
                                ->numeric()
                                ->prefix('$'),

                            TextInput::make('quantity')
                                ->label(__('Quantity'))
                                ->required()
                                ->numeric()
                                ->default(0),
                        ])
                ])->description(__('Product details'))->collapsible(),

                Section::make([
                    Toggle::make('status')
                        ->label(__('Status'))
                        ->required()
                        ->columnSpan('full'),
                ])->description(__('Product actions'))->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('products')
                    ->height(50)
                    ->square()
                    ->label(__('Image'))
                    ->toggleable(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->label(__('Product Title'))
                    ->wrap(),

                TextColumn::make('category.title')
                    ->label(__('Category'))
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('price')
                    ->label(__('Unit Price'))
                    ->money('USD')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('quantity')
                    ->sortable()
                    ->alignEnd()
                    ->label(__('Quantity')),

                IconColumn::make('status')
                    ->boolean()
                    ->label(__('Status'))
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->alignCenter(),
            ])
            ->reorderable('quantity')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
