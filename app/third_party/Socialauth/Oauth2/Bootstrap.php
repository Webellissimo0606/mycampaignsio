<?php
/**
 * CIMembership
 * 
 * @package		Libraries
 * @author		1stcoder Team
 * @copyright   Copyright (c) 2015 1stcoder. (http://www.1stcoder.com)
 * @license		http://opensource.org/licenses/MIT	MIT License
 */

namespace LeagueTests\OAuth2\Client;
error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);
/**
 * Test bootstrap, for setting up autoloading
 *
 * @subpackage UnitTest
 */
class Bootstrap
{
    protected static $serviceManager;
    public static function init()
    {
        static::initAutoloader();
    }

    protected static function initAutoloader()
    {
        $vendorPath = static::findParentPath('vendor');
        $loader = include $vendorPath.'/autoload.php';
    }

    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir.'/'.$path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) {
                return false;
            }
            $previousDir = $dir;
        }
        return $dir.'/'.$path;
    }
}
Bootstrap::init();