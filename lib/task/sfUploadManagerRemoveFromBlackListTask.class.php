<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfUploadManagerRemoveFromBlackListTask
 * Remove an IP from blacklist.
 *
 * @package    symfony
 * @subpackage sfUploadManagerPlugin
 * @author     François Béliveau  <francois.beliveau@my-labz.com>
 */
class sfUploadManagerRemoveFromBlackListTask extends sfBaseTask
{
  /**
   * Configures the task
   * 
   * @access protected
   */
  protected function configure()
  {
    $this->namespace            = 'upload-manager';
    $this->name                 = 'remove-from-blacklist';
    $this->briefDescription     = '"sfUploadManagerPlugin" remove an IP from blacklist';
    $this->detailedDescription  = <<<EOF
"sfUploadManagerPlugin" remove an IP from blacklist

Examples:
  [./symfony upload-manager:remove-from-blacklist frontend xxx.xxx.x.xx --env=cli]
EOF;

    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandOption::PARAMETER_REQUIRED, "The application's name"),
      new sfCommandArgument('ip', sfCommandOption::PARAMETER_REQUIRED, "The IP"),
    ));
    
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'The environment', 'cli'),
    ));
  }

  /**
   * Executes the task
   * 
   * @param array $arguments The CLI arguments array
   * @param array $options   The CLI options array
   * 
   * @access protected
   */
  protected function execute($arguments = array(), $options = array())
  {
    $configuration = ProjectConfiguration::getApplicationConfiguration($arguments['application'], $options['env'], true);
    sfContext::createInstance($configuration);
    
    $uploadManagerSecurity = Doctrine::getTable('sfUploadManagerSecurity')->findOneByIp($arguments['ip']);
    if ($uploadManagerSecurity)
    {
      $uploadManagerSecurity->setBlacklisted(false);
      $uploadManagerSecurity->save();
    }
    
    $this->logSection('result', 'removed from blacklist');
  }
}