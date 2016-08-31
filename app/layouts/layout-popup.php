<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use common\assets\FontAwesomeAsset;
use yii\helpers\BaseUrl;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
if (!isset($popupWidth))
	$popupWidth = "100%";
?>
<?php $this->beginPage()?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<?= $this->render('_parts/head'); ?>
	<style>
	body {
		background-color:#eee;
		padding:8px;
	}
	</style>
</head>
<body>
	<?php $this->beginBody() ?>
	<div style="box-shadow:0px 0px 10px #777;background-color:#fff;margin:0px auto;width:<?= $popupWidth; ?>;max-width:100%;">
		<div style="text-align:center;position:relative;background-color:#000;padding:15px 0px;">
			<a href="/"><img src="/img/logo-header.png" ></a>
		</div>
		<div><?= $content ?></div>
	</div>
	<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
