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
                    ->setSelect(['NAME' => 'ELEMENT.NAME'])
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
            //print_r($procs);
            CIBlockElement::SetPropertyValues($ID, DoctorsTable::IBLOCK_ID, $procs, false);
            //CIBlockElement::SetPropertyValues($ID, DoctorsTable::IBLOCK_ID, $procs, "PROC_IDS_MULTI");
            //CIBlockElement::SetPropertyValues($ID, DoctorsTable::IBLOCK_ID, $cpec, "SPEC_IDS_MULTI");
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
          </div>
          <div class="procedures">  
            <h2>Выполняемые процедуры:</h2>
            <ul>
             <?php  foreach ($procs_doc as $proc) { ?>             
                <?='<li><span class="blue"> '.$proc['NAME'].' </span></li>'?>
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

