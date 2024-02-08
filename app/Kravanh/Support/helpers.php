<?php

use App\Kravanh\Application\Admin\User\Agent;
use App\Kravanh\Application\Admin\User\Company;
use App\Kravanh\Application\Admin\User\MasterAgent;
use App\Kravanh\Application\Admin\User\Senior;
use App\Kravanh\Application\Admin\User\SuperSenior;
use App\Kravanh\Application\Admin\User\User;
use App\Kravanh\Domain\Environment\Models\Domain;
use App\Kravanh\Domain\User\Models\Agent as AgentModel;
use App\Kravanh\Domain\User\Models\MasterAgent as MasterAgentModel;
use App\Kravanh\Domain\User\Models\Senior as SeniorModel;
use App\Kravanh\Domain\User\Models\SubAccount;
use App\Kravanh\Domain\User\Models\SuperSenior as SuperSeniorModel;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Kravanh\Support\Enums\Currency;
use App\Models\User as UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Http\Requests\ResourceDetailRequest;
use Laravel\Nova\Http\Requests\ResourceIndexRequest;

if (! function_exists('isIndexView')) {
    function isIndexView($request): bool
    {
        return $request instanceof ResourceIndexRequest;
    }
}

if (! function_exists('isDetailView')) {
    function isDetailView($request): bool
    {
        return $request instanceof ResourceDetailRequest;
    }
}

if (! function_exists('isCreateView')) {
    function isCreateView($request): bool
    {
        return $request instanceof NovaRequest &&
            $request->editMode === 'create';
    }
}

if (! function_exists('isUpdateView')) {
    function isUpdateView($request): bool
    {
        return $request instanceof NovaRequest &&
            $request->editMode === 'update';
    }
}

if (! function_exists('root')) {
    function root(): bool
    {
        return auth()
            ->user()
            ->isRoot();
    }
}

if (! function_exists('makeErrorNotifyMessage')) {
    function makeErrorNotifyMessage($exception): string
    {
        $message = 'Code: '.$exception->getCode().PHP_EOL;
        $message .= 'File: '.$exception->getFile().PHP_EOL;
        $message .= 'Line: '.$exception->getLine().PHP_EOL;
        $message .= 'Message: '.$exception->getMessage().PHP_EOL;
        $message .= 'Url: '.config('app.url');

        return $message;
    }
}

if (! function_exists('user')) {
    function user()
    {
        return request()->user();
    }
}

if (! function_exists('sendToTelegram')) {
    function sendToTelegram($message, $userId = 92192223): void
    {
        if (in_array(app()->environment(), ['production'])) {
            $endpoint = "https://api.telegram.org/bot1871663595:AAEjPrJsT9vIMcEQjxrwwgAKBkIP1k2z4tM/sendMessage?chat_id={$userId}&text={$message}";
            Http::get($endpoint);
        }
    }
}

if (! function_exists('asJson')) {
    function asJson(array|string $message, int $status = 200): JsonResponse
    {
        $data = $message;

        if (is_string($message)) {
            $data = ['message' => $message];
        }

        return response()->json($data, $status);
    }
}

if (! function_exists('isLocal')) {
    function isLocal(): bool
    {
        return app()->environment(['local', 'cypress']);
    }
}

if (! function_exists('s3Url')) {
    function s3Url($value, $expiredInMinutes = 50): string
    {
        return Storage::disk('s3')->temporaryUrl($value, now()->addMinutes($expiredInMinutes));
    }
}

if (! function_exists('fromKHRtoCurrency')) {
    function fromKHRtoCurrency($amount, Currency $toCurrency): float|int
    {
        return $amount / Currency::getDescription($toCurrency->key);
    }
}

if (! function_exists('currencyFormatFromKHR')) {
    function currencyFormatFromKHR($amount, Currency $currency, bool $showPrefix = false): string
    {
        return priceFormat(fromKHRtoCurrency($amount, $currency), $currency, $showPrefix);
    }
}

if (! function_exists('toKHR')) {
    function toKHR($amount, Currency $fromCurrency)
    {
        return $amount * Currency::getDescription($fromCurrency->key);
    }
}
if (! function_exists('priceFormat')) {
    function priceFormat($value, string|Currency $prefix = '៛', $showPrefix = true): string
    {
        $decimal = 0;
        $tempPrefix = $prefix;

        if ($prefix instanceof Currency) {
            $tempPrefix = currencySymbol($prefix);
            $decimal = currencyDecimal($prefix);
        }

        if (! $showPrefix) {
            $tempPrefix = '';
        }

        return html_entity_decode($tempPrefix.'&nbsp;'.number_format($value, $decimal));
    }
}

if (! function_exists('currencySymbol')) {
    function currencySymbol(?Currency $currency): string
    {
        if (! $currency) {
            return '៛';
        }

        return match ($currency->value) {
            Currency::USD => '$',
            Currency::THB => '฿',
            Currency::VND => '₫',
            default => '៛'
        };
    }
}

if (! function_exists('currencyDecimal')) {
    function currencyDecimal(Currency $currency): int
    {
        return match ($currency->value) {
            Currency::USD => 2,
            default => 0
        };
    }
}

if (! function_exists('getResourceByUserType')) {
    function getResourceByUserType($userType): string
    {
        return match ($userType) {
            UserType::COMPANY => Company::uriKey(),
            UserType::SUPER_SENIOR => SuperSenior::uriKey(),
            UserType::SENIOR => Senior::uriKey(),
            UserType::MASTER_AGENT => MasterAgent::uriKey(),
            UserType::AGENT => Agent::uriKey(),
            UserType::DEVELOPER => User::uriKey(),
            default => 'member'
        };
    }
}

if (! function_exists('makeResourceNameFromUriKey')) {
    function makeResourceName($uriKey): string
    {
        return Str::studly(Str::singular($uriKey));
    }
}

if (! function_exists('redirectWith')) {

    function redirectWith(array $payload): RedirectResponse|JsonResponse
    {
        if (request()->wantsJson()) {
            return
                response()
                    ->json($payload);
        }

        return redirect()
            ->back()
            ->with($payload);
    }
}

if (! function_exists('redirectError')) {

    function redirectError(string $message): RedirectResponse|JsonResponse
    {
        $payload = [
            'type' => 'error',
            'message' => $message,
        ];

        if (request()->wantsJson()) {
            return
                response()
                    ->json($payload);
        }

        return redirect()
            ->back()
            ->with($payload);
    }
}

if (! function_exists('redirectSucceed')) {

    function redirectSucceed(string $message, int $statusCode = 200): RedirectResponse|JsonResponse
    {
        $payload = [
            'type' => 'success',
            'message' => $message,
        ];

        if (request()->wantsJson()) {
            return
                response()
                    ->json($payload, $statusCode);
        }

        return redirect()
            ->back()
            ->with($payload);
    }
}

if (! function_exists('formatPayout')) {
    function formatPayout($payoutRate): string
    {
        return $payoutRate >= 1
            ? number_format($payoutRate, 2)
            : str_pad($payoutRate, 4, '0');
    }
}

if (! function_exists('getModelByUserType')) {
    function getModelByUserType(string $userType): string
    {
        return match ($userType) {
            UserType::SUPER_SENIOR => SuperSeniorModel::class,
            UserType::SENIOR => SeniorModel::class,
            UserType::MASTER_AGENT => MasterAgentModel::class,
            UserType::AGENT => AgentModel::class,
            UserType::SUB_ACCOUNT => SubAccount::class,
        };
    }
}

if (! function_exists('exceptUserType')) {
    function exceptUserType(): array
    {
        return [
            UserType::COMPANY,
            UserType::DEVELOPER,
        ];
    }
}

if (! function_exists('canCurrentUserAccessLevel')) {
    function canCurrentUserAccessLevel(string $accessToUserType): bool
    {
        $currentUserType = user()->type;

        if (in_array($currentUserType, exceptUserType())) {
            return true;
        }

        $userLevel = [
            UserType::MEMBER,
            UserType::AGENT,
            UserType::MASTER_AGENT,
            UserType::SENIOR,
            UserType::SUPER_SENIOR,
        ];

        $current = array_search($currentUserType, $userLevel);
        $accessTo = array_search($accessToUserType, $userLevel);

        return $current > $accessTo;
    }
}

if (! function_exists('castingUser')) {
    function castingUser($user): object
    {
        $castUser = [
            'id' => $user->id,
            'type' => $user->type->value,
        ];

        if ($user->type->is(UserType::SUB_ACCOUNT)) {
            $castUser = $user->getParent();
        }

        return (object) $castUser;
    }
}

if (! function_exists('amountDisplay')) {
    function amountDisplay($amount, $payoutRate, Currency $currency): string
    {
        return priceFormat(fromKHRtoCurrency($amount, $currency), $currency)." x {$payoutRate} = ".priceFormat(fromKHRtoCurrency($amount * $payoutRate, $currency), $currency);
    }
}

if (! function_exists('allowIf')) {
    function allowIf(string $permission): bool
    {
        return user()->isRoot() || user()->hasPermission($permission);
    }
}

if (! function_exists('takeValueIfKeyExist')) {
    function takeValueIfKeyExist($value, string $key)
    {
        return $value && Arr::exists($value, $key) ? $value[$key] : null;
    }
}

if (! function_exists('userDomainInvalidWithCurrentDomain')) {
    function userDomainInvalidWithCurrentDomain(Request $request, UserModel $user): bool
    {
        return getDomainByUser($user) !== $request->server('HTTP_HOST');
    }
}

if (! function_exists('getDomainByUser')) {
    function getDomainByUser(UserModel $user): string
    {
        $domain = getParentDomain($user)->domain()
            ->select('domain')
            ->first();

        return $domain?->getAttribute('domain') ?? config('domain.default_domain_name');
    }
}

if (! function_exists('getParentDomain')) {
    function getParentDomain(UserModel $user): UserModel
    {
        if ($user->type->isNot(UserType::SUPER_SENIOR) && $user->type->isNot(UserType::SUB_ACCOUNT)) {
            $user = $user->underSuperSenior()->first();
        }

        return $user;
    }
}

if (! function_exists('getThemeColor')) {
    function getThemeColor(Request $request): string
    {
        $domain = Domain::findByDomain($request->server('HTTP_HOST'));

        if (! $domain->count()) {
            return '';
        }

        return takeValueIfKeyExist($domain->meta, 'theme_color') ?? '';
    }
}

if (! function_exists('appGetSetting')) {
    function appGetSetting($settingKey, $default = null)
    {
        $setting = Cache::get("app:setting:{$settingKey}");

        if (! is_null($setting)) {
            return $setting;
        }

        return \OptimistDigital\NovaSettings\NovaSettings::getSettingsModel()::getValueForKey($settingKey) ?? $default;
    }
}
if (! function_exists('appSetSetting')) {
    function appSetSetting(string $settingKey, mixed $value): void
    {
        Cache::put("app:setting:{$settingKey}", $value);
    }
}
