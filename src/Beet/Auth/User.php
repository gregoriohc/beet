<?php

namespace Gregoriohc\Beet\Auth;

use Zizaco\Entrust\Traits\EntrustUserTrait;

abstract class User extends AuthorizableUser
{
    use EntrustUserTrait;
}
