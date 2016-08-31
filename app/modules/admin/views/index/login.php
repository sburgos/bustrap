<?php
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = "Login";
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['labelColSize' => 12]); ?>

	<div style="padding:20px;">
		<?= $form->field($model, 'username')->textInput(['autofocus' => 'autofocus'])?>
		
		<?= $form->field($model, 'password')->passwordInput()?>
	
		<?= $form->field($model, 'rememberMe')->checkbox([], true) ?>
		
		<?= Html::submitButton('Ingresar', ['class' => 'btn btn-lg btn-primary btn-block'])?>
	</div>
	
<?php ActiveForm::end(); ?>

