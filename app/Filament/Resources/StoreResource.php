<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Filament\Resources\StoreResource\RelationManagers;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;
    protected static ?string $modelLabel = '门店管理';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $shouldSkipAuthorization = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('avatar')
                    ->maxLength(191),
                Forms\Components\TextInput::make('industry_id')
                    ->numeric(),
                Forms\Components\TextInput::make('province')
                    ->maxLength(191),
                Forms\Components\TextInput::make('city')
                    ->maxLength(191),
                Forms\Components\TextInput::make('area')
                    ->maxLength(191),
                Forms\Components\TextInput::make('address')
                    ->maxLength(191),
                Forms\Components\TextInput::make('contact_name')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('contact_mobile')
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('contact_wechat')
                    ->maxLength(191),
                Forms\Components\TextInput::make('official_account_qrcode')
                    ->maxLength(191),
                Forms\Components\TextInput::make('official_account_id')
                    ->numeric()
                    ->default(1),
                Forms\Components\Toggle::make('forever'),
                Forms\Components\DateTimePicker::make('expiration_date')
                    ->required(),
                Forms\Components\TextInput::make('images')
                    ->maxLength(191),
                Forms\Components\TextInput::make('introduction')
                    ->maxLength(191),
                Forms\Components\Toggle::make('blocked'),
                Forms\Components\TextInput::make('block_reason')
                    ->maxLength(191),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('avatar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('industry_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('province')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('area')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_mobile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_wechat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('official_account_qrcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('official_account_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('forever')
                    ->boolean(),
                Tables\Columns\TextColumn::make('expiration_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('images')
                    ->searchable(),
                Tables\Columns\TextColumn::make('introduction')
                    ->searchable(),
                Tables\Columns\IconColumn::make('blocked')
                    ->boolean(),
                Tables\Columns\TextColumn::make('block_reason')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
