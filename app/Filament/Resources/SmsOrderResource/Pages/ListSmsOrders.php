<?php

namespace App\Filament\Resources\SmsOrderResource\Pages;

use App\Filament\Resources\SmsOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmsOrders extends ListRecords
{
    protected static string $resource = SmsOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
