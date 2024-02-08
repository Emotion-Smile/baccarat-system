<?php

namespace App\Kravanh\Application\Environment;

use App\Kravanh\Domain\Environment\Models\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController
{
    public function __invoke(Request $request): JsonResponse
    {
        if (!$request->user()->videoIsBlocked()) {
            return asJson([
                'message' => ' សូមអភ័យទោស ការផ្សាយវីដេអូ កំពុងធ្វើការជួលជុល',
                'streamingLink' => ''
            ]);
        }

//        if ($request->user()->isCreditLessThanMinimumAllowedToViewVideo()) {
//            return asJson([
//                'message' => ' សូមអភ័យទោស credit របស់អ្នកមិនគ្រប់គ្រាន់ក្នុងការបង្ហាញវីដេអូ',
//                'streamingLink' => ''
//            ]);
//        }

        $group = Group::find($request->user()->group_id);

        return asJson([
            'message' => '',
            'defaultStreamingLink' => $group?->{$group?->default_streaming_link},
            'streamingLink1' => $group?->streaming_link,
            'streamingLink2' => $group?->streaming_link_1,
            'showFightNumber' => $group?->show_fight_number
        ]);
    }
}
