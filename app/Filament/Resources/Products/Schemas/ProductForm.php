<?php

namespace App\Filament\Resources\Products\Schemas;


use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\{FileUpload, Tabs, TextInput, Textarea, Repeater};

// use Filament\Forms\Components\Tabs\Tab;



class ProductForm
{
    public static function configure(Schema $schema): Schema
    {


        return $schema
            ->components([
                TextInput::make('title')
                    ->translatable(true, null, [
                        'ar' => ['required', 'string', 'max:255'],
                        'en' => ['required', 'string', 'max:255'],
                    ])
                    ->required(),
                TextInput::make('description')
                    ->required(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('quantity')
                    ->required()
                    ->numeric(),
            ]);
    }
}
