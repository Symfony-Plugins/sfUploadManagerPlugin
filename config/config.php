<?php
if (in_array('sfUploadManager', sfConfig::get('sf_enabled_modules', array())))
{
  $this->dispatcher->connect('routing.load_configuration', array('sfUploadManagerRouting', 'configure'));
}