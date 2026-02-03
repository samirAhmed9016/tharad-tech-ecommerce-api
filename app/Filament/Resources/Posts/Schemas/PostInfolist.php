<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;


use Filament\Schemas\Schema;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('title'),

            TextEntry::make('slug'),

            TextEntry::make('category.name')
                ->label('Category'),

            SpatieMediaLibraryImageEntry::make('thumbnail')
                ->collection('thumbnails')
                ->url(fn($record) => $record->getFirstMediaUrl('thumbnails'))
                ->openUrlInNewTab()
                ->placeholder('-'),

            TextEntry::make('tags')
                ->badge(),

            IconEntry::make('is_published')
                ->boolean(),

            TextEntry::make('content')
                ->columnSpanFull(),

            TextEntry::make('created_at')
                ->dateTime(),

            TextEntry::make('updated_at')
                ->dateTime(),
        ]);
    }
}
