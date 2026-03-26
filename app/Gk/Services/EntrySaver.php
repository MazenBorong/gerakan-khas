<?php

namespace App\Gk\Services;

use App\Gk\Support\AllowedStatus;
use App\Gk\Support\DayMetaFactory;
use App\Gk\Support\EntryAuthority;
use App\Gk\Support\EntryValidator;
use App\Gk\Support\RoleReader;
use App\Models\PlanEntry;
use App\Models\PlanMonth;
use Illuminate\Http\Request;

final class EntrySaver
{
    public function upsert(Request $r, ?PlanEntry $existing): PlanEntry
    {
        $u = $r->user();
        $role = RoleReader::forUser($u);
        $y = (int) $r->input('year');
        $m = (int) $r->input('month');
        $d = (int) $r->input('day');
        $status = AllowedStatus::normalize((string) $r->input('status', ''));
        $target = (int) $r->input('user_id', $existing?->user_id ?? $u->id);
        abort_unless(EntryAuthority::canTouchUserId($u, $target, $role), 403);
        $pmExisting = PlanMonth::query()->where('year', $y)->where('month', $m)->first();
        $cellAlreadyBooked = $existing !== null
            || ($pmExisting && PlanEntry::query()
                ->where('plan_month_id', $pmExisting->id)
                ->where('user_id', $target)
                ->where('day', $d)
                ->exists());
        if (! ($role === 'staff' && $cellAlreadyBooked)) {
            EntryValidator::assertLeadTime($y, $m, $d, $role);
        }
        $pm = PlanMonth::firstOrCreate(['year' => $y, 'month' => $m]);
        $meta = collect(DayMetaFactory::for($y, $m))->firstWhere('day', $d);
        abort_unless($meta, 422);
        EntryValidator::assertEditableDay($meta, $status);
        EntryValidator::assertWfhCap($pm, $d, $target, $role, $status);

        return PlanEntry::updateOrCreate(
            ['plan_month_id' => $pm->id, 'user_id' => $target, 'day' => $d],
            ['status' => $status],
        );
    }
}
