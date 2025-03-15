<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conference extends Model
{
    use HasFactory;

    protected $table = 'conferences';
    protected $fillable = [
        'name', 'description', 'region', 'start_date', 'end_date', 'status', 'venue_id'
    ];
    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'venue_id' => 'integer',
        'region' => Region::class,
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Conference Details')
                ->collapsible()
                ->description('Provide some basic information about the conference.')
                ->icon('heroicon-o-information-circle')
                ->columns(['md' => 1 , 'lg' => 2])
                ->schema([
                    TextInput::make('name')
                        ->columnSpanFull()
                        ->label('Name Conference')
                        ->prefixIcon('heroicon-o-pencil')
                        ->default('MY CONFERENCE')
                        ->hint('Enter the name of the conference')
                        ->hintIcon('heroicon-o-pencil')
//                    ->hintAction('Edit the conference')
                        ->required()
                        ->markAsRequired(false)
                        ->rules(['max:60']),
                    RichEditor::make('description')
//                Forms\Components\MarkdownEditor::make('description')
                        ->required()
                        ->columnSpanFull()
//                    ->toolbarButtons(['heading', 'italic', 'strike','bold'])
                        ->maxLength(255),
                    DatePicker::make('start_date')
                        ->native(false)
                        ->required(),
                    DateTimePicker::make('end_date')
                        ->required(),
                    Toggle::make('is_published')
                        ->default(false)
                        ->required(),
                    Select::make('region')
                        ->enum(Region::class)
                        ->options(Region::class)
                        ->label('Website')
                        ->prefix('https://')
                        ->suffix('.com'),
                    Fieldset::make('Status')
                        ->columns(1)
                        ->schema([
                            Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Published',
                                    'archived' => 'Archived',
                                ])
                                ->required(),
                            Toggle::make('is_published')
                                ->default(true),
                        ]),
                ]),

            Section::make('Location')
                ->columns(2)
                ->schema([
                    Select::make('region')
                        ->live()
                        ->enum(Region::class)
                        ->options(Region::class),
                    Select::make('venue_id')
                        ->searchable()
                        ->preload()
                        ->createOptionForm(Venue::getForm())
                        ->editOptionForm(Venue::getForm())
                        ->relationship('venue', 'name')
                ]),
            Actions::make([
                Action::make('star')
                    ->label('Fill with Factory Data')
                    ->icon('heroicon-m-star')
                    ->visible(function (string $operation) {
                        if($operation !== 'create') {
                            return false;
                        }
                        if(! app()->environment('local')) {
                            return false;
                        }
                        return true;
                    })
                    ->action(function ($livewire) {
                        $data = Conference::factory()->make()->toArray();
                        $livewire->form->fill($data);
                    }),
            ]),
        ];

    }
}
