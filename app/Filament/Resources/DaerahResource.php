<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DaerahResource\Pages;
use App\Models\Daerah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DaerahResource extends Resource
{
    protected static ?string $model = Daerah::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Daerah';
    protected static ?string $modelLabel = 'Daerah';
    protected static ?string $pluralLabel = 'Daerah';

    protected static ?int $navigationSort = 1;

        public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_daerah')
                    ->label('Nama Daerah')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Contoh: Jawa Timur'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_daerah')
                    ->label('Nama Daerah')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('makanan_count')
                    ->label('Jumlah Produk')
                    ->counts('makanan'),
                
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
            'index' => Pages\ListDaerahs::route('/'),
            'create' => Pages\CreateDaerah::route('/create'),
            'edit' => Pages\EditDaerah::route('/{record}/edit'),
        ];
    }
}