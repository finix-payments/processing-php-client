<?php

namespace Finix;

use Finix\Resource;

/**
 * Configurable settings.
 *
 *  You can either set these settings individually:
 *
 *  <code>
 *  \Finix\Settings::$username = 'username';
 *  \Finix\Settings::password = 'username';
 *  </code>
 *
 *  or all at once:
 *
 *  <code>
 *  \Finix\Settings::configure(
 *      'https://api.finix.io',
 *      'username',
 *      'password'
 *      );
 *  </code>
 */
class Settings
{
    public static $url_root = null,
                  $username = null,
                  $password = null,
                  $agent = 'finix-php',
                  $accept = 'application/vnd.json+api';

    public static function configure(array $args)
    {
        if (array_key_exists("root_url", $args))
        {
            self::$url_root = $args["root_url"];
        }

        if (array_key_exists("username", $args))
        {
            self::$username = $args["username"];
        }

        if (array_key_exists("password", $args))
        {
            self::$password = $args["password"];
        }
    }
}
