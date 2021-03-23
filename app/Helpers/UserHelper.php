<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class UserHelper
{
    public function getAuthUserTokens()
    {
        return collect(Auth::user()->tokens()->where('name', Auth::user()->name . '-token')->firstOrFail()->abilities);
    }

    public function isAuthUserAdmin()
    {
        // Move this into a gate
        return self::getAuthUserTokens()->contains("admin");
    }

}
