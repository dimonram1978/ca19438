<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */

// global $INTRANET_TOOLBAR;
// use Bitrix\Main\Engine\Contract\Controllerable;
//namespace Otus\Components;

use Bitrix\Main\Context,	
	Bitrix\Main\Application,
	Bitrix\Main\Type\DateTime,
	Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Engine\Contract\Controllerable,
	Bitrix\Iblock;
use Bitrix\Main\Engine\Contract;

use Models\MashinesTable as Mashines;
use Bitrix\Main\Diag\Debug;
use Bitrix\Main\Event;
use Bitrix\Main\EventManager;
use Bitrix\Main\EventResult;



class MashinesViewsComponent extends \CBitrixComponent
{

    protected $request;

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
    */
    public function onPrepareComponentParams($arParams) {
       // тут пишем логику обработки параметров, дополнение к параметрам по умолчанию
       return $arParams;
    }
    
    static function mashine_column(){
       $arr = \Bitrix\Iblock\Elements\ElementGarageTable::query()
	//->setFilter(['ID'=>$element_id])
         ->setSelect([
           'ID'=> (new IntegerField('ID',
					['title' => 'ID',]
				))->configureTitle(Loc::getMessage('LISTS_ENTITY_ID_FIELD'))
						->configurePrimary(true)
						->configureAutocomplete(true),
           'NAME',		   
           'Client_id',
           'Car_id'
        ])
        
        ->fetchcollection();
        return $arr;
         
    }

    private function getColumn()
    {
        /*$fieldMap = Mashines::getMap();
        //$fieldMap = $this::mashine_column(); 

        $columns = [];
        foreach ($fieldMap as $key => $field) {
            $columns[] = array(
                'id' => $field->getName(),
                'name' => $field->getTitle()
            );
        }*/
        $columns = [
	     ['id' => 'ID', 'name' => 'ID'], 
         ['id' => 'NAME', 'name' => 'Название машины'],
	     ['id' => 'Client_id', 'name' => 'id клиента'], 
         ['id' => 'car_id', 'name' => 'id машины клиента'], 
         ['id' => 'CAR', 'name' => 'Машина клиента'], 
         ['id' => 'MODEL', 'name' => 'Марка машины'], 
         ['id' => 'Year_prod', 'name' => 'Год производства'], 
         ['id' => 'COLOR', 'name' => 'Цвет машины'],
         ['id' => 'mileage', 'name' => 'Пробег'],
        ];
 
        return $columns;
    }

 
    private function getList($page = 1, $limit = 1)
    {

        $offset = $limit * ($page-1);
        $list = [];
        $obj_arr = \Bitrix\Iblock\Elements\ElementGarageTable::getList([
            'select' => ['ID','NAME','Client_id','Car_id','CAR','CAR.model_name','CAR.Year_prod','CAR.COLOR','CAR.mileage'],//,'UF_LASTNAME','UF_PHONE','UF_JOBPOSITION','UF_SCORE'


            'runtime' => ['CAR' => [
               'data_type' => \Bitrix\Iblock\Elements\ElementCustomerCarsTable::class,
               'reference' => [
                '=this.car_id.ELEMENT.ID' => 'ref.ID',
                ]
              ],
            ],
        ])->fetchCollection();
        foreach ($obj_arr as $key => $record){
	       $list[] = array('data' =>  [
             'ID' => $record->getid(), 
             'NAME' => $record->getName(),
             'Client_id' => $record->getClient_id()->getvalue(),
             'Car_id' => $record->getcar_id()->getvalue(),
			 'CAR' => $record->get('CAR')->getname(),
             'MODEL' => $record->get('CAR')->getmodel_name()->getvalue(),
             'Year_prod' => $record->get('CAR')->getYear_prod()->getvalue(),
             'COLOR' => $record->get('CAR')->getColor()->getvalue(),
             'mileage' => $record->get('CAR')->getmileage()->getvalue(),
            ]);  

        }
         
        
        return $list;
    }


    /**
     * Точка входа в компонент
     * Должна содержать только последовательность вызовов вспомогательых ф-ий и минимум логики
     * всю логику стараемся разносить по классам и методам 
     */
    public function executeComponent() {

        try
        {

            // получаем параметры методов GET и POST, из обьекта request который позволяет получить данные о текущем запросе: метод и протокол, запрошенный URL, переданные параметры
            $this->$request = Application::getInstance()->getContext()->getRequest();

           if(isset($this->$request['report_list'])){
                $page = explode('page-', $this->$request['report_list']);
                $page = $page[1];
            }else{
                $page = 1;
            }

            $this->arResult['SHOW_ROW_CHECKBOXES'] = false;

            if($this->arParams['SHOW_CHECKBOXES'] == 'Y'){
                $this->arResult['SHOW_ROW_CHECKBOXES'] = true;
            }

            $this->arResult['COLUMNS'] = $this->getColumn(); // получаем названия полей таблицы

            // pr($this->arResult['COLUMNS']); 
            // die();

            $this->arResult['NUM_PAGE'] = (empty($this->arParams['NUM_PAGE']))? 20 : $this->arParams['NUM_PAGE'];
            $this->arResult['LISTS'] = $this->getList($page, $this->arResult['NUM_PAGE']); // получаем записи таблицы
            $this->arResult['COUNT'] =  Mashines::getCount(); // количество записей         
            //Debug::writeToFile($this->arResult['LISTS'] , '$arResultLISTS', "/local/app/Events/log_Iblock3.txt");
            //Debug::writeToFile($this->arResult['COUNT'] , '$arResultCOUNT', "/local/app/Events/log_Iblock3.txt");
            $this->arResult['TABS'] = isset($this->arParams['TABS']) && is_array($this->arParams['TABS'])
			? $this->arParams['TABS'] : array();
           // $this->arResult['TABS'] = $this->updateTabsByEvent($this->arResult['TABS']);
            //Debug::writeToFile($this->arResult['TABS'] , '$arResultTABS', "/local/app/Events/log_Iblock3.txt");
            // подключаем шаблон
            $this->IncludeComponentTemplate();

        }
        catch (SystemException $e)
        {
            ShowError($e->getMessage());
        }
    }

    public function updateTabsByEvent(array $tabs): array
    {   //Debug::writeToFile('event' , 'event', "/local/app/Events/log_Iblock3.txt");
         //Debug::writeToFile($event , '$event', "/local/app/Events/log_Iblock3.txt");
         $event = new Event('crm', 'onEntityDetailsTabsInitialized', [
         'entityID' => $this->entityID,
         'entityTypeID' => $this->entityTypeID,
         'guid' => $this->guid,
         'tabs' => $tabs,
         ]);
        //Debug::writeToFile($event , '$event', "/local/app/Events/log_Iblock3.txt");
        EventManager::getInstance()->send($event);
  
        foreach ($event->getResults() as $result) {
          if ($result->getType() === EventResult::SUCCESS) {
           $parameters = $result->getParameters();
          if (is_array($parameters) && is_array($parameters['tabs'])) {
           $tabs = $parameters['tabs'];
          }
          }
        }

        return $tabs;
    }

     


} 