<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginWithSocialRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class LoginWithSocialController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param LoginWithSocialRequest $request
     * @return JsonResponse
     */
    public function __invoke(LoginWithSocialRequest $request)
    {
        $targetUrl = Socialite::driver($request->get('social'))->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'targetUrl' => $targetUrl,
        ]);
    }

    private function socialUserInfo($socialUser) {
        return [
            'name' => $socialUser->name,
            'email_verified_at' => now(),
            'avatar' => $socialUser->avatar,
        ];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function callback(Request $request)
    {
        $socialUser = Socialite::driver($request->get('social'))->stateless()->user();
        $user = User::firstOrCreate(
            ['email' => $socialUser->email],
            $this->socialUserInfo($socialUser)
        );
        $user->update([
            'provider' => $request->get('social'),
            'provider_id' => $request->get('code')
        ]);
        Auth::login($user);
        Auth::user()->tokens()->delete();
        Auth::user()->createToken(Auth::user()->name . '-token', ['guest']);
        return response()->json($user);
    }


}
