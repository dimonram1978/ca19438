<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Bizproc\Activity\BaseActivity;
use Bitrix\Bizproc\FieldType;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Localization\Loc;
use Bitrix\Bizproc\Activity\PropertiesDialog;
\Bitrix\Main\Loader::includeModule('crm');
use Bitrix\Main\Engine\CurrentUser;

class CBPSearchByInnActivity extends BaseActivity
{
    // protected static $requiredModules = ["crm"];
    
    /**
     * @see parent::_construct()
     * @param $name string Activity name
     */
    const IBLOCK_ID = 25;
    public function __construct($name)
    {
        parent::__construct($name);

        $this->arProperties = [
            'Inn' => '',

            // return
            'Text' => null,
        ];

        $this->SetPropertiesTypes([
            'Text' => ['Type' => FieldType::STRING],
        ]);
    }

    /**
     * Return activity file path
     * @return string
     */
    protected static function getFileName(): string
    {
        return __FILE__;
    }

    /**
     * @return ErrorCollection
     */
    protected function internalExecute(): ErrorCollection 
    {
        $errors = parent::internalExecute();

        $token = "0c825d0906122684951a7a3d60ee8848289d4344";
        $secret = "db2700343995d8f5e1992e0fcbd81ded70267e71";

        // token и secret лучше передавать в виде переменных БП в активити
        // $rootActivity->GetVariable("TOKEN"); 
        // $rootActivity->GetVariable("SECRET"); 
        
        $dadata = new Dadata($token, $secret);
        $dadata->init();

        $fields = array("query" => $this->Inn, "count" => 5);
        $response = $dadata->suggest("party", $fields);
        
        $companyName = 'Компания не найдена!';
        if(!empty($response['suggestions'])){ // если копания найдена
           // по ИНН возвращается массив в котором может бытьнесколько элементов (компаний)
           $companyName = $response['suggestions'][0]['value']; // получаем имя компании из первого элемента  
        }  

        // в рабочем активити необходимо будет создать отдельный метод который будет получать результат ответа сервиса Dadata, 
        // обходить циклом результат и сохранять в массив все полученные организации

        //$this->preparedProperties['Text'] = $companyName;
        //$this->log($this->preparedProperties['Text']);
        $this->log($companyName);

        
        $userId = CurrentUser::get()->getId();
        //id ответственного и уведомителя
        $responsible = $userId;
        

        // создаем массив для создания новой компании
        $arNewCompany = array(
          "TITLE" => $companyName,
          "OPENED" => "Y",
          "COMPANY_TYPE" => "CUSTOMER",
          "ASSIGNED_BY_ID" => $responsible,
          "UF_CRM_INN" => $this->Inn,
       );
       $arNewCompany['FM']['PHONE'] = array(
         "n0" => array(
         "VALUE_TYPE" => "WORK",
         "VALUE" => "123456",
         )
       );
       $arNewCompany['FM']['EMAIL'] = array(
          "n0" => array(
          "VALUE_TYPE" => "WORK",
          "VALUE" => "test@test.ru",
         )
        );
        

        //Создание новой компани

        //Проверка на существование компании с таким ИНН

        //Способ получился очень извращенный и громоздкий. Можно немного оптимизировать но пока сделал так

        //Проверка на существовани компаний с таким ИНН
        //по человечески отбор нае работал
        $entityResult = \CCrmCompany::GetListEx(
	    [],
	    [],//["UF_CRM_INN" => ['7733381102']],
        false,
        false,
        [
         'ID',
         'TITLE',
         'UF_CRM_INN'

        ]
        );
 
        
        $colich = 0;
        while( $entity = $entityResult->fetch() )
        {
          $cur_inn_arr = explode("|", $entity["UF_CRM_INN"]);
	      if($cur_inn_arr[0]==$this->Inn){$colich++;}
 
        }
        //если ничего не нашли то добавляем новую компанию 
        if ($colich==0){
           $company = new CCrmCompany(false);
           $companyID = $company->Add($arNewCompany);
           $this->log('Добавлена компания: '.$companyName); 
           //Как задание от преподавателя надо обновить поля "Наименование Заказчика" и "ИНН Заказчика"
            
          
           $rootActivity = $this->GetRootActivity();
           $element_id = $rootActivity->GetVariable("ELEMENT_ID");
           $this->log('element_id : '.$element_id);
        // сохранение полученных результатов работы активити в переменную бизнес процесса
        //
          CIBlockElement::SetPropertyValuesEx($element_id, CBPSearchByInnActivity::IBLOCK_ID, [
            "COMPANY_ID" => $companyID,
            "COMPANY_TITLE" => $companyName
          ]);
        }
        elseif ($colich>0){
            $this->log('Компания с таким ИНН: '.$this->Inn.' в базе уже есть');

            //Если такая компания уже есть то в поле "Наименование Заказчика" пишем "Дубликат записи. ИНН в базе уже есть"
            $rootActivity = $this->GetRootActivity();
            $element_id = $rootActivity->GetVariable("ELEMENT_ID");
            $this->log('element_id : '.$element_id);
            CIBlockElement::SetPropertyValuesEx($element_id, CBPSearchByInnActivity::IBLOCK_ID, [
              "COMPANY_TITLE" => 'Дубликат записи Такой ИНН: '.$this->Inn.' в базе уже есть'
            ]);
        }

        
        //$rootActivity = $this->GetRootActivity(); // получаем объект активити
        // сохранение полученных результатов работы активити в переменную бизнес процесса
        //$rootActivity->SetVariable("TEST", $this->preparedProperties['Text']); 
        /*
        // получение значения полей документа в активити        
        $documentType = $rootActivity->getDocumentType(); // получаем тип документа
        $documentId = $rootActivity->getDocumentId(); // получаем ID документа        
        // получаем объект документа над которым выполняется БП (элемент сущности Компания)
        $documentService = CBPRuntime::GetRuntime(true)->getDocumentService(); 
        // $documentService = $this->workflow->GetService("DocumentService");   

        // поля документа
        $documentFields =  $documentService->GetDocumentFields($documentType);
        // $arDocumentFields = $documentService->GetDocument($documentId);   

        foreach ($documentFields as $key => $value) {
            if($key == 'UF_CRM_1718872462762'){ // поле номер ИНН
                $fieldValue = $documentService->getFieldValue($documentId, $key, $documentType);
                $this->log('значение поля Инн:'.' '.$fieldValue);
            }

            if($key == 'UF_CRM_TEST'){ // поле TEST
                $fieldValue = $documentService->getFieldValue($documentId, $key, $documentType);
                $this->log('значение поля TEST:'.' '.$fieldValue);
            }
        }*/

        return $errors;
    }

    

    /**
     * @param PropertiesDialog|null $dialog
     * @return array[]
     */
    public static function getPropertiesDialogMap(?PropertiesDialog $dialog = null): array
    {
        $map = [
            'Inn' => [
                'Name' => Loc::getMessage('SEARCHBYINN_ACTIVITY_FIELD_SUBJECT'),
                'FieldName' => 'inn',
                'Type' => FieldType::STRING,
                'Required' => true,
                'Options' => [],
            ],
        ];
        return $map;
    }




}