<?php

namespace App\Kravanh\Domain\Integration\Http\Controllers\Member;

use App\Kravanh\Domain\Integration\Supports\Enums\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class GameEmbedController
{
    public function __invoke(Request $request, string $game): Response
    {
        $token = $this->generateToken($request);

        $link = $this->generateEmbedLink(
            game: $game,
            token: $token
        );

        return Inertia::render('Member/GameEmbed', [
            'link' => $link,
            'game' => Str::of($request->game)->headline()
        ]);
    }

    public function generateEmbedLink(string $game, string $token): string
    {
        return match ($game) {
            Game::YUKI => $this->generateYukiEmbedLink($token),
            Game::SPORT => $this->generateSportEmbedLink($token) 
        };
    }

    protected function generateToken(Request $request): string
    {
        $user = $request->user();

        $user->tokens()->delete();

        return $user->createToken($user->name)->plainTextToken;
    }

    protected function generateYukiEmbedLink(string $token): string
    {
        $baseUrl = Str::of(config('t88.base_url'))->trim('/');

        return "{$baseUrl}/magic-login/{$token}";
    }

    protected function generateSportEmbedLink(string $token): string
    {
        $baseUrl = Str::of(config('af88.base_url'))->trim('/');

        return "{$baseUrl}/integration/login/{$token}";
    }
}