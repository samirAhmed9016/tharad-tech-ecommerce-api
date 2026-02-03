<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;


class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')
                ->required()
                ->maxLength(255),

            TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true),

            Select::make('category_id')
                ->relationship('category', 'name')
                ->required(),

            Textarea::make('content')
                ->required()
                ->columnSpanFull(),

            SpatieMediaLibraryFileUpload::make('thumbnail')
                ->collection('thumbnails')
                ->image(),

            TagsInput::make('tags'),

            Toggle::make('is_published')
                ->default(false),
        ]);
    }
}
