<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Filament\Resources\TransaksiResource\RelationManagers;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

use function Laravel\Prompts\form;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggalpemesanan')
                ->required(),
                Forms\Components\TextInput::make('code')
                ->required(),
                Forms\Components\select::make('produk_id')
                ->relationship('produk','namaproduk')
                ->required(),
                Forms\Components\TextInput::make('nama')
                ->required(),
                Forms\Components\TextInput::make('email')
                ->required(),
                Forms\Components\TextInput::make('phone')
                ->numeric()
                ->required(),
                Forms\Components\Select::make('paymentstat')
                ->required(),
                Forms\Components\TextInput::make('totalharga')
                ->numeric()
                ->prefix('IDR')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggalpemesanan'),
                Tables\Columns\TextColumn::make('trx_code'), 
                Tables\Columns\TextColumn::make('produk_id'), 
                Tables\Columns\TextColumn::make('namapemesan'), 
                Tables\Columns\TextColumn::make('email'), 
                Tables\Columns\TextColumn::make('phone'), 
                Tables\Columns\TextColumn::make('paymentstat'), 
                Tables\Columns\TextColumn::make('totalbayar'), 
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
            'edit' => Pages\EditTransaksi::route('/{record}/edit'),
        ];
    }
}
