<?php

namespace KravanhEco\Report\Modules\Cockfight\Actions;

use Illuminate\Support\Collection;
use KravanhEco\Report\Modules\Cockfight\DataTransferObjects\WinLoseItemData;

class WinLoseBuilderAction
{
    private Collection $items;

    public function __construct(Collection $items)
    {
        $this->items = $items->mapInto(WinLoseItemData::class);
    }

    public static function from(Collection $items): static
    {
        return new static($items);
    }

    public function items(): Collection
    {
        return $this->items;
    }
}
