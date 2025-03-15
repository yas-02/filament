<?php

namespace App\Models;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Talk extends Model
{
    use HasFactory;

    protected $fillable = ['title','abstract','speaker_id'];
    protected $casts = [
        'id' => 'integer',
        'speaker_id' => 'integer',
    ];

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }
    public static function getForm()
    {
        return
            [
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Textarea::make('abstract')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Select::make('speaker_id')
                    ->relationship('speaker', 'name')
                    ->required(),
            ];

    }
}
