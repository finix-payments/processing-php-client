<?php

namespace Finix;
use Finix\Resources;
use Finix\Http\Auth\BasicAuthentication;

/**
 * Bootstrapper for Finix does autoloading and resource initialization.
 */
class Bootstrap
{
    const DIR_SEPARATOR = DIRECTORY_SEPARATOR;
    const NAMESPACE_SEPARATOR = '\\';

    public static $initialized = false;


    public static function init()
    {
        spl_autoload_register(array('\Finix\Bootstrap', 'autoload'));
        self::initializeResources();
    }

    public static function autoload($classname)
    {
        self::_autoload(dirname(dirname(__FILE__)), $classname);
    }

    public static function pharInit()
    {
        spl_autoload_register(array('\Finix\Bootstrap', 'pharAutoload'));
        self::initializeResources();
    }

    public static function pharAutoload($classname)
    {
        self::_autoload('phar://finix.phar', $classname);
    }

    private static function _autoload($base, $classname)
    {
        $parts = explode(self::NAMESPACE_SEPARATOR, $classname);
        $path = $base . self::DIR_SEPARATOR. implode(self::DIR_SEPARATOR, $parts) . '.php';
        if (file_exists($path)) {
            require_once($path);
        }
    }

    /**
     * Initializes resources (i.e. registers them with Resource::_registry). Note
     * that if you add a Resource then you must initialize it here.
     *
     * @internal
     */
    private static function initializeResources()
    {
        if (self::$initialized) {
            return;
        }

        Resource::init();

        Resources\User::init();
        Resources\Application::init();
        Resources\Identity::init();
        Resources\Processor::init();
        Resources\Merchant::init();
        Resources\PaymentInstrument::init();
        Resources\PaymentCard::init();
        Resources\BankAccount::init();
        Resources\Authorization::init();
        Resources\Transfer::init();
        Resources\Reversal::init();
        Resources\Dispute::init();
        Resources\Webhook::init();
        Resources\Settlement::init();
        Resources\Verification::init();
        Resources\Evidence::init();
        Resources\Token::init();
        Resources\InstrumentUpdate::init();

        self::$initialized = true;
    }

    public static function createClient()
    {
        if (Settings::$username == null || Settings::$password == null) {
            $client = new Hal\Client(Settings::$url_root, '/');
        }
        else {
            $client = new Hal\Client(
                Settings::$url_root,
                '/',
                null,
                new BasicAuthentication(Settings::$username, Settings::$password));
        }
        return $client;
    }
}
