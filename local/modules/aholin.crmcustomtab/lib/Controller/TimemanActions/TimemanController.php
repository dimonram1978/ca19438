<?php
namespace Aholin\Crmcustomtab\Controller\TimemanActions;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Diag\Debug;

class TimemanController extends Controller
{
    public function configureActions()
    {
        return [
            'startDate' => [
                'prefilters' => [],
                'postfilters' => [],
            ],
        ];
    }

    public function startDateAction(): array
    {
        $userId = CurrentUser::get()->getId();
        
        
        if(\CModule::IncludeModule('timeman'))
        {
            
          $tmUser = new \CTimeManUser($userId);
          $userSettings = $tmUser->GetSettings(); //получаем настройки пользователя
           
          if($userSettings["UF_TIMEMAN"]) { //ведётся ли учет времени
            $now = date("d.m.Y H:i:s");
            //Debug::writeToFile($now, 'now', "/local/app/Events/log_Iblock3.txt"); 
            $tmstp = MakeTimeStamp($now, FORMAT_DATETIME);
            
            $result = $tmUser->OpenDay($tmstp, "Открыть день");
            //Debug::writeToFile($result, 'result1', "/local/app/Events/log_Iblock3.txt");
            $state =  $tmUser->State();
            //Debug::writeToFile($state, 'state', "/local/app/Events/log_Iblock3.txt");
            //Если день все равно не открывается то делаем с функцией ReopenDay скорее всего он был сегодня закрыт ИЛИ НА ПАУЗЕ
            if ($state != 'OPENED'){
                 $result = $tmUser->ReOpenDay($tmstp, "Открыть ЗАНОВО день");
            }
           }
        }
        // @TODO реализовать запуск рабочего дня, бработку ошибок, возврат ответа в JS
        return [$result];
    }
}
