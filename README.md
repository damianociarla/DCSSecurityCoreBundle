[![Build Status](https://travis-ci.org/damianociarla/DCSSecurityCoreBundle.svg?branch=master)](https://travis-ci.org/damianociarla/DCSSecurityCoreBundle) [![Coverage Status](https://coveralls.io/repos/github/damianociarla/DCSSecurityCoreBundle/badge.svg?branch=master)](https://coveralls.io/github/damianociarla/DCSSecurityCoreBundle?branch=master)

# DCSSecurityCoreBundle

The DCSSecurityCoreBundle offers the integration of **DCSUser** with the *Symfony security system*.

This bundle provides a custom **UserProvider** and a custom **AuthenticationProvider**.

### The UserProvider

The **UserProvider** using the `DCS\User\CoreBundle\Repository\UserRepositoryInterface` deals with recovering the user. During the **loadUserByUsername** action will be emitted two events *(before and after the user search)* that allow you to insert additional logic.

### The AuthenticationProvider

This provider extends the `Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider` and adds an event after executing the **authenticate** method. The AuthenticationProvider is not used directly by this bundle, but is provided as a service in the implementation of a final SecurityFactoryInterface.

See: [DCSSecurityAuthFormBundle](https://github.com/damianociarla/DCSSecurityAuthFormBundle) authentication system that uses the login form.

### Events

The complete list of events is within the class `DCS\Security\CoreBundle\DCSSecurityCoreEvents`.

## Installation

### Prerequisites

This bundle requires [DCSUserCoreBundle](https://github.com/damianociarla/DCSUserCoreBundle).

### Require the bundle

Run the following command:

	$ composer require dcs/security-core-bundle "~1.0@dev"

Composer will install the bundle to your project's `vendor/dcs/security-core-bundle` directory.

### Enable the bundle

Enable the bundle in the kernel:

	<?php
	// app/AppKernel.php

	public function registerBundles()
	{
		$bundles = array(
			// ...
			new DCS\Security\CoreBundle\DCSSecurityCoreBundle(),
			// ...
		);
	}

### Configure

Now that you have properly enabled this bundle, the next step is to configure it to work with the specific needs of your application.

Add the following configuration to your `config.yml`.

    dcs_security_core:
        provider_key: #SECURITY_PROVIDER_NAME
        
And sets the provider to your `security.yml`.

    security:
        providers:
            dcs_user:
                id: dcs_security.core.provider.user
        
        # !!Now use the provider in your authentication provider within the firewall!!

# Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/damianociarla/DCSSecurityCoreBundle/issues).
