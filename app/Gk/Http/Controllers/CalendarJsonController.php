<?php

namespace App\Gk\Http\Controllers;

use App\Gk\Services\CalendarPayloadBuilder;
use Illuminate\Routing\Controller;

final class CalendarJsonController extends Controller
{
    public function show(int $y, int $m, CalendarPayloadBuilder $b)
    {
        return response()->json($b->build($y, $m, auth()->user()));
    }
}
