 
<?
 //pr($arResult['LISTS']);
 ?>
<table>
    <tr>
      <th>CURRENCY:</th>
      <th>AMOUNT:</th>
      <th>Описание:</th>
    </tr>
  <?php
      foreach ($arResult['LISTS'] as $item) {
         
         echo '<tr>';
         echo '<td>'.$item['CURRENCY'].'</td>';
         echo '<td>'.$item['AMOUNT'].'</td>';
         $opis = CurrencyViewsComponent::name_currency($item['CURRENCY']);
        echo '<td>'.$opis.'</td>';
        echo '</tr>';
       

      }
   ?> 
</table>
<br><br>
 

 
 
<section class="doctors_tr">
  
   <div class="doctor1"> 
    <h3>Получаем курс через запрос к серверу:</h3><br>
    <form name="myForm1" method="GET">    
            <h3>Курсы валют:</h3>
            <br>
     <label for="CURRENCY">Cписок Валют:  </label>  
    
     <select name="CURRENCY">
       <option value=""  selected disabled>Cписок Валют:</option>
         <?php  foreach ($arResult['LISTS'] as $item):?>
                <option value="<?=$item['CURRENCY']?>"><?=CurrencyViewsComponent::name_currency($item['CURRENCY']).' '.$item['CURRENCY']?></option>
          <?php endforeach; ?>
     </select>
     <br><br>  
     <div class="add-buttons">    
              <input type="submit" name="currency-submit"  value="Отправить">
     </div>
   </form>   
   </div>
   <br>
   <div class="doctor1">
      <label for="currency_rate">Валюта:  </label> <br>
       
      <input type="text" name="currency_rate" placeholder="Валюта:"  size="20" value="<?=CurrencyViewsComponent::name_currency($arResult['LISTS_OTBOR']['CURRENCY']).' '.$arResult['LISTS_OTBOR']['CURRENCY']??''?>" disabled/><br><br>
      <label for="exchange_rate">Текущий курс:  </label> <br>
      <input type="text" name="exchange_rate" placeholder="Курс:"  size="20" value="<?=$arResult['LISTS_OTBOR']['AMOUNT']??''?>" disabled/><br><br>      
   </div>
   
 
   <div class="doctor1"> 
    <h3>Получаем курс через Ajax:</h3><br>
    <form name="myForm2" method="GET">     
            <h3>Курсы валют:</h3>
            <br>
     <label for="CURRENCY">Список Валют:  </label>  
      
     <select name="CURRENCY">
       <option value=""  selected disabled>Cписок Валют:</option>
         <?php  foreach ($arResult['LISTS'] as $item):?>
                <option value="<?=$item['CURRENCY']?>"><?=CurrencyViewsComponent::name_currency($item['CURRENCY']).' '.$item['CURRENCY']?></option>
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