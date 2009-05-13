<?php

/**
 * sfUploadManagerPlugin configuration.
 *
 * @package    sfUploadManagerPlugin
 * @subpackage config
 * @author     François Béliveau  <francois.beliveau@my-labz.com>
 */
class sfUploadManagerPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    require dirname(__FILE__).'/config.php';
  }
}
