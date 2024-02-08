<?php

use App\Kravanh\Domain\User\Models\Member;

test("get_class() is equal to ClassName:class", function () {
    $member = Member::factory()->create();
    expect(get_class($member))->toBe(Member::class);
});
