<?php

namespace App\Filament\Resources\AttendeeResource\Pages;

use App\Filament\Resources\AttendeeResource;
use App\Filament\Resources\AttendeeResource\Widgets\AttendeeChartWidget;
use App\Filament\Resources\AttendeeResource\Widgets\AttendeeStatsWidget;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListAttendees extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = AttendeeResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            AttendeeStatsWidget::class,
            AttendeeChartWidget::class,
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
