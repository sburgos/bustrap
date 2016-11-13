<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use common\assets\FontAwesomeAsset;
use common\yii\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
FontAwesomeAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<?= $this->render('_parts/head'); ?>
	<style>
		.layout-content-app{
			position: fixed;
			left: 0;
			top: 0px;
			right: 0;
			bottom: 0;
			overflow: auto;
			padding: 0px;
			z-index: 10;
		}
	</style>
	<script src="/js/sweetalert.min.js"></script>
	<script src="/js/jquery-3.1.1.min.js"></script>

	<link rel="stylesheet" href="/js/sweetalert.css">
	<link rel="stylesheet" href="/css/card.css">
	<link rel="stylesheet" href="/css/timeline.css">
</head>
<body>
<?php $this->beginBody() ?>
<div class="layout-content-app">
	<?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAVN07EXkJ1KIKGJUE5TtB8Yixj4NX0JcE&callback=initMap" async defer></script>

</html>
<?php $this->endPage() ?>
