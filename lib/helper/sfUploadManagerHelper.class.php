<?php

class sfUploadManagerHelper
{
  static protected
    $configManager = null;

  /**
   * Return config manager
   *
   * @param  mixte
   *
   * @return sfUoWidgetConfigManager
   */
  static public function getConfigManager($context=null)
  {
    if (is_null(self::$configManager))
    {
      self::$configManager = new sfUploadManagerConfigManager(is_null($context) ? sfContext::getInstance() : $context);
    }

    return self::$configManager;
  }
  
  /**
   * Return an sfUploadManagerSecurity object from database or a new one.
   *
   * @return sfUploadManagerSecurity
   */
  public static function findUploadManagerSecurityOrCreate($ip = null)
  {
    if (is_null($ip))
    {
      $ip = (isset($_SERVER) && array_key_exists('REMOTE_ADDR', $_SERVER)) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    $upploadManagerSecurity = Doctrine::getTable('sfUploadManagerSecurity')->findOneByIp($ip);
    if (!$upploadManagerSecurity)
    {
      $upploadManagerSecurity = new sfUploadManagerSecurity();
      $upploadManagerSecurity->setIp($ip);
    }

    return $upploadManagerSecurity;
  }

  /**
   * Return temporary dir
   *
   * @return string
   */
  static public function getTempDir()
  {
    $defaultTempDir = sfConfig::get('sf_data_dir').'/upload_manager';

    return sfConfig::get('app_sfUploadManagerPlugin_temp_dir', $defaultTempDir);
  }
}