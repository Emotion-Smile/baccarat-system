<?php

namespace App\Kravanh\Domain\Integration\Jobs;

use App\Kravanh\Domain\Integration\Actions\T88\CreateGameConditionAction;
use App\Kravanh\Domain\Integration\Actions\T88\CreateUserAction;
use App\Kravanh\Domain\Integration\Actions\T88\DestroyAuthenticationTokenAction;
use App\Kravanh\Domain\Integration\Actions\T88\GetAuthenticationTokenAction;
use App\Kravanh\Domain\Integration\DataTransferObject\T88\CreateGameConditionData;
use App\Kravanh\Domain\Integration\Supports\Enums\T88Game;
use App\Kravanh\Domain\User\Events\RefreshPage;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AutoCreateT88GameConditionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(
        protected User $user
    )
    {}

    public function handle()
    {
        $token = null;
        
        $upLineUser = $this->user->underSuperSenior;

        $condition = $upLineUser->t88GameCondition(T88Game::LOTTO_12)->condition;
       
        foreach ($this->getUserTypes() as $userType) {
            try {
                $user = $this->getCreateGameConditionUser($userType);
                
                $createGameConditionData = $this->getCreateGameConditionData(
                    user: $user, 
                    condition: $condition
                );
                
                $token = (new GetAuthenticationTokenAction)([
                    'username' => $upLineUser->name . '.f88',
                    'password' => 'KvF88!@#123',
                ]);

                (new CreateUserAction)(
                    user: $user,
                    token: $token,
                    createGameConditionData: $createGameConditionData 
                );

                (new CreateGameConditionAction)($createGameConditionData);

                RefreshPage::dispatchIf(
                    $user->isMember(), 
                    $user->environment_id, 
                    $user->id
                );
            } catch (Exception $exception) {
                if($token) {
                    (new DestroyAuthenticationTokenAction)($token);
                }
                
                if(! Str::contains($exception->getMessage(), 'The name has already been taken.')) {
                    // Log::error($exception);
                    throw $exception;
                }
            } finally {
                $upLineUser = $user;
            }
        }
    }

    protected function getUserTypes(): array
    {
        return [
            UserType::SENIOR,
            UserType::MASTER_AGENT,
            UserType::AGENT,
            UserType::MEMBER
        ];
    }

    protected function getCreateGameConditionUser(string $userType): User
    {
        if($this->user->type->isNot($userType)) {
            return User::find($this->user->{$userType});
        }

        return $this->user;
    } 

    protected function getCreateGameConditionData(
        User $user,
        array $condition
    ): CreateGameConditionData
    {
        return CreateGameConditionData::new(
            userId: $user->id,
            condition: $condition,
            gameType: T88Game::LOTTO_12,
        )
            ->noShare()
            ->noCommission();
    }
}
