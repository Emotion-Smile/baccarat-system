<?php

namespace KravanhEco\Report\Modules\Mixed\Actions;

use Illuminate\Support\Collection;
use KravanhEco\Report\Modules\Mixed\DataTransferObjects\TotalWinLoseData;
use KravanhEco\Report\Modules\Mixed\DataTransferObjects\WinLoseItemData;

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
