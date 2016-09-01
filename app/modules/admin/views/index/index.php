<?php
use yii\helpers\Url;
use common\yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use common\yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Admin - Cinepapaya';
?>
<div class="row home-items">
	<?php if(false): foreach (Yii::$app->modules as $moduleId => $module): ?>
		<?php if ($moduleId == 'admin') continue; ?>
		<?php if (!\Yii::$app->user->can("{$moduleId}/")) continue; ?>
		<div class="col-xs-6 col-sm-3 col-md-2 col-lg-2" style="margin-bottom:15px;">
			<a href="/<?= $moduleId ?>" class="btn btn-default btn-block">
				<h4 style="margin:0px;padding:8px;">
					<div style="height:80px;padding-top:5px;"><span class="fa fa-5x fa-<?= $moduleId; ?>"></span></div>
					<div style="height:35px;padding-top:5px;"></div>
				</h4>
			</a>
		</div>
	<?php endforeach; endif;?>
	<?php if(false): ?>
		<div class="col-xs-6 col-sm-3 col-md-2 col-lg-2" style="margin-bottom:15px;">
			<a href="/admin/asociar" class="btn btn-default btn-block">
				<h4 style="margin:0px;padding:8px;">
					<div style="height:80px;padding-top:5px;"><span class="fa fa-5x fa-plus"></span></div>
					<div style="height:35px;padding-top:5px;">Asociar Organizador</div>
				</h4>
			</a>
		</div>
	<?php endif; ?>
</div>
