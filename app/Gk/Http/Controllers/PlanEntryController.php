<?php

namespace App\Gk\Http\Controllers;

use App\Gk\Services\EntrySaver;
use App\Gk\Support\EntryAuthority;
use App\Gk\Support\RoleReader;
use App\Models\PlanEntry;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class PlanEntryController extends Controller
{
    public function store(Request $r, EntrySaver $s)
    {
        return response()->json($s->upsert($r, null));
    }

    public function update(Request $r, PlanEntry $entry, EntrySaver $s)
    {
        $role = RoleReader::forUser($r->user());
        abort_unless(EntryAuthority::canTouch($r->user(), $entry, $role), 403);

        return response()->json($s->upsert($r, $entry));
    }

    public function destroy(Request $r, PlanEntry $entry)
    {
        $u = $r->user();
        abort_if($u === null, 403);
        $role = RoleReader::forUser($u);
        abort_unless(EntryAuthority::canTouch($u, $entry, $role), 403);
        $entry->delete();

        return response()->json(['ok' => true]);
    }
}
