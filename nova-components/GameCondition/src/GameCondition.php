<?php

namespace KravanhEco\GameCondition;

use Laravel\Nova\Fields\Field;

class GameCondition extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'game-condition';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->onlyOnIndex();
    }

    public function text(string $text): GameCondition
    {
        return $this->withMeta([
            'text' => $text
        ]);
    }

    public function resource(?int $resourceId): GameCondition
    {
        return $this->withMeta([
            'resourceId' => $resourceId ?? 0
        ]);
    }

    public function fieldEndpoint(string $endpoint): GameCondition
    {
        return $this->withMeta([
            'fieldEndpoint' => $endpoint
        ]);
    }

    public function executeEndpoint(string $endpoint): GameCondition
    {
        return $this->withMeta([
            'executeEndpoint' => $endpoint
        ]);
    }
}
