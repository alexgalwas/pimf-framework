<?php
/**
 * Pimf
 *
 * PHP Version 5
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 * http://krsteski.de/new-bsd-license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to gjero@krsteski.de so we can send you a copy immediately.
 *
 * @copyright Copyright (c) 2010-2011 Gjero Krsteski (http://krsteski.de)
 * @license http://krsteski.de/new-bsd-license New BSD License
 */

/**
 * For use please add the following code to the end of the config.php file:
 *
 * <code>
 *
 * 'cache' => array(
 *
 *    'storage' => 'memcached',
 *       'servers' => array(
 *           array('host' => '127.0.0.1', 'port' => 11211, 'weight' => 100),
 *        ),
 *      ),
 *  ),
 *
 * </code>
 * @package Pimf
 * @author Gjero Krsteski <gjero@krsteski.de>
 */
class Pimf_Memcached
{
	/**
	 * @var Memcached
	 */
	protected static $connection;

  /**
   * <code>
   *    // Get the Memcache connection and get an item from the cache
   *    $name = Pimf_Memcached::connection()->get('name');
   *
   *    // Get the Memcache connection and place an item in the cache
   *    Pimf_Memcached::connection()->set('name', 'Robin');
   * </code>
   *
   * @return Memcached
   */
  public static function connection()
  {
    if (static::$connection === null) {
      $conf = Pimf_Registry::get('conf');
      static::$connection = static::connect(
        $conf['cache']['servers']
      );
    }

    return static::$connection;
  }

  /**
   * Create a new Memcached connection instance.
   * @param array $servers
   * @return Memcached
   * @throws RuntimeException
   */
  protected static function connect(array $servers)
  {
    $memcache = new Memcached();

    foreach ($servers as $server) {
      $memcache->addServer($server['host'], $server['port'], $server['weight']);
    }

    if ($memcache->getVersion() === false) {
      throw new RuntimeException('could not establish memcached connection!');
    }

    return $memcache;
  }

  /**
   * Dynamically pass all other method calls to the Memcache instance.
 	 *
 	 * <code>
 	 *		// Get an item from the Memcache instance
 	 *		$name = Pimf_Memcached::get('name');
 	 *
 	 *		// Store data on the Memcache server
 	 *		Pimf_Memcached::set('name', 'Robin');
 	 * </code>
   *
   * @param $method
   * @param $parameters
   * @return mixed
   */
  public static function __callStatic($method, $parameters)
	{
		return call_user_func_array(array(static::connection(), $method), $parameters);
	}
}