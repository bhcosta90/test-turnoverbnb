<?php

declare(strict_types = 1);

namespace App\Http\Resources;

use function formatDate;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use Storage;

class DepositResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'description' => $this->description,
            'value'       => formatNumber($this->value),
            'status'      => $this->status,
            'receipt'     => Storage::url($this->receipt),
            'created_at'  => formatDate($this->created_at),
        ];
    }
}
