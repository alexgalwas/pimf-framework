<?php
/**
 * Model
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
 * @copyright Copyright (c)  Gjero Krsteski (http://krsteski.de)
 * @license http://krsteski.de/new-bsd-license New BSD License
 */

namespace Pimf\Model;
use Pimf\Contracts\Arrayable;

/**
 * Returns only protected and public properties of the given model-object. You have to extend it.
 *
 * Normally you will use ArrayObject and than method getArrayCopy() to turn Classes to Array, but
 * with ToArray you have the opportunity to easily intercept the setting of the values at the array.
 *
 * Sure if you need it - otherwise please prefers using ArrayObject - is much faster!
 *
 * @package Model
 * @author Gjero Krsteski <gjero@krsteski.de>
 */
abstract class ToArray implements Arrayable
{
  /**
   * Returns only protected and public properties of the given model-object.
   * For another properties output format, please override this method.
   *
   * @return array A list of properties.
   */
  public function toArray()
  {
    return $this->map(get_class_vars(get_class($this)));
  }

  /**
   * Maps the properties to array with actual values.
   * For another properties-mapping, please override this method.
   *
   * @param array $properties
   * @return array
   */
  protected function map(array $properties)
  {
    $map = array();

    foreach ($properties as $name => $default) {
      $map[$name] = (true === empty($this->$name)) ? $default : $this->$name;
    }

    return $map;
  }
}