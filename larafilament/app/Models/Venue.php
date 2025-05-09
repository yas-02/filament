<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Venue extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = ['name', 'city', 'country', 'postal_code', 'region'];
    protected $casts = [
        'id' => 'integer',
        'region' => Region::class,
    ];

    public function conferences(): HasMany
    {
        return $this->hasMany(Conference::class);
    }

    public static function getForm(): array
    {
        return [
            Wizard::make(
                [
                    Wizard\Step::make('Venue Details')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('postal_code')
                                ->required()
                                ->maxLength(255),
                        ]),
                    Wizard\Step::make('Country Details')
                        ->schema([
                            TextInput::make('city')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('country')
                                ->required()
                                ->maxLength(255),
                            Select::make('region')
                                ->enum(Region::class)
                                ->options(Region::class),
                            SpatieMediaLibraryFileUpload::make('images')
                                ->collection('venue-images')
                                ->multiple()
                                ->image(),
                        ]),
                    Wizard\Step::make('Venue Images')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('images')
                                ->collection('venue-images')
                                ->multiple()
                                ->image(),
                        ]),

                ])
        ];
    }
}
