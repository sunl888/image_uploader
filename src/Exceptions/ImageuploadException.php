<?php
/**
 * Created by PhpStorm.
 * User: sunlong
 * Date: 18-6-16
 * Time: 下午10:08
 */

namespace Wqer1019\ImageUploader\Exceptions;


use Exception;

class ImageuploadException extends Exception
{
    public function __construct($message, $code = null, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}