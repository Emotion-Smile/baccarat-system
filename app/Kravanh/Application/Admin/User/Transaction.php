<?php

namespace App\Kravanh\Application\Admin\User;

use App\Kravanh\Application\Admin\Resource;
use App\Kravanh\Domain\User\Models\Transaction as TransactionModel;
use App\Kravanh\Domain\User\Supports\Enums\TransactionType;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;

class Transaction extends Resource
{
    public static $model = TransactionModel::class;

    public static $title = 'label';

    public static $search = ['amount'];
    public static $with = ['payable:id,currency'];

    public static $displayInNavigation = false;

    public static function label(): string
    {
        return 'Transaction';
    }

    public static $globallySearchable = false;

    public function subtitle(): ?string
    {
        return $this->label;
    }

    public function fields(Request $request): array
    {

        return [
            Select::make('Type')
                ->options(TransactionType::list()),
            Text::make('Amount', function () {
                return priceFormat(fromKHRtoCurrency($this->amount, $this->payable->currency), $this->payable->currency);
            })
                ->onlyOnIndex()
                ->sortable(),
            DateTime::make('Created At')
        ];
    }
}
