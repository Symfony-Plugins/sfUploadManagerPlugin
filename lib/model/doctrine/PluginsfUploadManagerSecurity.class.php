<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginsfUploadManagerSecurity extends BasesfUploadManagerSecurity
{
  public function incrementUploadCount()
  {
    if ($this->isNew())
    {
      $this->setUploadCount(1);
    }
    else
    {
      $this->setUploadCount($this->getUploadCount() + 1);
    }
  }
}