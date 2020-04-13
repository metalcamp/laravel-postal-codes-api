<?php

namespace Tests\Utils;

use App\User;

trait UsesAuthentication
{
    public function login()
    {
        return $this->actingAs(factory(User::class)->create());
    }
}
