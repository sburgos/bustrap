<?php

use yii\helpers\Url;
use common\yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\admin\orm\User */

$this->title = 'User' . ": " . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(); ?>

	<div class="row text-center">
		<div class="col-xs-12" style="padding:5px 30px;">
			<h2>Estás por cerrar tu sesión e iniciarla como:</h2>
			<h3 class="text-primary"><?= Html::encode($model->username) . "<br/><br/>" . Html::encode($model->displayName); ?></h3>
			<br/>
			<h4>Cambiar a esta cuenta?<br/><br/></h4>
		</div>
	</div>
	
	<div class="btn-group btn-group-justified">
		<div class="btn-group" role="group">
			<?= Html::a( "Cancel", ["/admin/crud/user-admin/view", 'id' => $model->id], ['tabindex' => -1, 'class' => 'btn btn-default btn-lg']) ?>
		</div>
		<div class="btn-group" role="group">
			<?= Html::submitButton( "Change", ['class' => 'btn btn-primary btn-lg'])?>
		</div>
	</div>
	
<?php ActiveForm::end(); ?>


