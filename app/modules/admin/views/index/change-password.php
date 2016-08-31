<?php
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title ="change password";
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['labelColSize' => 12]); ?>

	<div class="row" style="padding:20px;">
		<?= $form->field($model, 'currentPassword')->passwordInput(['autofocus'=>'autofocus']) ?>
		
		<?= $form->field($model, 'newPassword')->passwordInput() ?>
		
		<?= $form->field($model, 'checkPassword')->passwordInput() ?>
	</div>
	
	<div class="btn-group btn-group-justified">
		<div class="btn-group" role="group">
			<?= Html::a(Cancel, ["/"], ['tabindex' => -1, 'class' => 'btn btn-default btn-lg']) ?>
		</div>
		<div class="btn-group" role="group">
			<?= Html::submitButton(Change, ['class' => 'btn btn-primary btn-lg'])?>
		</div>
	</div>
	
<?php ActiveForm::end(); ?>

