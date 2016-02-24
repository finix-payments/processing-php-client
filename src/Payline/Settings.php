<?php

namespace Payline;

/**
 * Configurable settings.
 *
 *  You can either set these settings individually:
 *
 *  <code>
 *  \Payline\Settings::$username = 'username';
 *  \Payline\Settings::password = 'username';
 *  </code>
 *
 *  or all at once:
 *
 *  <code>
 *  \Payline\Settings::configure(
 *      'https://api.payline.io',
 *      'username',
 *      'password'
 *      );
 *  </code>
 */
class Settings
{
    const VERSION = '1.0.0';

    public static $url_root = 'https://api.payline.io',
                  $username = null,
                  $password = null,
                  $agent = 'payline-php',
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