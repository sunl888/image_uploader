<?php
/**
 * Created by PhpStorm.
 * User: sunlong
 * Date: 18-6-17
 * Time: 上午9:46
 */

namespace Wqer1019\ImageUploader\Exceptions;


use Throwable;

class NotAllowUploadException extends \Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}