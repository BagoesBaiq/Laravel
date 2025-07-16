<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdukResource\Pages;
use App\Models\Produk;
use App\Models\Daerah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\FileUpload;

class ProdukResource extends Resource
{
    protected static ?string $model = Produk::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('daerah_id')
                    ->label('Daerah')
                    ->relationship('daerah', 'nama_daerah')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Daerah')
                    ->placeholder('Pilih Daerah'),
                
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Makanan/Minuman')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Select::make('kategori')
                    ->options([
                        'makanan' => 'Makanan',
                        'minuman' => 'Minuman',
                    ])
                    ->required()
                    ->placeholder('Pilih Jenis'),
                
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                
                FileUpload::make('gambar')
                    ->image()
                    ->directory('produk')
                    ->visibility('public')
                    ->required(),
                
                Forms\Components\RichEditor::make('resep')
                    ->label('Resep')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('daerah.nama_daerah')
                    ->label('Daerah')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('kategori'),
                
                Tables\Columns\ImageColumn::make('gambar')
                    ->disk('public')
                    ->label('Gambar'),
                
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
                Tables\Filters\SelectFilter::make('daerah_id')
                    ->label('Daerah')
                    ->relationship('daerah', 'nama_daerah'), // <-- DIPERBAIKI
                
                Tables\Filters\SelectFilter::make('kategori')
                    ->options([
                        'makanan' => 'Makanan',
                        'minuman' => 'Minuman',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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