<?php

namespace App\Kravanh\Domain\Baccarat\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaccaratGameCreateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isMember();
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric'],
            'betOn' => ['required'],
            'betType' => ['required'],
        ];
    }

    public function ip()
    {
        return $this->header('x-vapor-source-ip') ?? parent::ip();
    }
}
