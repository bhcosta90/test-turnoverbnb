<?php

declare(strict_types = 1);

namespace App\Http\Requests\Order;

use App\Models\Order;
use App\Rules\BalanceRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => 'required|string|max:120',
            'value'       => [
                'required',
                'numeric',
                'min:0.01',
                'max:4000000000',
                new BalanceRule(auth()->user()->profile),
            ],
        ];
    }

    public function authorize(): bool
    {
        return auth()->user()->can('create', Order::class);
    }
}
