<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Navigation\NavigationGroup;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return 'Zamówienie';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Zamówienia';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->label('Numer')
                    ->disabled()
                    ->dehydrated(false)
                    ->visible(fn ($record) => $record && $record->exists),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->email(),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefon'),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Oczekujące',
                        'processing' => 'W trakcie realizacji',
                        'completed' => 'Gotowe',
                        'closed' => 'Zakończone',
                        'declined' => 'Odrzucone',
                    ])
                    ->default('pending')
                    ->required(),
                Forms\Components\TextInput::make('subiekt_zk')
                    ->label('Zk'),
                Forms\Components\TextInput::make('total_price')
                    ->label('Cena całkowita')
                    ->numeric()
                    ->prefix('zł'),
                Forms\Components\Select::make('payment_status')
                    ->label('Status płatności')
                    ->options([
                        'unsettled' => 'Nierozliczone',
                        'settled' => 'Rozliczone',
                        
                    ])
                    ->default('unsettled'),
                    Forms\Components\Select::make('source')
                    ->label('Źródło')
                    ->options([
                        'shop' => 'Sklep',
                        'net' => 'Net',
                        
                    ])
                    ->default('shop'),
                Forms\Components\TextInput::make('deposit_amount')
                    ->label('Zaliczka')
                    ->numeric()
                    ->prefix('zł'),

                    Forms\Components\Select::make('product_id')
                    ->label('Produkt')
                    ->options(Product::pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                    
                Forms\Components\Textarea::make('notes')
                    ->label('Uwagi')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Numer zamówienia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Numer telefonu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subiekt_zk')
                    ->label('zk')
                    ->searchable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Oczekujące',
                        'processing' => 'W trakcie realizacji',
                        'completed' => 'Gotowe',
                        'closed' => 'Zakończone',
                        'declined' => 'Odrzucone',
                    ])
                    ->disablePlaceholderSelection(),
                Tables\Columns\SelectColumn::make('payment_status')
                    ->label('Status płatności')
                    ->options([
                        'unsettled' => 'Nierozliczone',
                        'settled' => 'Rozliczone',
                    ])
                    ->disablePlaceholderSelection(),
                    Tables\Columns\SelectColumn::make('source')
                    ->label('Źródło')
                    ->options([
                        'shop' => 'Sklep',
                        'net' => 'Net',
                    ])
                    ->disablePlaceholderSelection(),
                Tables\Columns\TextColumn::make('deposit_amount')
                    ->label('Zaliczka')
                    ->money('pln')
                    ->sortable(),
                // Tables\Columns\TextColumn::make('total_price')
                //     ->label('Łączna cena')
                //     ->money('pln')
                //     ->sortable(),
                    
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
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Oczekujące',
                        'processing' => 'W trakcie realizacji',
                        'completed' => 'Gotowe',
                        'closed' => 'Zakończone',
                        'declined' => 'Odrzucone',
                    ]),
                    SelectFilter::make('source')
                    ->label('Żródło')
                    ->options([
                        'shop' => 'Sklep',
                        'net' => 'Net',
                        
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('cart')
                    ->label('Koszyk')
                    ->icon('heroicon-o-shopping-cart')
                    ->url(function (Model $record): string {
                        $product = $record->product;
                        if ($product && $product->route) {
                            return route($product->route, $record);
                        }
                        // Fallback w przypadku braku szablonu lub trasy
                        return '#';
                    })
                    // ->url(fn (Model $record): string => route('order.print', $record))
                    ->openUrlInNewTab()
                    ->visible(fn (Model $record): bool => $record->product && $record->product->view),
                Action::make('print')
                    ->label('Drukuj')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Model $record): string => route('order.print', $record))
                    ->openUrlInNewTab()
                    ->visible(fn (Model $record): bool => $record->product && $record->product->view)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            
            ->defaultSort('created_at', 'desc');
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
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
