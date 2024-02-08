<?php

namespace App\Kravanh\Application\Admin\Support;

use Closure;
use Laravel\Nova\Actions\Action;
use Pdmfc\NovaFields\ActionButton;

/**
 * @mixin Action
 */
trait UseNovaAction
{

    public function allowToRun(Closure $callback): static
    {

        $this->canSee($callback);
        $this->canRun($callback);

        return $this;
    }


    public static function toInlineButton(
        string $label,
        int    $resourceId = null,
        mixed  $resource = null,
        bool   $canSee = true
    )
    {

        $novaAction = is_null($resource)
            ? self::class
            : self::make($resource);

        return ActionButton::make('')
            ->text($label)
            ->action(
                $novaAction,
                $resourceId
            )
            ->onlyOnIndex()
            ->canSee(fn() => $canSee);
    }

}


