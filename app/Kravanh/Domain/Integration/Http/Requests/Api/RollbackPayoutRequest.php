<?php

namespace App\Kravanh\Domain\Integration\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RollbackPayoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'game' => 'required|string',
            'amount' => 'required|integer',
            'meta' => 'required|array',
        ];
    }
} 