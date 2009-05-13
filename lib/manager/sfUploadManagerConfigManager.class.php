<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfUploadManagerConfigManager
 * Config manager for file validator configuration.
 *
 * @package    symfony
 * @subpackage sfSwfUploadServerPlugin
 * @author     François Béliveau  <francois.beliveau@my-labz.com>
 */
class sfUploadManagerConfigManager implements ArrayAccess
{
  protected
    $context       = null,
    $configuration = array();

  /**
   * Constructor
   */
  public function __construct(sfContext $context)
  {
    $this->context = $context;
    $this->configuration = include($this->context->getConfiguration()->getConfigCache()->checkConfig('config/sfUploadManagerPlugin.yml'));
  }

  /**
   * Returns context
   *
   * @return sfContext
   */
  public function getContext()
  {
    return $this->context;
  }

  /**
   * Returns all configuration
   *
   * @return array
   */
  public function getAllConfiguration()
  {
    return $this->configuration;
  }

  /**
   * Returns configuration from a specific key
   *
   * @throws InvalidArgumentException if specified key does not have configuration
   *
   * @param  string $key
   * @return mixed
   */
  public function getConfiguration($key)
  {
    if (!isset($this->configuration[$key]))
    {
      throw new InvalidArgumentException('Configuration for sfUploadManager key «'.$key.'» is not available.');
    }
    return $this->configuration[$key];
  }

  /**
   * Returns field name
   *
   * @return string
   */
  public function getFieldName()
  {
    return $this->configuration['field_name'];
  }

  /**
   * Returns security mode
   *
   * @return string
   */
  public function getSecurityMode()
  {
    return $this->configuration['security']['mode'];
  }

  /**
   * Returns true if security mode sets in whitelist, false otherwise
   *
   * @return boolean
   */
  public function isInWhiteListSecurityMode()
  {
    return $this->getSecurityMode() == 'whitelist';
  }

  /**
   * Returns validator configuration
   *
   * @throws InvalidArgumentException if specified key does not have configuration
   *
   * @param  string $key
   * @return array
   */
  public function getValidatorConfiguration($key)
  {
    if (!isset($this->configuration['validator'][$key]))
    {
      throw new InvalidArgumentException('Configuration for sfUploadManager validator «'.$key.'» is not available.');
    }
    return $this->configuration['validator'][$key];
  }

  /**
     * ArrayAccess: isset
     */
  public function offsetExists($offset)
  {
    return isset($this->configuration[$offset]);
  }

  /**
     * ArrayAccess: getter
     */
  public function offsetGet($offset)
  {
    return $this->configuration[$offset];
  }

  /**
     * ArrayAccess: setter
     */
  public function offsetSet($offset, $value)
  {
    throw new LogicException('Cannot use array access of upload manager in write mode.');
  }

  /**
     * ArrayAccess: unset
     */
  public function offsetUnset($offset)
  {
    throw new LogicException('Cannot use array access of upload manager in write mode.');
  }
}