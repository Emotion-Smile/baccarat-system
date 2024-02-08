<?php

namespace KravanhEco\Report\Http\Controllers\T88WinLose;

use App\Kravanh\Domain\Integration\Contracts\Services\T88Contract;
use App\Kravanh\Domain\Integration\Supports\Helpers\HttpJsonHelper;
use App\Kravanh\Domain\Integration\Supports\Traits\T88\HasApi;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use KravanhEco\Report\Http\Requests\T88Request;

class T88WinLoseController
{
    use HasApi;

    public function __invoke(T88Request $request): JsonResponse
    {
        $user = $request->getPerformUser();

        return asJson([
            'currentUser' => $user, 
            'report' => $this->fetchReport($request, $user),
            'previousUser' => $request->getPreviousUser($user)
        ]);
    }

    protected function fetchReport(Request $request, User $user): object
    {
        $token = App::make(T88Contract::class)->getToken();
        
        $response = HttpJsonHelper::prepare()
            ->authorization($token)
            ->post(
                url: $this->requestUrl('win-loss-report'),
                data: [
                    'date' => $request->get('date') ?? 'today',
                    'name' => $user->name,
                    'level' => $user->type->value,
                    'from' => $request->get('from'),
                    'to' => $request->get('to')
                ]
            );

        $responseBody = $response->object();

        if($response->failed()) {
            throw new Exception($responseBody?->message);
        }

        return $responseBody;
    }
}