<?php

use yii\helpers\Url;
use common\yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model modules\admin\orm\User */

$this->title = 'User' . ": " . $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['labelColSize' => 12]); ?>

	<div class="row" style="padding:0px 20px 20px 20px;">
		<h2 class="text-center" style="background-color:#eee;margin-top:0px;margin-bottom:20px;margin-left:-10px;margin-right:-10px;padding-bottom:10px;"><?= "<small>(" . Html::encode($model->user->username) . ")</small><br/>" . Html::encode($model->user->displayName); ?></h2>
		
		<?= $form->field($model, 'newPassword')->passwordInput(['autofocus' => 'autofocus']) ?>
		
		<?= $form->field($model, 'checkPassword')->passwordInput() ?>
	</div>
	
	<div class="btn-group btn-group-justified">
		<div class="btn-group" role="group">
			<?= Html::a("Cancel", ["/admin/crud/user-admin/view", 'id' => $model->user->id], ['tabindex' => -1, 'class' => 'btn btn-default btn-lg']) ?>
		</div>
		<div class="btn-group" role="group">
			<?= Html::submitButton("Change", ['class' => 'btn btn-primary btn-lg'])?>
		</div>
	</div>
	
<?php ActiveForm::end(); ?>


