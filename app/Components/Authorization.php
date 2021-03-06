<?php

namespace App\Components;

use App\Services\AuthorizationInterface;
use Illuminate\Support\Facades\Auth;

class Authorization implements AuthorizationInterface
{
    public function Check($id)
    {
        if ($id !== Auth::user()->id) {
            abort(401);
            return;
        }
    }

    public function CheckGuest()
    {
        if (Auth::user()->login_id === "guest001") {
            abort(401);
            return;
        }
    }
}