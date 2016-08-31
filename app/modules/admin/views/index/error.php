<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
\Yii::$app->controller->layout = "@app/layouts/layout-popup-md";
?>
<div style="padding:20px;margin-bottom:0px;">
	<h1 style="margin:0px 0px 20px 0px;" class="text-danger">
		<?= Html::encode($this->title) ?>
	</h1>
	<p class="lead" style="margin:0px;">
		<?= nl2br(Html::encode($message)) ?>
	</p>
</div>
<div style="padding:20px;background-color:#eee;border-top:1px solid #ddd;">
	<a href="/"><?= "Go back"; ?></a>
</div>