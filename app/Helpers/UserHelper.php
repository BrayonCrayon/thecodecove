<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class UserHelper
{
    public function getAuthUserTokens()
    {
        return collect(Auth::user()->tokens()->where('name', Auth::user()->name . '-token')->first()->abilities);
    }

    public function isAuthUserAdmin()
    {
        return self::getAuthUserTokens()->contains("admin");
    }

    public function isAuthUserGuest()
    {
        return self::getAuthUserTokens()->contains("guest");
    }

}
