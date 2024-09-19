<?php

declare(strict_types = 1);

namespace App\Http\Requests\Deposit;

use App\Models\Deposit;
use Illuminate\Foundation\Http\FormRequest;

class CreateDepositRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => 'required|string|max:120',
            'value'       => 'required|numeric|min:0.01|max:4000000000',
            'receipt'     => 'required|file|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function authorize(): bool
    {
        return auth()->user()->can('create', Deposit::class);
    }
}
