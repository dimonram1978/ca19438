<?php
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

    $APPLICATION->SetTitle('Datamanager в Битрикс');

    use Bitrix\Main\Type;
    use Bitrix\Main\Entity\Query;

    //use Models\Lists\CarsPropertyValuesTable as CarsTable;
    use Models\BookTable as Books;
    use Models\AuthorTable as Authors;
    use Models\PublisherTable as Publishers;
    use Models\BookPublisherTable as BookPublisher;
    use Models\Lists\ShopsPropertyValuesTable as ShopsTable;
    use Models\Lists\PrintinghousePropertyValuesTable as PrintinghouseTable;
    //print_r($_GET);
    $path = trim($_GET['path'],'/');

    if (!empty($path)) {
     
        $path_parts = explode('/',$path);
       
        if (sizeof($path_parts)<3) {

            if (sizeof($path_parts) == 1 || $path_parts[0] == 'add') {
                $action = $path_parts[0];
            
            }
            if (sizeof($path_parts) == 2) {
                
                if (is_numeric($path_parts[1]))
                {
                    $book_id = $path_parts[1];
                }
                else
                {
                    $book_name = $path_parts[1];
                }
                if ($path_parts[0] == 'view' || $path_parts[0] == 'delete'|| $path_parts[0] == 'update'){
                    $action = $path_parts[0];
                  
                }
            }            
        }    
     
        if (! empty($action) || in_array($action,['add, delete','update'])){
            if ($action == 'delete') {
                    $result = Books::delete($book_id); 
                    if ($result->isSuccess()) {                      
                        header("Location: /data-manager");
                        exit();
                    } 
                    else {
                        $errors = $result->getErrorMessages();
                        echo 'Ошибка при удалении записи: ' . implode(', ', $errors);
                    }    
            }
            else if ($action == 'add') {
                $auth_options = Authors::query()->setSelect(['id', 'NAME'])->fetchAll();
                $publish_options = Publishers::query()->setSelect(['id', 'NAME'])->fetchAll();
                $shops_options = ShopsTable::query()->setSelect(['ID' => 'ELEMENT.ID', 'NAME'=> 'ELEMENT.NAME'])->fetchAll();
                $printinghouse_options = PrintinghouseTable::query()->setSelect(['ID' => 'ELEMENT.ID', 'NAME'=> 'ELEMENT.NAME'])->fetchAll();
                if (isset($_POST['doctor-submit'])) {
                    if ( isset($_POST['publish_date']) ) {
                        $publicationDate = explode ( '-', $_POST['publish_date'] );
                        if ( count($publicationDate) == 3 ) {
                          list ( $y, $m, $d ) = $publicationDate;
                          //print_r($publicationDate);
                          //$generic_date = mktime ( 0, 0, 0, $m, $d, $y ); 
                          $generic_date = new Type\Date($_POST['publish_date'], 'Y-m-d');
                        }
                    }
                    //print_r($_POST['PUBLISHERS']);
                    //var_dump($_POST['PUBLISHERS']);
                    $record = [
                        'name'=>$_POST['NAME'],
                        'text'=>$_POST['text'],
                        
                        'publish_date' => $generic_date,
                        'ISBN' =>$_POST['ISBN'],
                        //'AUTHOR'=>(int)($_POST['AUTHOR']),
                        'author_id' => $_POST['AUTHOR'],
                        'shops_id'  => $_POST['SHOPS'],
                        'printinghouse_id' => $_POST['PRINTINGHOUSE'],
                    ];
                    //print_r($_POST['PUBLISHERS']);
                    $res = Books::add($record);
                    if(!$res->isSuccess()){
                        var_dump($res->getErrorMessages());
                     }
                     $newID = $res->getid();
                     $q = new Query(BookPublisher::getEntity());
                     $q->setSelect(array('id','BOOK_ID','PUBLISHER_ID'));
                     $q->setFilter(array('BOOK_ID' => $newID));
                     $result = $q->exec(); // выполняем запрос
                     //$collection = $result->fetchCollection();
                     $collection = $result->fetchAll();
                     if (empty($collection)) {
                        echo 'Таких записей нет!';
                        foreach ($_POST['PUBLISHERS'] as $icon) 
                        {
                            $record2 = [
                                'BOOK_ID'=>$newID,
                                'PUBLISHER_ID'=>$icon,
                            ];
                            $res2 = BookPublisher::add($record2);
                            if(!$res2->isSuccess()){
                               var_dump($res2->getErrorMessages());
                            }
                        } 
                     } else {
                        //Все обнулим и добавим заново из массива $_POST['PUBLISHERS']
                       foreach ($collection as $icon) 
                        {
                                ///your insert code//
                          $icon['BOOK_ID'] = NULL;
                          $icon['PUBLISHER_ID'] = NULL; 
                        }  
                        foreach ($_POST['PUBLISHERS'] as $icon) 
                        {
                            $record2 = [
                                'BOOK_ID'=>$newID,
                                'PUBLISHER_ID'=>$icon,
                            ];
                            $res2 = BookPublisher::add($record2);
                            if(!$res2->isSuccess()){
                               var_dump($res2->getErrorMessages());
                            }
                        } 
                         
                     }
                     header("Location: /data-manager");
                     exit();
                      
                }                
            }
            else if ($action == 'update') {
                $auth_options = Authors::query()->setSelect(['id', 'NAME'])->fetchAll();
                $publish_options = Publishers::query()->setSelect(['id', 'NAME'])->fetchAll();
                $shops_options = ShopsTable::query()->setSelect(['ID' => 'ELEMENT.ID', 'NAME'=> 'ELEMENT.NAME'])->fetchAll();
                $printinghouse_options = PrintinghouseTable::query()->setSelect(['ID' => 'ELEMENT.ID', 'NAME'=> 'ELEMENT.NAME'])->fetchAll();
                if (isset($_POST['doctor-submit'])) {
                    if ( isset($_POST['publish_date']) ) {
                        $publicationDate = explode ( '-', $_POST['publish_date'] );
                        if ( count($publicationDate) == 3 ) {
                          list ( $y, $m, $d ) = $publicationDate;
                          $generic_date = new Type\Date($_POST['publish_date'], 'Y-m-d');
                        }
                    }
                    $record = [
                        'name'=>$_POST['NAME'],
                        'text'=>$_POST['text'],
                        
                        'publish_date' => $generic_date,
                        'ISBN' =>$_POST['ISBN'],
                        'author_id' => $_POST['AUTHOR'],
                        'shops_id'  => $_POST['SHOPS'],
                        'printinghouse_id' => $_POST['PRINTINGHOUSE'],
                    ];

                    //print_r($record);
                    $res = Books::update($_POST['ID'], $record);
                    if(!$res->isSuccess()){
                        var_dump($res->getErrorMessages());
                     }
                     $newID = $res->getid();
                     $q = new Query(BookPublisher::getEntity());
                     $q->setSelect(array('id','BOOK_ID','PUBLISHER_ID'));
                     $q->setFilter(array('BOOK_ID' => $newID));
                     $result = $q->exec(); // выполняем запрос
                     //$collection = $result->fetchCollection();
                     $collection = $result->fetchAll();
                     if (empty($collection)) {
                        echo 'Таких записей нет!';
                        foreach ($_POST['PUBLISHERS'] as $icon) 
                        {
                            $record2 = [
                                'BOOK_ID'=>$newID,
                                'PUBLISHER_ID'=>$icon,
                            ];
                            $res2 = BookPublisher::add($record2);
                            if(!$res2->isSuccess()){
                               var_dump($res2->getErrorMessages());
                            }
                        } 
                     } else {
                        //Все обнулим и добавим заново из массива $_POST['PUBLISHERS']
                       foreach ($collection as $icon) 
                        {
                                ///your insert code//
                          $icon['BOOK_ID'] = NULL;
                          $icon['PUBLISHER_ID'] = NULL; 
                        }  
                        foreach ($_POST['PUBLISHERS'] as $icon) 
                        {
                            $record2 = [
                                'BOOK_ID'=>$newID,
                                'PUBLISHER_ID'=>$icon,
                            ];
                            $res2 = BookPublisher::add($record2);
                            if(!$res2->isSuccess()){
                               var_dump($res2->getErrorMessages());
                            }
                        } 
                         
                     }
                     header("Location: /data-manager");
                     exit();
                      
                } 
            }
        }
    }
     

if (!empty($book_id) || !empty($book_name)) {
        if (!empty($book_id)){$usl1="id";$usl2=$book_id; $filter_name = 'ID'; $arrfilter= [$book_id];}
        else {$usl1="NAME"; $usl2=$book_name;  $filter_name = 'NAME'; $arrfilter= [$book_name];}
        $arrbook = Books::getList([
            'select' => [            
                'id', 
                'name', 
                'text', 
                'publish_date',
                'ISBN',
                'AUTHOR',
                //'PUBLISHERS',
                'SHOPS',
                'PRINTINGHOUSE',
        
            ], 
            'filter' => [$filter_name => $arrfilter],
        ])->fetch();   
        //Не знаю PUBLISHERS без коллекции не выводится
        $arrbook2 = Books::getList([
            'select' => [            
                'PUBLISHERS',       
            ], 
            'filter' => [$filter_name => $book_name],
        ])->fetchCollection();
        
 }

    
   

    $collection = Books::getList([
        'select'  => [            
            'id', 
            'name', 
            'text', 
            'publish_date',
            'ISBN',
            'AUTHOR',
            'PUBLISHERS',
            'SHOPS',
            'PRINTINGHOUSE',
        ], 
 
    ])->fetchCollection();
    // ])->fetchAll();
 ?>

 <section class="doctors">
 <div class="doctor1">   
   <table>
    <tr>
    <th>Id:</th>
    <th>Name:</th>
    <th>Text:</th>
    <th></th>
    <th>Publish_date:</th>
    <th></th>
    <th>ISBN:</th>
    <th></th>
    <th>AUTHOR:</th>
    <th></th>
    <th>Издательство:</th>
    
    <th>Магазин:</th>
    <th>Типография:</th>
   </tr>
   <?php
      foreach ($collection as $key => $record) {
        
        //print_r($record);
        echo '<tr><td><a href="/data-manager/view/'.$record->getid().'">'.$record->getid().'</a></td><td><a href="/data-manager/view/'.$record->getName().'">'.$record->getName().'</a></td>
        <td>'.$record->getText().'<td><td>'.$record->getpublish_date().'<td><td>'.$record->getIsbn().'<td><td>'.$record->getAuthor()->getName().'<td>';
        echo '<td>';
           foreach ($record->getPublishers() as $publisher)
            {echo $publisher->getName().'<br/>';}
           
         
        echo '</td>';
        if ($record->getShops() !== null){echo '<td>'.$record->getShops()->getName().'</td>';}else{echo '<td>---</td>';}
        
         
         
        if ($record->getPrintinghouse() !== null){echo '<td>'.$record->getPrintinghouse()->getName().'</td>';}else{echo '<td>---</td>';}
        
        echo '</tr>';

      }
    ?> 
   
    
   </table>

   <div class="doctor2">
      
        <div class="add-buttons">
           <a href="/data-manager/add"><button>Добавить запись</button></a>
           <!--<a href="/data-manager/edit"><button>Изменить запись</button></a>
           <a href="/data-manager/del"><button>Удалить запись</button></a>-->
        </div>
    </div>
 </div>
 <div class="doctor2">
 
   <?php if ((! empty($book_id) || ! empty($book_name)) && $action=='view'):?>
     
    <h2><a href="/data-manager">Вернуться к списку книг</a></h2>
    <div class="cards-list">
        <form method="POST">    
            <h3>Книга:</h3><br>
            <br>
             
            <label for="ID">ID:  </label> <input type="text" name="ID" size="5"  placeholder="ID" value="<?=$arrbook['id']??''?>" disabled/><br>
            <label for="NAME">Название книги:  </label> <input type="text" name="NAME" size="50"  placeholder="Название книги" value="<?=$arrbook['name']??''?>" disabled/><br>
            <label for="text">Описание:  </label> <input type="text" name="text" placeholder="Описание"  size="50" value="<?=$arrbook['text']??''?>" disabled/><br>
            <label for="publish_date">Дата публикации:  </label> <input type="date" name="publish_date" placeholder="Дата публикации"  size="20" value="<?=$arrbook['publish_date']->format("Y-m-d")??''?>" disabled/><br>
            <label for="ISBN">Код ISBN:  </label>  <input type="text" name="ISBN" placeholder="код ISBN"  size="20" value="<?=$arrbook['ISBN']??''?>" disabled/><br>
            <label for="AUTHOR">Автор:  </label><br><input type="text" name="AUTHOR" placeholder="Автор:"  size="20" value="<?=$arrbook['MODELS_BOOK_AUTHOR_name']??''?>" disabled/><br><br>
            <label for="PUBLISHERS">Издательства:  </label>
             <table>
                <?php 
                 foreach ($arrbook2 as $key => $record){
                    foreach ($record->getPublishers() as $publish){
                        {echo '<tr><td>'.$publish->getName().'</td></tr><br>';}
                    }
                 }                
                ?>
                </table><br> <br> 
            <label for="SHOPS">Книжный магазин:  </label><br>
            <input type="text" name="SHOPS" placeholder="Книжный магазин:"  size="20" value="<?=$arrbook['MODELS_BOOK_SHOPS_NAME']??''?>" disabled/><br><br>  
            <label for="PRINTINGHOUSE">Типография:  </label><br>
            <input type="text" name="PRINTINGHOUSE" placeholder="Типография:"  size="40" value="<?=htmlspecialchars($arrbook['MODELS_BOOK_PRINTINGHOUSE_NAME'])??''?>" disabled/><br><br>         
            <div class="add-buttons">    
              <button><a href="/data-manager/update/<?=$arrbook['id']?>">Изменить запись</a></button>
              
              <button><a href="javascript:confirmDelete(<?=$arrbook['id']?>)">Удалить запись</a></button>               
            </div>
        </form>
    </div>   
     
     
   <?php elseif(in_array($action, ["add", "delete","update"])):  ?> 
     <?php if ($action =='add'): ?>
     
        <h2><a href="/data-manager">Вернуться к списку книг</a></h2>
        <div class="cards-list">
          <form method="POST">    
            <h3>Добавить запись книги:</h3><br>
            <br>
             
            <label for="NAME">Название книги:  </label> <input type="text" name="NAME" size="50"  placeholder="Название книги" value="<?=$book['name']??''?>" /><br>
            <label for="text">Описание:  </label> <input type="text" name="text" placeholder="Описание"  size="50" value="<?=$book['text']??''?>" /><br>
            <label for="publish_date">Дата публикации:  </label> <input type="date" name="publish_date" placeholder="Дата публикации"  size="20" value="<?=$book['publish_date']??''?>" /><br>
            <label for="ISBN">Код ISBN:  </label>  <input type="text" name="ISBN" placeholder="код ISBN"  size="20" value="<?=$book['ISBN']??''?>" /><br>
            <label for="AUTHOR">Автор:  </label>  
            <select name="AUTHOR">
                      <option value=""  selected disabled>Список авторов:</option>
                      <?php  foreach ($auth_options as $auth):?>
                         <option value="<?=$auth['id']?>"><?=$auth['name']?></option>
                      <?php endforeach; ?>
            </select>
            
            <br><br>
            <label for="PUBLISHERS">Издательства:  </label> 
            <select multiple name="PUBLISHERS[]">
                      <option value=""  selected disabled>Список издательств:</option>
                      <?php  foreach ($publish_options as $publish):?>
                         <option value="<?=$publish['id']?>"><?=$publish['name']?></option>
                      <?php endforeach; ?>
            </select>
            
            <label for="SHOPS">Книжный магазин:  </label><br>
            <select name="SHOPS"> 
                      <option value="" selected disabled>Выбор магазина:</option>
                      <?php  foreach ($shops_options as $shop):?>
                        
                         <option value="<?=$shop['ID']?>">
                            <?=$shop['NAME']?>
                         </option>
                      <?php endforeach; ?>
                       
            </select>
            
            
            <br><br>
            <label for="PRINTINGHOUSE">Типография:  </label><br>
            <select name="PRINTINGHOUSE"> 
                      <option value="" selected disabled>Выбор типографии:</option>
                      <?php  foreach ($printinghouse_options as $ph):?>
                        
                         <option value="<?=$ph['ID']?>">
                            <?=$ph['NAME']?>
                         </option>
                      <?php endforeach; ?>
                       
            </select>
            <br><br>

            <div class="add-buttons">    
              
              <input type="submit" name="doctor-submit" value="Сохранить"/>
               
            </div>
          </form>
        </div>         
     

     <?php elseif ($action == 'update'):  ?>  
         
        
        <h2><a href="/data-manager">Вернуться к списку книг444</a></h2>
        <div class="cards-list">
          <form method="POST">    
            <h3>Книга:</h3><br>
            <br>
             
            <label for="ID">ID:  </label> <input type="text" name="ID" size="5"  placeholder="ID" value="<?=$arrbook['id']??''?>" /><br>
            <label for="NAME">Название книги:  </label> <input type="text" name="NAME" size="50"  placeholder="Название книги" value="<?=$arrbook['name']??''?>" /><br>
            <label for="text">Описание:  </label> <input type="text" name="text" placeholder="Описание"  size="50" value="<?=$arrbook['text']??''?>" /><br>
            <label for="publish_date">Дата публикации:  </label> <input type="date" name="publish_date" placeholder="Дата публикации"  size="20" value="<?=$arrbook['publish_date']->format("Y-m-d")??''?>" /><br>
            <label for="ISBN">Код ISBN:  </label>  <input type="text" name="ISBN" placeholder="код ISBN"  size="20" value="<?=$arrbook['ISBN']??''?>" /><br>
            <label for="AUTHOR">Автор:  </label><br>
            <select name="AUTHOR">
                      <option value="" selected disabled>Список авторов:</option>
                      <?php  foreach ($auth_options as $auth):?>
                         <option value="<?=$auth['id']?>"
                           <?php if (isset($arrbook['MODELS_BOOK_AUTHOR_id']) && $auth['id'] == $arrbook['MODELS_BOOK_AUTHOR_id']):?>
                            selected
                           <?php endif;?>
                           >
                           <?=$auth['name']?></option>
                      <?php endforeach; ?>
            </select>
            <br> <br><br>
            <label for="PUBLISHERS">Издательства:  </label>
            <select multiple name="PUBLISHERS[]">
                      <option value=""  selected disabled>Список издательств:</option>
                      <?php  foreach ($publish_options as $publish):?>
                         <option value="<?=$publish['id']?>"><?=$publish['name']?></option>
                      <?php endforeach; ?>
            </select>
            <br><br>
           
            <label for="SHOPS">Книжный магазин:  </label><br>
            <select name="SHOPS"> 
                      <option value="" selected disabled>Выбор магазина:</option>
                      <?php  foreach ($shops_options as $shop):?>
                        
                         <option value="<?=$shop['ID']?>">
                            <?=$shop['NAME']?>
                         </option>
                      <?php endforeach; ?>
                       
            </select>
            
            
            <br><br>
            <label for="PRINTINGHOUSE">Типография:  </label><br>
            <select name="PRINTINGHOUSE"> 
                      <option value="" selected disabled>Выбор типографии:</option>
                      <?php  foreach ($printinghouse_options as $ph):?>
                        
                         <option value="<?=$ph['ID']?>">
                            <?=$ph['NAME']?>
                         </option>
                      <?php endforeach; ?>
                       
            </select>
            <br><br>
            <div class="add-buttons">    
                <input type="submit" name="doctor-submit" value="Сохранить"/>
            </div>
          </form>
    
    
    
    </div>   
     <?php endif;?>
   <?php endif;?>
 </div>
 </section>    
<script>
  
 function confirmDelete(delUrl) {
  if (confirm("Вы подтверждаете удаление книги с id "+ delUrl+" ?")) {
   document.location = "/data-manager/delete/"+ delUrl;
 }
}
</script>


<?php
   require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>