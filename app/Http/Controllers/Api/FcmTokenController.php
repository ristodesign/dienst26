<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    /**
     * store fcm token after login
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate(['token' => 'required|string', 'platform' => 'nullable|string']);

        FcmToken::updateOrCreate(
            ['token' => $request->token],
            ['user_id' => $request->user_id, 'platform' => $request->platform]
        );

        return response()->json(['status' => 'success', 'message' => 'Token saved']);
    }
}
