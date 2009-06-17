<?php
class sfUploadManagerSessionStorage extends sfSessionStorage
{
  public function initialize($options = null)
  {
    $request = sfContext::getInstance()->getRequest();
    $module  = $request->getParameter('module');
    $action  = $request->getParameter('action');
   
    if ('sfUploadManager' == $module && 'create' == $action)
    {
      $sessionName = $request->getParameter('session_name');
      $sessionId   = $request->getParameter('session_id');
   
      if(!empty($sessionName) && !empty($sessionId))
      {
        //Shitty work-around for swfuploader or equivalent
        session_name($sessionName);
        session_id($sessionId);
      } 
    }

    parent::initialize($options);
  }
}