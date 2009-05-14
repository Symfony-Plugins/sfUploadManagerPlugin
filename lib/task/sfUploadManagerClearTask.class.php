<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfUploadManagerClearTask
 * Remove an IP from blacklist.
 *
 * @package    symfony
 * @subpackage sfUploadManagerPlugin
 * @author     François Béliveau  <francois.beliveau@my-labz.com>
 */
class sfUploadManagerClearTask extends sfBaseTask
{
  /**
   * Configures the task
   * 
   * @access protected
   */
  protected function configure()
  {
    $this->namespace            = 'upload-manager';
    $this->name                 = 'clear';
    $this->briefDescription     = '"sfUploadManagerPlugin" clear the uploaded files: deletes all obsolete records';
    $this->detailedDescription  = <<<EOF
"sfUploadManagerPlugin" clear the uploaded files: deletes all obsolete records

Examples:
  [./symfony upload-manager:clear frontend --env=cli --seconds=3600]
EOF;

    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandOption::PARAMETER_REQUIRED, "The application's name"),
    ));
    
    $this->addOptions(array(
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_OPTIONAL, 'The environment', 'cli'),
      new sfCommandOption('seconds', null, sfCommandOption::PARAMETER_OPTIONAL, 'Seconds after which a registration is considered as obsolete', '3600'),
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
    
    $uploadedFiles = Doctrine::getTable('sfUploadedFile')->findObsoletes($options['seconds']);
    $deleteCount   = 0;
    foreach ($uploadedFiles as $uploadedFile)
    {
      $uploadedFiles->delete();
      $deleteCount++;
    }

    $this->logSection('result', $deleteCount.' uploaded files deleted');
  }
}