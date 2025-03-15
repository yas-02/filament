<?php

namespace App\Models;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'bio', 'twitter_handle', 'qualifications','avatar'];
    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array',
    ];


    const QUALIFICATIONS = [
        'business-leader' => 'Business Leader',
        'charisma' => 'Charismatic Speaker',
        'first-time' => 'First Time Speaker',
        'hometown-hero' => 'Hometown Hero',
        'humanitarian' => 'Works in Humanitarian Field',
        'laracasts-contributor' => 'Laracasts Contributor',
        'twitter-influencer' => 'Large Twitter Following',
        'youtube-influencer' => 'Large YouTube Following',
        'open-source' => 'Open Source Creator / Maintainer',
        'unique-perspective' => 'Unique Perspective'
    ];

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public static function getForm(): array
    {
        return [
            Tabs::make('tabs')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Speakers Details')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            FileUpload::make('avatar')
                            ->avatar()
                            ->image()
                            ->directory('speakers')
                            ->preserveFilenames(),
                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255),
                            Textarea::make('bio')
                                ->required()
                                ->maxLength(65535)
                                ->columnSpanFull(),
                        ]),
                    Tabs\Tab::make('location')
                    ->schema([
                        TextInput::make('twitter_handle')
                            ->required()
                            ->maxLength(255),
                        CheckboxList::make('qualifications')
                            ->columnSpanFull()
                            ->searchable()
                            ->bulkToggleable()
                            ->options(self::QUALIFICATIONS)
                            ->descriptions([
                                'business-leader' => 'Here is a nice long description',
                                'charisma' => 'This is even more information about why you should pick this one',
                            ])
                            ->columns(3),
                    ])
                ])
        ];
    }
}
