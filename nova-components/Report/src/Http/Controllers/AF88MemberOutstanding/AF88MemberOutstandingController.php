<?php

namespace KravanhEco\Report\Http\Controllers\AF88MemberOutstanding;

use App\Kravanh\Domain\Integration\Contracts\Services\AF88Contract;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\AF88\HasApi;
use Exception;
use Illuminate\Http\JsonResponse;

class AF88MemberOutstandingController
{
    use HasApi;

    public function __invoke(): JsonResponse
    {
        return asJson([
            'report' => $this->fetchMemberOutstanding() 
        ]);
    }

    protected function fetchMemberOutstanding(): object
    {
        $token = app(AF88Contract::class)->getToken();

        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->get(
                url: $this->requestUrl('/report/member/outstanding'),
            );

        $responseBody = $response->object();

        if($response->failed()) {
            throw new Exception($responseBody?->message);
        }

        return $responseBody;
    }
}