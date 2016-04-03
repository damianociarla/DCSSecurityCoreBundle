<?php

namespace DCS\Security\CoreBundle\Tests;

use DCS\User\CoreBundle\Model\User;

class TestUser extends User
{
    protected function initRoles()
    {
        $this->roles = ['ROLE_DEFAULT'];
    }
}