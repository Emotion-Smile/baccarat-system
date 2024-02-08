<?php

namespace App\Kravanh\Domain\DragonTiger\App\Nova\Http\Requests;

use App\Kravanh\Domain\DragonTiger\App\Nova\Forms\ConditionForm;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Laravel\Nova\Fields\Field;

class SaveGameConditionRequest extends FormRequest
{
    public function rules(): array
    {
        return collect(
            ConditionForm::make($this->getUser())->buildFields()
        )
            ->filter(
                fn (Field $field) => $field->attribute
            )
            ->mapWithKeys(
                fn (Field $field) => [
                    $field->attribute => $field->rules
                ]
            )
            ->toArray();
    }

    public function getUser(): User
    {
        return User::find($this->resources);
    }
}