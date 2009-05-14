<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfUploadManagerAddToBlackListTask
 * Add an IP to blacklist.
 *
 * @package    symfony
 * @subpackage sfUploadManagerPlugin
 * @author     François Béliveau  <francois.beliveau@my-labz.com>
 */
class sfUploadManagerAddToBlackListTask extends sfBaseTask
{
  /**
   * Configures the task
   * 
   * @access protected
   */
  protected function configure()
  {
    $this->namespace            = 'upload-manager';
    $this->name                 = 'add-to-blacklist';
    $this->briefDescription     = '"sfUploadManagerPlugin" add an IP to blacklist';
    $this->detailedDescription  = <<<EOF
"sfUploadManagerPlugin" add an IP to blacklist

Examples:
  [./symfony upload-manager:add-to-blacklist frontend xxx.xxx.x.xx --env=cli]
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
    
    $uploadManagerSecurity = sfUploadManagerHelper::findUploadManagerSecurityOrCreate($arguments['ip']);
    $uploadManagerSecurity->setBlacklisted(true);
    $uploadManagerSecurity->save();
    
    $this->logSection('result', 'added to blacklist');
  }
}