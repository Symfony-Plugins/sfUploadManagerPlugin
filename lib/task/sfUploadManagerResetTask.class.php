<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfUploadManagerResetTask
 * Remove all uploaded file.
 *
 * @package    symfony
 * @subpackage sfUploadManagerPlugin
 * @author     François Béliveau  <francois.beliveau@my-labz.com>
 */
class sfUploadManagerResetTask extends sfBaseTask
{
  /**
   * Configures the task
   * 
   * @access protected
   */
  protected function configure()
  {
    $this->namespace            = 'upload-manager';
    $this->name                 = 'reset';
    $this->briefDescription     = '"sfUploadManagerPlugin" reset uploaded files: deletes all records';
    $this->detailedDescription  = <<<EOF
"sfUploadManagerPlugin" reset uploaded files: deletes all records

Examples:
  [./symfony upload-manager:reset frontend --env=cli]
EOF;

    $this->addArguments(array(
      new sfCommandArgument('application', sfCommandOption::PARAMETER_REQUIRED, "The application's name"),
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
    
    if (!chmod(sfUploadManagerHelper::getTempDir(), 0777))
    {
      throw new Exception('Unable to set permission of directory '.sfUploadManagerHelper::getTempDir());
    }
    
    $uploadedFiles = Doctrine::getTable('sfUploadedFile')->findAll();
    $deleteCount   = 0;
    foreach ($uploadedFiles as $uploadedFile)
    {
      $uploadedFiles->delete();
      $deleteCount++;
    }

    $this->logSection('result', $deleteCount.' uploaded files deleted from database');
    
    $deleteCount = 0;
    $finder      = sfFinder::type('file')->follow_link()->name('*');
    foreach ($finder->in(sfUploadManagerHelper::getTempDir()) as $file)
    {
      if (!unlink($file))
      {
        throw new Exception('Unable to delete file "'.basename($file).'"');
      }
      
      $deleteCount++;
    }
    
    $this->logSection('result', $deleteCount.' uploaded files deleted');
  }
}