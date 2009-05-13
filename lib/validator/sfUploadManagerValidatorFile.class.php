<?php
/**
 * sfUploadManagerValidatorFile validates an uploaded file from a standard HTML form or from an integer (should correspond to sfUploadedFile primary key).
 *
 * @package    symfony
 * @subpackage sfUploadManagerPlugin
 * @author     François Béliveau <francois.beliveau@my-labz.com>
 */
class sfUploadManagerValidatorFile extends sfValidatorFile
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * max_size:             The maximum file size
   *  * mime_types:           Allowed mime types array or category (available categories: web_images)
   *  * mime_type_guessers:   An array of mime type guesser PHP callables (must return the mime type or null)
   *  * mime_categories:      An array of mime type categories (web_images is defined by default)
   *  * validated_file_class: Name of the class that manages the cleaned uploaded file (optional)
   *
   * There are 3 built-in mime type guessers:
   *
   *  * guessFromFileinfo:        Uses the finfo_open() function (from the Fileinfo PECL extension)
   *  * guessFromMimeContentType: Uses the mime_content_type() function (deprecated)
   *  * guessFromFileBinary:      Uses the file binary (only works on *nix system)
   *
   * Available error codes:
   *
   *  * max_size
   *  * mime_types
   *  * partial
   *  * no_tmp_dir
   *  * cant_write
   *  * extension
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addMessage('no_tmp_file', 'Missing the temporary file.');
  }

  /**
   * This validator always returns a sfValidatedFile object.
   *
   * The input value could be an array with the following keys:
   *
   *  * tmp_name: The absolute temporary path to the file
   *  * name:     The original file name (optional)
   *  * type:     The file content type (optional)
   *  * error:    The error code (optional)
   *  * size:     The file size in bytes (optional)
   *
   * Or could be a simple string
   *
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    if (!$this->isClassicUploadedFile($value))
    {
      $value        = trim((string)$value);
      $uploadedFile =  Doctrine::getTable('sfUploadedFile')->findOneById($value);

      if (!$uploadedFile)
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }

      if (!$uploadedFile->checkFileExists())
      {
        throw new sfValidatorError($this, 'no_tmp_file', array('value' => $value));
      }

      return $uploadedFile->getValidatedFile($this->getOption('validated_file_class'));
    }
    
    return parent::doClean($value);
  }

  /**
   * @see sfValidatorBase
   */
  protected function isEmpty($value)
  {
    return $this->isClassicUploadedFile($value) ? parent::isEmpty($value) : ('' == trim((string)$value));
  }

  /**
   * Check if value come from a classic html form or not
   *
   * @param  @mixed
   *
   * @return @boolean
   */
  protected function isClassicUploadedFile($value)
  {
    return is_array($value) && isset($value['tmp_name']);
  }
}