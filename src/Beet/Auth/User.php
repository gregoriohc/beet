<?php

namespace Gregoriohc\Beet\Auth;

use Zizaco\Entrust\Traits\EntrustUserTrait;

abstract class User extends AuthenticatableUser
{
    use EntrustUserTrait;
}
