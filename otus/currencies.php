<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Валюты");
$APPLICATION->SetAdditionalCSS('/doctors/style.css');

use Bitrix\Main\Loader;
use Bitrix\Currency\CurrencyTable;

Loader::includeModule('currency');

$result = []; // загружаем все валюты, какие есть
$result2 = [];
$result = \Bitrix\Currency\CurrencyTable::getList([
	'select' => ['CURRENCY'],//['CURRENCY', 'AMOUNT', 'AMOUNT_CNT', 'SORT', 'BASE'],
    'order'  => ['SORT' => 'ASC']
]);
$result2 =  \Bitrix\Currency\CurrencyTable::getList([
	'select' => ['CURRENCY'],//['CURRENCY', 'AMOUNT', 'AMOUNT_CNT', 'SORT', 'BASE'],
    'order'  => ['SORT' => 'ASC']
]);

function name_currency($CURRENCY){
  $name = "";
  
  switch ($CURRENCY){
    case "RUB": $name = "рубль РФ"; break;
    case "USD": $name = "доллар США"; break;
    case "EUR": $name = "ЕВРО EC"; break;
    case "UAH": $name = "Гривна из 404"; break;
    case "BYN": $name = "Юань КНР"; break;

    
  }

  return $name;

}

 //pr($_GET);
 if (isset($_GET['currency-submit'])) {
     
    if ( isset($_GET['CURRENCY']) ) {
       
       $currencyData2 = [];
       $currencyData2 = CurrencyTable::getList([
       'select' => ['CURRENCY','AMOUNT'],
       'filter' => ['CURRENCY' => $_GET['CURRENCY']],
        
       ])->fetch();
 
    } 
}
?>
<section class="doctors_tr">
  
   <div class="doctor1"> 
    <h3>Получаем курс через запрос к серверу:</h3><br>
    <form name="myForm1" method="GET">    
            <h3>Курсы валют:</h3>
            <br>
     <label for="CURRENCY">Cписок Валют:  </label>  
    
     <select name="CURRENCY">
       <option value=""  selected disabled>Cписок Валют:</option>
         <?php  foreach ($result as $curr):?>
                         <option value="<?=$curr['CURRENCY']?>"><?=name_currency($curr['CURRENCY']).' '.$curr['CURRENCY']?></option>
          <?php endforeach; ?>
     </select>
       
     <div class="add-buttons">    
              <input type="submit" name="currency-submit"  value="Отправить">
     </div>
   </form>   
   </div>
   <br>
   <div class="doctor1">
      <label for="currency_rate">Валюта:  </label> <br>
      <input type="text" name="currency_rate" placeholder="Валюта:"  size="20" value="<?=name_currency($currencyData2['CURRENCY']).' '.$currencyData2['CURRENCY']??''?>" disabled/><br><br>
      <label for="exchange_rate">Текущий курс:  </label> <br>
      <input type="text" name="exchange_rate" placeholder="Курс:"  size="20" value="<?=$currencyData2['AMOUNT']??''?>" disabled/><br><br>      
   </div>
   
 
   <div class="doctor1"> 
    <h3>Получаем курс через Ajax:</h3><br>
    <form name="myForm2" method="GET">     
            <h3>Курсы валют:</h3>
            <br>
     <label for="CURRENCY">Список Валют:  </label>  
      
     <select name="CURRENCY">
       <option value=""  selected disabled>Список Валют:</option>
         <?php  foreach ($result2 as $curr2):?>
                         <option value="<?=$curr2['CURRENCY']?>"><?=name_currency($curr2['CURRENCY']).' '.$curr2['CURRENCY']?></option>
          <?php endforeach; ?>
     </select>
    </form>
       <div id="CURRENCY"></div><input type="hidden" id='cur_uniid' name="cur_uniid" value=""><br><br><br>
       <div id="AMOUNT"></div>
       <div class="add-buttons">    
              <button id="btn2" onclick="ajaxload();">Показать курс</button>

        </div>
      
    </div>
    
</section>
<script>
   const currencySelect = document.myForm2.CURRENCY;
   const selection = document.getElementById("CURRENCY");
 
   function changeOption(){
    const selectedOption = currencySelect.options[currencySelect.selectedIndex];
    selection.textContent = "Вы выбрали: " + selectedOption.text;
    document.getElementById("cur_uniid").value = selectedOption.value;
   }
 
   currencySelect.addEventListener("change", changeOption);


   function ajaxload(){
        var xhr=new XMLHttpRequest();
        var cur_uniid= document.getElementById('cur_uniid').value;
        var goToUrl= '../otus/ajax.php?cur_uniid='+cur_uniid;
        xhr.onreadystatechange=function(){
        if(this.readyState==4){
                if(this.status >=200 && xhr.status < 300){
                    
                    document.getElementById('AMOUNT').innerHTML='Текущий курс: '+this.responseText;
                }
            }
        }
        
        xhr.open('GET', goToUrl, true);
        xhr.send();     // выполняем запрос
     
   }


</script>
 <?php
/*$result = []; // загружаем все валюты, какие есть
$result = \Bitrix\Currency\CurrencyTable::getList([
	'select' => ['*'],//['CURRENCY', 'AMOUNT', 'AMOUNT_CNT', 'SORT', 'BASE'],
    'order'  => ['SORT' => 'ASC']
]);
 
while ($currency = $result->fetch()) {
    echo  pr($currency ); echo '<br>';
}*/
?>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>