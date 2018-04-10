<?php
header("Content-type: text/css");

if(isset($_GET['css'])){
	?> 
/* P A C K E D */
	<?
}elseif(isset($_GET['js'])){
	?>
<!-- P A C K E D
	<?
}
$content = "";

/*
Sommer-Colors:
-----------------------
Sommer Gr�n: #99CA3C;
BG-Gr�n: #939B38;
blasses Gr�n: #DEE7B0;
dunkles Grau: #6D6E71;
Grau: #707072;
helles Grau: #858789;
text schwarz: #231F20;

Winter Colors:
-----------------------
Winter Lila: #592D1D;
BG-Braun: #592D1D;
dunkles Beige: #A66C4B;
helles Beige: #BF8C60;
ganz helles Beige: #F0E3BF;
text schwarz: #26150F;
*/

$sommer = array("#99CA3C", "#6D6E71", "#858789", "#707072", "#231F20", "#939B38", "#DEE7B0");
$winter = array("#592D1D", "#A66C4B", "#BF8C60", "#BF8C60", "#26150F", "#592D1D", "#F0E3BF");

##### BEGIN ::: Hier die zu benutzenden Scripte einbinden #####
$content.= file_get_contents("style.css");
/*if(!isset($_GET['sommer'])){ // is it Christmastime? :)
	$content = str_replace($sommer, $winter, $content);
}else{
}*/
##### END   ::: Hier die zu benutzenden Scripte einbinden #####


$arrSearch = array("\rn","\n","\r","\t\t\t\t\t\t","\t\t\t\t\t","\t\t\t\t","\t\t\t","\t\t","\t");
// Ist search ein Array und replace ein String, dann wird dieser String f�r jeden Wert von search angewandt.
//$content = str_replace($arrSearch, " ", $content);
echo $content;

if(isset($_GET['js'])){
	?>
-->
	<?
}

?>
