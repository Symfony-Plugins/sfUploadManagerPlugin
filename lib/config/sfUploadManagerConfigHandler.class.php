<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfUploadManagerConfigHandler
 * Config handler for sfUploadManager plugin configuration.
 *
 * @package    symfony
 * @subpackage sfUploadManagerPlugin
 * @author     François Béliveau  <francois.beliveau@my-labz.com>
 */
class sfUploadManagerConfigHandler extends sfYamlConfigHandler
{
  public function execute($configFiles)
  {
    $config = $this->parseYamls($configFiles);

    return sprintf('<?php return %s;', var_export($config, 1));
  }
}