<?php

namespace KravanhEco\Report\Http\Controllers\AF88MemberOutstanding;

use App\Kravanh\Domain\Integration\Contracts\Services\AF88Contract;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\AF88\HasApi;
use Exception;
use Illuminate\Http\JsonResponse;

class AF88MemberOutstandingDetailController
{
    use HasApi;

    public function __invoke(int $accountId): JsonResponse
    {
        return asJson([
            'report' => $this->fetchMemberOutstandingDetail($accountId) 
        ]);
    }

    protected function fetchMemberOutstandingDetail(int $accountId): object
    {
        $token = app(AF88Contract::class)->getToken();

        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->get(
                url: $this->requestUrl("/report/member/outstanding/detail/{$accountId}"),
            );

        $responseBody = $response->object();

        if($response->failed()) {
            throw new Exception($responseBody?->message);
        }

        return $responseBody;
    }
}