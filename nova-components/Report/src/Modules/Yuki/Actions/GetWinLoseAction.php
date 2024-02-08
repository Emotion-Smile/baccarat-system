<?php

namespace KravanhEco\Report\Modules\Yuki\Actions;

use App\Kravanh\Domain\Integration\Contracts\Services\T88Contract;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\T88\HasApi;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GetWinLoseAction
{
    use HasApi;

    public function __invoke(User $user, string $date): Collection
    {
        $dateFilter = $this->getDateFilter($date);
        
        $token = app(T88Contract::class)->getToken();
        
        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->post(
                url: $this->requestUrl('win-loss-report'),
                data: [
                    'name' => $user->name,
                    'level' => $user->type->value,
                    ...$dateFilter
                ]
            );

        if($response->failed()) {
            throw new Exception($response->object()?->message);
        }

        return collect($response->json()['data']);
    }

    protected function getDateFilter(string $date): array
    {
        $dateRange = $this->dateRangeHandler($date);

        if(is_array($dateRange)) {
            return [
                'date' => 'today',
                'from' => $dateRange[0],
                'to' => $dateRange[1],
            ];
        }
        
        return [
            'date' => $date,
        ];
    }

    protected function dateRangeHandler(string $date): string|array
    {
        if (!Str::of($date)->contains(',')) {
            return $date;
        }

        $dateRange = Str::of($date)->explode(',');

        return [
            $dateRange->first(),
            $dateRange->last()
        ];
    }
}
