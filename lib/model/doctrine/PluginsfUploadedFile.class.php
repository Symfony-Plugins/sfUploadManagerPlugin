<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginsfUploadedFile extends BasesfUploadedFile
{
  public function getValidatedFile($validatedFileClassName)
  {
    return new $validatedFileClassName($this->getOriginalName(), $this->getMimeType(), $this->getFullPath(), $this->getSize());
  }
  
  public function fromValidatedFile(sfvalidatedFile $validatedFile, $savePath)
  {
    $this->setSize($validatedFile->getSize());
    $this->setMimeType($validatedFile->getType());
    $this->setOriginalName($validatedFile->getOriginalName());
    if (!$validatedFile->isSaved())
    {
      try
      {
        $validatedFile->save($savePath.'/'.$validatedFile->generateFilename());
      }
      catch (Exception $e)
      {
        throw $e;
      }
    }
    $this->setTempPath(dirname($validatedFile->getSavedName()));
    $this->setTempName(basename($validatedFile->getSavedName()));
  }
  
  public function getTempFile()
  {
    return $this->getTempPath().$this->getTempName();
  }
}