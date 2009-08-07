<?php

/**
 * sfUploadManager actions
 */
class PluginsfUploadManagerActions extends sfActions
{
  public function preExecute()
  {
    $this->getContext()->getLogger()->debug('{sfUploadManager} received upload request');
    $this->configManager = sfUploadManagerHelper::getConfigManager($this->getContext());
  }

  public function executeCreate($request)
  {
    $configuration                        = $this->configManager->getValidatorConfiguration($request->getParameter('type', 'all'));
    $configuration['options']['required'] = true;
    $options                              = isset($configuration['options']) ? $configuration['options'] : array();
    $messages                             = isset($configuration['messages']) ? $configuration['messages'] : array();
    
    try
    {
      $this->processUploadManagerSecurity();
      
      $fileValidator = new sfUploadManagerValidatorFile($options, $messages);
      $uploadedFile  = $this->getUploadedFileObject(
        $fileValidator->clean($request->getFiles('file')), sfUploadManagerHelper::getTempDir(),
        sfUploadManagerHelper::getTempDir()
      );
      $uploadedFile->save();
      
      $this->getContext()->getLogger()->debug('{sfUploadManager} file uploaded');
    }
    catch (Exception $e)
    {
      $this->getContext()->getLogger()->err('{sfUploadManager} '.$e->getMessage());
      return $this->getResponseError($e->getMessage());
    }

    echo $uploadedFile->isNew() ? null : $uploadedFile->getId();
    return $this->getResponseContent();
  }

  protected function getResponseContent()
  {
    throw new sfStopException();
    return sfView::NONE;
  }

  protected function getResponseError($message)
  {
    header('HTTP/1.1 500 '.$message);
    return $this->getResponseContent();
  }
  
  protected function processUploadManagerSecurity()
  {
    $this->uploadManagerSecurity = null;
    if ($this->configManager->isSecurityEnable())
    {
      $this->uploadManagerSecurity = sfUploadManagerHelper::findUploadManagerSecurityOrCreate($this->getRequest()->getRemoteAddress());
      
      if ($this->uploadManagerSecurity->getBlackListed())
      {
        // blacklisted ... reject request
        throw new Exception('You are not authorized to do this action');
      }
      
      if (
        $this->configManager->isInWhiteListSecurityMode()
        && $this->uploadManagerSecurity->isNew()
      )
      {
        // not in white list ... reject request
        throw new Exception('You are not authorized to do this action');
      }

      $this->uploadManagerSecurity->incrementUploadCount();
    }
  }
  
  protected function getUploadedFileObject(sfValidatedFile $validatedFile, $tempDir)
  {
    $uploadedFile = new sfUploadedFile();
    $uploadedFile->fromValidatedFile($validatedFile, $tempDir);
    $uploadedFile->setUploadManagerSecurity($this->uploadManagerSecurity);
    
    return $uploadedFile;
  }
}