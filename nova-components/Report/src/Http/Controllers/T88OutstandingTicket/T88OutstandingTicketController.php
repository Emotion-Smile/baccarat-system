<?php

namespace KravanhEco\Report\Http\Controllers\T88OutstandingTicket;

use App\Kravanh\Domain\Integration\Contracts\Services\T88Contract;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\T88\HasApi;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use KravanhEco\Report\Http\Requests\T88Request;

class T88OutstandingTicketController
{
    use HasApi;

    public function __invoke(T88Request $request): JsonResponse
    {
        return asJson([
            'outstandingTicket' => $this->fetchOutstandingTicket($request)
        ]);
    }

    protected function fetchOutstandingTicket(Request $request): object
    {
        $token = App::make(T88Contract::class)->getToken();

        $user = $request->getPerformUser();

        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->get(
                url: $this->requestUrl('outstanding-bets'),
                query: [
                    'name' => $user->name,
                    'type' => $user->type->value,
                    'page' => $request->page ?? 1
                ]
            );

        $responseBody = $response->object();

        if ($response->failed()) {
            throw new Exception($responseBody?->message);
        }

        return $responseBody;
    }
}
