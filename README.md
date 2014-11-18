Atlassian Crowd authentication for Symfony2
===========================================

This bundle allows you to add Atlassian Crowd authentication to your Symfony2 application and is inspired on the
<a href="https://github.com/seiffert/crowd-auth-bundle/">seiffert Crowd Auth Bundle</a>. In the near future this bundle
will be expended with more advanced features.

# Installation

Add this bundle to your composer.json

```
composer.phar require "bluetea/crowd-authentication-bundle" dev-master
```

Enable it in the AppKernel.php

```
new Bluetea\CrowdAuthenticationBundle\BlueteaCrowdAuthenticationBundle(),
```

Add the configuration to your config.yml

```
bluetea_crowd_authentication:
    base_url: https://atlassian.yourdomain.com/crowd/rest/usermanagement/latest
    application: application_key
    password: password
```

# Configuration

Now edit the `security.yml`.

```
security:
    encoders:
        Bluetea\CrowdAuthenticationBundle\Crowd\User: plaintext

    [...]

    firewalls:
        dev:
            pattern:  ^/(_(profiler|wdt)|css|images|js)/
            security: false

        demo_login:
            pattern:  ^/demo/secured/login$
            security: false

        demo_secured_area:
            pattern:    ^/demo/secured/
            crowd_login:
                check_path: _demo_security_check
                login_path: _demo_login
            logout:
                path:   _demo_logout
                target: _demo
```

The `crowd_login` is important! Don't forget it or you won't get authenticated.
