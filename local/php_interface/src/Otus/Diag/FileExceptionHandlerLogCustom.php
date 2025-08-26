<?php

namespace Otus\Diag;
//bitrix\modules\main\lib\diag
use Bitrix\Main\Diag\ExceptionHandlerLog;
use Bitrix\Main\Diag\FileExceptionHandlerLog;
use Bitrix\Main\Diag\ExceptionHandlerFormatter;

class FileExceptionHandlerLogCustom extends FileExceptionHandlerLog
{
   
  public static function print()
  {
     print 'Hello world!';
   }
 
   public function write($exception, $logType)
	{
    
    
    
    //var_dump($exception);
		$text = ExceptionHandlerFormatter::format($exception, false, $this->level);

		$context = [
			'type' => static::logTypeToString($logType),
		];

		$logLevel = static::logTypeToLevel($logType);

		$message = 'OTUS -'."{date} - Host: {host} - {type} - {$text}\n";

		$this->logger->log($logLevel, $message, $context);
	}
 

}