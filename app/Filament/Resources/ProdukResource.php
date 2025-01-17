<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Filament\Resources\ProdukResource\RelationManagers;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                ->image()
                ->directory('produks')
                ->required()
                ->columnSpan(2),
                Forms\Components\TextInput::make('namaproduk')
                ->required()
                ->debounce(500)
                ->reactive()
                ->afterStateUpdated(function($state, callable $set){
                    $set('slug', Str::slug($state));
                }),
                Forms\Components\TextInput::make('slug')
                ->required(),
                Forms\Components\select::make('jeniskopi')
                ->options([
                    'robusta'=>'Robusta',
                    'arabica'=>'Arabica'
                ])
                ->required(),
                Forms\Components\select::make('tingkatroasting')
                ->options([
                    'medium'=>'Light',
                    'medium'=>'Medium',
                    'dark'=>'Dark'
                ])
                ->required(),
                Forms\Components\select::make('proseskopi')
                ->options([
                    'wash'=>'Wash',
                    'natural'=>'Natural',
                    'honey'=>'Honey'
                ])
                ->required(),
                Forms\Components\TextInput::make('harga')
                ->numeric()
                ->prefix('IDR')
                ->required(),
                Forms\Components\RichEditor::make('deskripsi')
                ->required(),
        ]);
           
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('namaproduk'),
                Tables\Columns\TextColumn::make('slug'), 
                Tables\Columns\TextColumn::make('jeniskopi'), 
                Tables\Columns\TextColumn::make('tingkatroasting'), 
                Tables\Columns\TextColumn::make('proseskopi'), 
                Tables\Columns\TextColumn::make('slug'), 
                Tables\Columns\TextColumn::make('harga'), 
                Tables\Columns\TextColumn::make('deskripsi'), 
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
            'index' => Pages\ListProduks::route('/'),
            'create' => Pages\CreateProduk::route('/create'),
            'edit' => Pages\EditProduk::route('/{record}/edit'),
        ];
    }
}
