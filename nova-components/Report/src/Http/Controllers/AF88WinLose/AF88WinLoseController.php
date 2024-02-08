<?php

namespace KravanhEco\Report\Http\Controllers\AF88WinLose;

use App\Kravanh\Domain\Integration\Contracts\Services\AF88Contract;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\AF88\HasApi;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AF88WinLoseController
{
    use HasApi;

    public function __invoke(Request $request): JsonResponse
    {
        return asJson([
            'report' => $this->fetchReport($request) 
        ]);
    }

    protected function fetchReport(Request $request): object
    {
        $token = app(AF88Contract::class)->getToken();

        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->get(
                url: $this->requestUrl('/report/win-lose'),
                query: [
                    'date' => $request->date
                ]
            );

        $responseBody = $response->object();

        if($response->failed()) {
            throw new Exception($responseBody?->message);
        }

        return $responseBody;
    }
}