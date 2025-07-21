<?php
  require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

  
  use Bitrix\Main\Engine\CurrentUser;
  $userName = CurrentUser::get()->getFullName();
   
  echo  $userName;