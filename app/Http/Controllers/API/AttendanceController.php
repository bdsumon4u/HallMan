<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\AttendanceRequest;
use App\Models\Log;

class AttendanceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AttendanceRequest $request)
    {
        Log::create($request->validated());

        return response()->json([
            'message' => 'CREATED',
        ], 201);
    }
}
