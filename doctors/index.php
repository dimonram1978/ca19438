<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
/** @global $APPLICATION */
$APPLICATION->SetTitle('Врачи');
$APPLICATION->SetAdditionalCSS('/doctors/style.css');

// модели работающие с инфоблоками
use Models\Lists\DoctorsPropertyValuesTable as DoctorsTable;
use Models\Lists\ProcsPropertyValuesTable as ProcsTable;
use Models\Lists\CpecsPropertyValuesTable as CpecsTable;

// массивы для сохранения полученных данных
$doctors = [];
$procedures = [];
$cpecs = [];
$doctor = [];
$procs_doc = [];
$cpecs_doc = [];
$procs = [];
$path = trim($_GET['path'],'/');
$action = '';
$doctor_name = '';



if (!empty($path)) {
     
    $path_parts = explode('/',$path);
    if (sizeof($path_parts)<3) {
        if (sizeof($path_parts) == 2 && $path_parts[0] == 'edit') {
            $action = 'edit';
            $doctor_name = $path_parts[1];
            
        } else if (sizeof($path_parts) == 1 && in_array($path_parts[0],['new','newproc'])) {
            $action = $path_parts[0];
        } else $doctor_name = $path_parts[0];
    }
}
 

if (!empty($doctor_name)) {
        $doctor = DoctorsTable::query()
            ->setSelect([
                '*', 
                'NAME' => 'ELEMENT.NAME', 
                'PROC_IDS_MULTI',
                'SPEC_IDS_MULTI',
                'ID' => 'ELEMENT.ID'
            ])
            ->where("NAME", $doctor_name)
            ->fetch();
 
        if (is_array($doctor)) { //выводим одного доктора
             
            if($doctor['PROC_IDS_MULTI']){
                $procs_doc = ProcsTable::query()
                    ->setSelect(['ID'=> 'ELEMENT.ID', 'NAME' => 'ELEMENT.NAME'])
                    ->where("ELEMENT.ID", "in", $doctor['PROC_IDS_MULTI'])
                    ->fetchAll();
                    
            }
            if($doctor['SPEC_IDS_MULTI']){
                 
                $cpecs_doc = CpecsTable::query()
                    ->setSelect(['NAME' => 'ELEMENT.NAME'])
                    ->where("ELEMENT.ID", "in", $doctor['SPEC_IDS_MULTI'])
                    ->fetchAll();
            }
           
             
    
        }
        else {
            header("Location: /doctors");
            exit();
        }

        
}

//Список всех броирований пациентов
use \Bitrix\Iblock\Elements\ElementReservationTable as ReservationTable;
// Фильтр для бронирования
$Filter=[];
if ($doctor_name == '' ) {
   $Filter=[];    
}
else { if(is_array($doctor)){$Filter = ['doctor_id.ELEMENT.ID' => $doctor['ID']];}  }

$reserv = ReservationTable::query()
    ->setSelect([
       'id',
       'NAME',
       'doctor_id.ELEMENT.ID',
       'procedure_id.ELEMENT.ID',
       'booking',
       'patient',
       //'doctor_id.ELEMENT' => 'doctor_id_ELEMENT'
       'DOCTORS',
        'PROCEDURES'
    ])
    ->setFilter($Filter)
    ->setOrder(['ID' => 'desc'])
    ->registerRuntimeField(
        null,
        new \Bitrix\Main\Entity\ReferenceField(
            'DOCTORS',
            'Bitrix\Iblock\Elements\ElementDoctorTable',
            ['=this.doctor_id.ELEMENT.ID' => 'ref.ID']
        )
    )
    ->registerRuntimeField(
        null,
        new \Bitrix\Main\Entity\ReferenceField(
            'PROCEDURES',
            'Bitrix\Iblock\Elements\ElementProcTable',
            ['=this.procedure_id.ELEMENT.ID' => 'ref.ID']
        )
    )
 ->fetchCollection();

// если не выбран доктор и его
// выводим всех докторов 
if (empty($doctor_name) && empty($action)) { 
    $doctors = DoctorsTable::query()
        ->setSelect(['*', "NAME" => "ELEMENT.NAME", "ID" => "ELEMENT.ID"])
        ->fetchAll();
    $procedures = ProcsTable::query()
        ->setSelect(['*', "NAME" => "ELEMENT.NAME"])
        ->fetchAll();
    $cpecs = CpecsTable::query()
        ->setSelect(['*', "NAME" => "ELEMENT.NAME"])
        ->fetchAll();
}

if ($action == 'newproc') { // добавляем процедуру
    if (isset($_POST['proc-submit'])) {
        unset($_POST['proc-submit']);
        if (ProcsTable::add($_POST)) {
            header("Location: /doctors");
            exit();
        } else echo "Произошла ошибка";
    }
}

if ($action == 'new' || $action == 'edit') { // добавляем доктора
    if (isset($_POST['doctor-submit'])) {
        
        if ($action == 'edit' && !empty($_POST['ID'])) {
            //print_r($_POST);
            $ID = $_POST['ID'];
            unset($_POST['ID']);
            $_POST['IBLOCK_ELEMENT_ID']=$ID;

            $procs['PROC_IDS_MULTI'] = $_POST['PROC_IDS_MULTI'];
            $procs['SPEC_IDS_MULTI'] = $_POST['SPEC_IDS_MULTI'];
            unset($_POST['PROC_IDS_MULTI']);
             
            unset($_POST['SPEC_IDS_MULTI']);
            CIBlockElement::SetPropertyValues($ID, DoctorsTable::IBLOCK_ID, $procs, false);
 
            if (DoctorsTable::update($_POST['ID'], $_POST)) {
                header("Location: /doctors");
                exit();
            } else echo "Произошла ошибка";
        }
        if ($action=='new' && DoctorsTable::add($_POST)) {
            header("Location: /doctors");
            exit();
        } else echo "Произошла ошибка";
    }
    
    $proc_options = ProcsTable::query()->setSelect(["ID"=>"ELEMENT.ID","NAME"=>"ELEMENT.NAME"])->fetchAll();
    if (!empty($doctor_name)) {
        $data = $doctor;
    }
     

    $cpec_options = CpecsTable::query()->setSelect(["ID"=>"ELEMENT.ID","NAME"=>"ELEMENT.NAME"])->fetchAll();
    if (!empty($doctor_name)) {
        $data = $doctor;
    }
}
?>

<section class="doctors">
   <div class="doctor1">
     <?php if (empty($doctor_name) && empty($action)):?>
        <h1><span class="blue">Врачи:</span></h1>
        <div class="cards-list">
           <?php foreach ($doctors as $doc) { ?>
             <a class="card" href="/doctors/<?=$doc["NAME"]?>">
               <div class="fio">
                <?=$doc['LAST_NAME']?>
                <?=$doc['FIRST_NAME']?>
                <?=$doc['MIDDLE_NAME']?>
              </div>
             </a>
           <?php } ?>
        </div>
     <?php elseif  (empty($doctor_name) && ! empty($action)):  ?> 
        <div class="zagolovok"><a href="/doctors">Вернуться к списку врачей</a></div>
        <div class="cards-list">
            
        </div>  
     <?php elseif  (! empty($doctor_name) && empty($action)):  ?> 
        <h2><a href="/doctors">Вернуться к списку врачей</a></h2>
        <div class="cards-list">
          <div class="doctor_fio">
             <h3>Врач:</h3><br>
             <?= $doctor['LAST_NAME'].' '.$doctor['FIRST_NAME'].' '.$doctor['MIDDLE_NAME'];?> 
          </div>
        </div>       
     <?php elseif  (! empty($doctor_name) && ! empty($action)):  ?>  
        <h2><a href="/doctors">Вернуться к списку врачей</a></h2>
        <div class="cards-list">
          <div class="doctor_fio">
             <h3>Врач:</h3><br>
             <?= $doctor['LAST_NAME'].' '.$doctor['FIRST_NAME'].' '.$doctor['MIDDLE_NAME'];?> 
          </div>
        </div>     
     <?php endif;?>
   </div>
   <div class="doctor2">
     <?php if (empty($doctor_name) && empty($action)):?>
        <div class="add-buttons">
           <a href="/doctors/new"><button>Добавить врача</button></a>
           <a href="/doctors/newproc"><button>Добавить процедуру</button></a>
        </div>
        <div class="doctor4">
          <div class="procedures">  
             <h2>Процедуры:</h2>
            <ul>
               <?php foreach ($procedures as $proc) { ?>             
                <?='<li><span class="blue"> '.$proc['NAME'].' </span> - <span class="cursiv">'.$proc['DESCRIPTION'].'</span></li>'?>
               <?php } ?>
            </ul>
           </div>
           <br><br><br>
           <div class="cpecs">  
           <h2>Специальности:</h2>
            <ul>
             <?php foreach ($cpecs as $cpec) { ?>             
                <?='<li><span class="blue"> '.$cpec['NAME'].' </span></li>'?>
             <?php } ?>
            </ul>
          </div>           
        </div>
     <?php elseif (! empty($action)):?>   
         <?php if ($action=='new' || $action=='edit'): ?>
                <div class="zagolovok"><a class="card" href="/doctors/<?=$data["NAME"]?>">Вернуться в карточку врача:</a></div>
                <div class="cards-list">
                  <form method="POST">
                     <h2 style="text-align:center;">Данные врача</h2>
                     <div class="doctor-add-form">

                      <?php if (isset($data['ID'])):?>
                       <input type="hidden" name="ID" value="<?=$data['ID']?>" />
                      <?php endif;?>

                     <input type="text" name="NAME" placeholder="Название страницы врача (фамилия латиницей)" value="<?=$data['NAME']??''?>"/>
                     <input type="text" name="LAST_NAME" placeholder="Фамилия врача" value="<?=$data['LAST_NAME']??''?>"/>
                     <input type="text" name="FIRST_NAME" placeholder="Имя врача" value="<?=$data['FIRST_NAME']??''?>"/>
                     <input type="text" name="MIDDLE_NAME" placeholder="Отчество врача" value="<?=$data['MIDDLE_NAME']??''?>"/>
                     
                     <select multiple name="PROC_IDS_MULTI[]">
                      <option value="" selected disabled>Процедуры:</option>
                      <?php  foreach ($proc_options as $proc):?>
                         <option value="<?=$proc['ID']?>"
                            <?php if (isset($data['PROC_IDS_MULTI']) && in_array($proc['ID'],$data['PROC_IDS_MULTI'])):?>selected<?php endif;?>>
                            <?=$proc['NAME']?>
                         </option>
                      <?php endforeach; ?>
                     </select>
                      <br><br>
                     <select multiple name="SPEC_IDS_MULTI[]">
                      <option value="" selected disabled>Специализация:</option>
                      <?php  foreach ($cpec_options as $cpec):?>
                         <option value="<?=$cpec['ID']?>"
                            <?php if (isset($data['SPEC_IDS_MULTI']) && in_array($cpec['ID'],$data['SPEC_IDS_MULTI'])):?>selected<?php endif;?>>
                            <?=$cpec['NAME']?>
                         </option>
                      <?php endforeach; ?>
                      </select>
                      <br><br>
                  <input type="submit" name="doctor-submit" value="Сохранить"/>
                  
                  </form>
                </div>
         <?php elseif ($action == 'newproc'):  ?>
              
             <div class="zagolovok">Добавление процедуры:</div>
              <div class="cards-list">
              <form method="POST">
                  <div class="doctor-add-form">
                   <input type="text" name="NAME" placeholder="Название процедуры"/>
                   <input type="text" name="DESCRIPTION" placeholder="Описани процедуры"/>
                   <input type="submit" name="proc-submit" value="Сохранить"/>
                  </div>
              </form>  
               </div> 
         <?php endif;?>
      <?php elseif  (! empty($doctor_name) && empty($action)):  ?>
        <div class="doctor4">
          <div class="add-buttons">
           <a href="/doctors/edit/<?=$doctor_name?>"><button>Изменить данные врача</button></a>
           <div id="bx_popup_form" style="display:none; padding:10px;min-height: 300px" class="bx_login_popup_form">
              Содержимое всплывающего окна
           </div>
          </div>
          <div class="procedures">  
            <h2>Выполняемые процедуры:</h2>
            <div id="news"><h3>Нет новостей</h3></div>
            <ul>
             <?php  foreach ($procs_doc as $proc) { ?>             
                <?='<li><span class="blue"> '.$proc['NAME'].' </span>'.
                    '<a href="javascript:void(0)" onclick="openFormPopup('.$proc['ID'].')" class="recall">Записаться</a></li>'
                ?>
             <?php } ?>
            </ul>
          </div>
          <br><br><br>
         <div class="cpecs">  
           <h2>Специальности врача:</h2>
           <ul>
           <?php foreach ($cpecs_doc as $cpec) { ?>             
                <?='<li><span class="blue"> '.$cpec['NAME'].' </span></li>'?>
           <?php } ?>
           </ul>
         </div>
        </div>
     
     <?php endif;?>    
   </div>
</section>
<section>

 <table><?php
 echo '<tr><td colspan="6" bgcolor="#FBF0DB">Список записей к врачам:</td><tr>';
 echo '<tr><td>Номер:</td><td>Наименование:</td><td>Время:</td><td>Врач:</td><td>Пациент:</td><td>Процедура:</td><tr>';
 
 foreach ($reserv as $item): 
   echo '<tr><td>'.$item->getid().'</td>';
   echo '<td>'.$item->getName().'</td>';
       $arValue = unserialize(htmlspecialcharsback(base64_decode($item->getBooking()->getValue())), [stdClass::class]);
      
        $Date = ($arValue['DATE']) ? $arValue['DATE'] : '';
        $timeFrom = ($arValue['TIME_FROM']) ? $arValue['TIME_FROM'] : '';
        $timeTo = ($arValue['TIME_TO']) ? $arValue['TIME_TO'] : '';

        $html2 = '<div>&nbsp;Дата приёма: &nbsp;'.$Date.'&nbsp;время приёма: с&nbsp;'.$timeFrom.'&nbsp;по&nbsp;'.$timeTo.'</div>';
         
   echo '<td>'.$html2.'</td>';
   echo '<td>'.$item->get('DOCTORS')->getName().'</td>';
   echo '<td>'.$item->getPatient()->getValue().'</td>';
   echo '<td>'.$item->get('PROCEDURES')->getName().'</td>';
 endforeach;
?>
</table>
</section>

<?php

CJSCore::Init(['popup']);
?>
<script>
    function openFormPopup(proc_id)
    {

        var authPopup = BX.PopupWindowManager.create("FormPopup", proc_id,  {
            //console.log(al);
            //content: 'Контент, отображаемый в теле окна'
            
            width: 400, // ширина окна
            height: 350, // высота окна
            zIndex: 100, // z-index
            autoHide: true,
            offsetLeft: 0,
            offsetTop: 0,
            resizable: true,
            overlay : true,
            draggable: {restrict:true},
            closeByEsc: true,
            closeIcon: { right : "12px", top : "10px"},
            titleBar: 'Запись на процедуру',
            content: '<div style="text-align: center;">'+
                     '<form method="GET">'+
                     '<p><lable>Введите имя пациента:</lable><br><input type="text" id="patientName" name="patientName" placeholder="Имя пациента" size="18" /></p>'+  
                     '<p><lable>Введите дату записи:</lable><div class="day_zapic" >&nbsp;Дата приёма: &nbsp;<input type="date" id="DATE" name="DATE" value="">'+
                     '&nbsp;время приёма: с&nbsp;<input type="time" id="TIME_FROM" name="TIME_FROM" value="">'+
                     '&nbsp;по&nbsp;<input type="time" id="TIME_TO" name="TIME_TO" value=""></div></p>'+
                     '<p><lable>Врач: </lable><input type="text" disabled  id="doctor_name" name="doctor_name" value="<?echo $doctor_name;?>"/></p>'+
                     '<input type="hidden" id="doctor_id" name="doctor_id" value="<?echo $doctor['ID'];?>"/>'+ 
                     '<input type="hidden" id="procedure_id" name="procedure_id" value="'+proc_id+'"/>'+
                     '</form></div>',
            buttons: [
                new BX.PopupWindowButton({
                    text: 'Сохранить', // текст кнопки
                    id: 'save-btn', // идентификатор
                    className: 'ui-btn ui-btn-success', // доп. классы
                    events: {
                      click: function() {
                          ajaxload();
                          this.popupWindow.close();
                         // var goToUrl= '../local/ajax/hendler_records.php';
                         // $('#news').load(goToUrl);
                      }
                    }
                }),
            ],
            events: {
                onAfterPopupShow: function()
                {
                    this.setContent(BX("bx_recall_popup_form"));
                }
            }
        });

        authPopup.show();
    }

    function ajaxload(){
        var patientName= document.getElementById('patientName').value;
        var DATE= document.getElementById('DATE').value;
        var TIME_FROM= document.getElementById('TIME_FROM').value;
        var TIME_TO= document.getElementById('TIME_TO').value;
        var doctor_name= document.getElementById('doctor_name').value;
        var doctor_id= document.getElementById('doctor_id').value;
        var procedure_id= document.getElementById('procedure_id').value;
        
        
        var request = new XMLHttpRequest();
          function reqReadyStateChange() {
           if (request.readyState == 4 && request.status == 200)
               document.getElementById("news").innerHTML= "На Ваш запрос отвечаю:<br>"+request.responseText;
          }
 
       var goToUrl= '../local/ajax/hendler_records.php';
       var body= 'patientName='+patientName+'&DATE='+DATE+'&TIME_FROM='+TIME_FROM+'&TIME_TO='+TIME_TO+'&doctor_name='+doctor_name+'&doctor_id='+doctor_id+'&procedure_id='+procedure_id;
       request.open("POST", goToUrl);
       request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
       request.onreadystatechange = reqReadyStateChange;
       request.send(body);
    }
 
</script>
