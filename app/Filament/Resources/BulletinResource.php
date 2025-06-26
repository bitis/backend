<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BulletinResource\Pages;
use App\Models\Bulletin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BulletinResource extends Resource
{
    protected static ?string $model = Bulletin::class;
    protected static ?string $modelLabel = '公告';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static bool $shouldSkipAuthorization = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->maxLength(191),
                Forms\Components\RichEditor::make('content')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('content')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('show_at'),
                Forms\Components\Toggle::make('top')
                    ->required(),
                Forms\Components\TextInput::make('sort_num')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_show')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('show_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('top')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_num')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_show')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBulletins::route('/'),
            'create' => Pages\CreateBulletin::route('/create'),
            'edit' => Pages\EditBulletin::route('/{record}/edit'),
        ];
    }
}
