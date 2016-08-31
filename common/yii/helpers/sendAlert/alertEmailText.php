<?php
use yii\helpers\Html;

?>
ERROR: <?php echo Html::encode($errorTitle); ?>


Fecha de la alerta: <?php echo date('Y-m-d H:i:s') . " - " . \Yii::$app->getTimeZone(); ?>


<?php 
if (is_array($info))  
{	
	foreach ($info as $key => $value) 
	{
		echo Html::encode($key) . ": "; 
		if (is_string($value) || is_numeric($value))
			echo $value;
		else
			echo json_encode($value, JSON_PRETTY_PRINT);
		echo "\n\n";
	}
} 
else {
	echo json_encode($info, JSON_PRETTY_PRINT);
}
