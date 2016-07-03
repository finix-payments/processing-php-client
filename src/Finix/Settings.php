<?php

namespace Finix;

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
    const VERSION = '1.0.0';

    public static $url_root = 'https://api-staging.finix.io/',
                  $username = null,
                  $password = null,
                  $agent = 'finix-php',
                  $version = Settings::VERSION,
                  $accept = 'application/vnd.json+api';

    /**
     * Configure all settings.
     *
     * @param string $url_root The root (schema://hostname[:port]) to use when constructing api URLs.
     * @param string $username The username to identify the client
     * @param string $password The api key secret to use for authenticating when talking to the api. If null then api usage is limited to unauthenticated endpoints.
     */
    public static function configure($url_root, $username, $password)
    {
        self::$url_root= $url_root;
        self::$username= $username;
        self::$password = $password;
    }
}