<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use TgWebValid\TgWebValid;

class TelegramAuthController extends Controller
{
    public function callback(Request $request, TgWebValid $tgWebValid)
    {
        $validation = $tgWebValid->loginWidget($request->all())->validate();

        if (!$validation->isValid()) {
            return response()->json(['error' => 'Invalid hash'], 403);
        }
        $userData = $validation->getUser();
        $telegramId = $userData->id;
        $user = User::firstOrCreate(
            ['telegram_id' => $telegramId],
            [
                'name' => $userData->first_name . ' ' . ($userData->last_name ?? ''),
                'username' => $userData->username ?? $telegramId,
                'password' => bcrypt(\Str::random(32)),
                'avatar' => $userData->photo_url ?? null,
            ]
        );
        Auth::login($user, true);
        return response()->json(['success' => true, 'redirect' => '/dashboard']);
    }
}
