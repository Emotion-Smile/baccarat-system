<?php


namespace App\Kravanh\Domain\Baccarat\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaccaratGameSubmitResultRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isTraderBaccarat();
    }

    public function rules(): array
    {
        return [
            'dragonResult' => 'required',
            'dragonType' => 'required',
            'tigerResult' => 'required',
            'tigerType' => 'required'
        ];
    }

    public function getGameTableId()
    {
        return $this->user()->getGameTableId();
    }

    public function getDragonResult()
    {
        return $this->get('dragonResult');
    }

    public function getDragonType()
    {
        return $this->get('dragonType');
    }

    public function getTigerResult()
    {
        return $this->get('tigerResult');
    }

    public function getTigerType()
    {
        return $this->get('tigerType');
    }

}
