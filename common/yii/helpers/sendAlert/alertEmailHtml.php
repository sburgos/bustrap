<?php
use yii\helpers\Html;

$tdStyle = "vertical-align:top;text-align:left;border:1px solid #ccc;padding:5px;font-size:11px;font-family:consolas,monospace;";
$thStyle = $tdStyle . "background-color:#eee;";
$divStyle = "width:500px;overflow:auto;white-space:pre-wrap;";
if (!isset($errorColor))
	$errorColor = '#ff0000';
?>
<h1 style='color:<?= $errorColor ?>;'><?= Html::encode($errorTitle); ?></h1>
<h4>Fecha de la alerta: <?= date('Y-m-d H:i:s') . " - " . \Yii::$app->getTimeZone(); ?></h4>
<table style='border:1px solid #ccc;border-collapse:collapse;'>
	<tr>
		<th style='<?php echo $thStyle; ?>'>KEY</th>
		<th style='<?php echo $thStyle; ?>'>VALUE</th>
	</tr>
	<?php if (is_array($info)) : ?>
		<?php foreach ($info as $key => $value) : ?>
		<tr>
			<td style="<?php echo $tdStyle; ?>"><?php echo Html::encode($key); ?></td>
			<td style="<?php echo $tdStyle; ?>">
				<div style="<?php echo $divStyle; ?>"><?php
					if (is_string($value) || is_numeric($value))
						echo $value;
					else
						echo json_encode($value, JSON_PRETTY_PRINT);
				?></div>
			</td>
		</tr>
		<?php endforeach; ?>
	<?php else: ?>
		<tr>
			<td style="<?php echo $tdStyle; ?>">??</td>
			<td style="<?php echo $tdStyle; ?>"><?php echo json_encode($info, JSON_PRETTY_PRINT); ?></td>
		</tr>
	<?php endif; ?>
</table>
