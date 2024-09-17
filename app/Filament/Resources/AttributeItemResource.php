<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttributeItemResource\Pages;
use App\Filament\Resources\AttributeItemResource\RelationManagers;
use App\Models\AttributeItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttributeItemResource extends Resource
{
    protected static ?string $model = AttributeItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('attribute_id')
                    ->relationship('attribute', 'name')
                    ->nullable(),
                Forms\Components\Select::make('parent_id')
                    ->relationship('parent', 'value')
                    ->nullable(),
                Forms\Components\TextInput::make('value')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('attribute.name'),
                Tables\Columns\TextColumn::make('parent.value'),
                Tables\Columns\TextColumn::make('value'),
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
            'index' => Pages\ListAttributeItems::route('/'),
            'create' => Pages\CreateAttributeItem::route('/create'),
            'edit' => Pages\EditAttributeItem::route('/{record}/edit'),
        ];
    }
}
