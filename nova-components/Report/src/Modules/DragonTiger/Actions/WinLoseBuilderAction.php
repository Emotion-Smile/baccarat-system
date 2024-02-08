<?php

namespace KravanhEco\Report\Modules\DragonTiger\Actions;

use Illuminate\Support\Collection;
use KravanhEco\Report\Modules\DragonTiger\DataTransferObjects\TotalWinLoseData;
use KravanhEco\Report\Modules\DragonTiger\DataTransferObjects\WinLoseItemData;

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

    public function total(): TotalWinLoseData
    {
        return TotalWinLoseData::from($this->items);
    }
}
