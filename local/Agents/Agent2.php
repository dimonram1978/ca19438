<?
//Функция для агента
use Bitrix\Main\Loader;
 
use Models\RandomOrg as Rand1;
 

function testAgent2()
{
    
 $random = new \Rand1\Random('e09eee81-8e53-49ab-894a-3d428da5dbbc');
// Simple method
// following functions returns 52 random non-repeating numbers between 1-52
  $result = $random->generateIntegers(52, 1, 52, false);
  pr($result);
   
	 
	return "testAgent2();";
 }

?>