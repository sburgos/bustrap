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
</head>
<body>
	<?php $this->beginBody() ?>
	<?= $this->render('_parts/navbar'); ?>
	<div class="layout-content">
		<?= Alert::widget(); ?>
		<?= $content ?>
	</div>
	<?php $this->endBody() ?>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmM9_C0bPw2c1Vtf8DREdUi2FwBtYiAKU&callback=initMap" async defer></script>
</html>
<?php $this->endPage() ?>
