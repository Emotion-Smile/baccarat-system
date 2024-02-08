<?php

namespace App\Kravanh\Application;

use App\Kravanh\Support\External\Telegram\Telegram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class FeedbackCustomerController
{
    public function create(): Response
    {

        return Inertia::render('Member/Feedback');
    }

    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'telegram' => 'required',
            'issue' => 'required',
        ]);

        //F88 info notify
        $telegram = new Telegram(
            apiKey: '5421803742:AAH1A-vI_9BE2IVz_n9Fo3FaptRuSZhcyA0',
            chatId: -966248587 // F88-User Login Notify
        );

        $text = ' Account: '.$request->user()->name;
        $text .= "\nTelegram: ".$request->get('telegram');
        $text .= "\nIssue: ".$request->get('issue');

        $telegram
//            ->allowSendInLocal()
            ->text($text);

        return redirect()->back();
    }
}
