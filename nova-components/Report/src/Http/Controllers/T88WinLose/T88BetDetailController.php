<?php

namespace KravanhEco\Report\Http\Controllers\T88WinLose;

use App\Kravanh\Domain\Integration\Contracts\Services\T88Contract;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\T88\HasApi;
// use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use KravanhEco\Report\Http\Requests\T88Request;

class T88BetDetailController
{
    use HasApi;

    public function __invoke(
        T88Request $request, 
        string $name
    ): JsonResponse
    {
        $previousUser = $request->getDetailPreviousUser();

        return asJson([
            'previousUser' => $previousUser,
            'report' => $this->fetchReport(
                request: $request, 
                name: $name
            ) 
        ]);
    }

    protected function fetchReport(
        Request $request,
        string $name
    ): object
    {
        $token = App::make(T88Contract::class)->getToken();

        $queryString = $this->generateQueryString($request);
        
        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->get(
                url: $this->requestUrl("/member-bet-detail/{$name}") . $queryString,
            );

        $responseBody = $response->object();

        if($response->failed()) {
            throw new Exception($responseBody?->message);
        }

        return $responseBody;
    }

    protected function generateQueryString(Request $request): string
    {
        $page = $request->get('page') ?? 1;

        $queries = ["?page={$page}"];
        
        $from = $request->get('from');
        $to = $request->get('to');

        $date = $request->get('date') ?? 'today';
        
        $queries[] = $from && $to ? "from={$from}&to={$to}" : "date={$date}";

        return implode('&', $queries);
    }
}