<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 *
 * @package    sfUploadManagerPlugin
 * @subpackage routing
 * @author     François Béliveau  <francois.beliveau@my-labz.com>
 */
class sfUploadManagerRouting
{
  /**
   * Configure sfUploadManager plugin routing.
   *
   * @param sfEvent An sfEvent instance
   */
  static public function configure(sfEvent $event)
  {
    $r = $event->getSubject();

    $r->prependRoute('sfUploadManager_create', new sfRoute('/upload-manager/create', array('module' => 'sfUploadManager', 'action' => 'create')));
  }
}
