<?php

namespace Gregoriohc\Beet\Routing;

use Illuminate\Routing\Controller as Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

abstract class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use Resourceful, ResetsPasswords;

    /**
     * Create a new password controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
