<?php

declare(strict_types = 1);

namespace App\Http\Requests\Deposit;

use function auth;

use Illuminate\Foundation\Http\FormRequest;

class StatusDepositRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|boolean',
        ];
    }

    public function authorize(): bool
    {
        return auth()->user()->can('edit', $this->route('deposit'));
    }
}
