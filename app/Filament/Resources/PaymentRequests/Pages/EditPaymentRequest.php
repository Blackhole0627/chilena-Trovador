<?php

namespace App\Filament\Resources\PaymentRequests\Pages;

use App\Filament\Resources\PaymentRequests\PaymentRequestResource;
use Filament\Resources\Pages\EditRecord;

class EditPaymentRequest extends EditRecord
{
    protected static string $resource = PaymentRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ...PaymentRequestResource::getRecordActionsForDisplay($this, $this->getRecord()),
        ];
    }
}
