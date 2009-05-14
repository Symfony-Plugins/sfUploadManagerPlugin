<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfUploadManagerAddToWhiteListTask
 * Add an IP to whitelist.
 *
 * @package    symfony
 * @subpackage sfUploadManagerPlugin
 * @author     François Béliveau  <francois.beliveau@my-labz.com>
 */
class sfUploadManagerAddToWhiteListTask extends sfBaseTask
{
  /**
   * Configures the task
   * 
   * @access protected
   */
  protected function configure()
  {
    $this->namespace            = 'upload-manager';
    $this->name                 = 'add-to-whitelist';
    $this->briefDescription     = '"sfUploadManagerPlugin" add an IP to whitelist';
    $this->detailedDescription  = <<<EOF
"sfUploadManagerPlugin" add an IP to whitelist

Examples:
  [./symfony upload-manager:add-to-whitelist frontend xxx.xxx.x.xx --env=cli]
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
    $uploadManagerSecurity->setBlacklisted(false);
    $uploadManagerSecurity->save();
    
    $this->logSection('result', 'added to whitelist');
  }
}