<?php

namespace DCS\Security\CoreBundle;

class DCSSecurityCoreEvents
{
    const AUTHENTICATED_USER        = 'dcs_security.core.event.authentication.authenticated_user';
    const PROVIDER_BEFORE_LOAD_USER = 'dcs_security.core.event.provider.before_load_user';
    const PROVIDER_AFTER_LOAD_USER  = 'dcs_security.core.event.provider.after_load_user';
}