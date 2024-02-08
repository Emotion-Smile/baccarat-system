<?php

namespace KravanhEco\Balance;

use Laravel\Nova\Fields\Field;

class Balance extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'balance';

    public function currency(string $currency): self  
    {
        return $this->withMeta([
            'currency' => $currency
        ]);
    }
}
