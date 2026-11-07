<?php

declare(strict_types=1);

namespace App\Modules\DemoToggle\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DemoToggleController
{
    public function show(Request $request): View
    {
        return view('DemoToggle::show', [
            'user' => $request->user(),
        ]);
    }

    public function api(): JsonResponse
    {
        return response()->json([
            'module' => 'demo_toggle',
            'status' => 'enabled',
            'message' => 'Demo toggle module is active for this tenant.',
        ]);
    }
}
