<?php
/* @var $this \yii\web\View */
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

$hiddenColumns = [];
$formOptions = [];

?>
<script>
function generateCodes()
{
	var chars = "ABCDEFGHJKLMNPQRSTUVWXYZ0123456789";
	var charsCount = chars.length;
	var quantity = parseInt($("#<?= Html::getInputId($model, 'quantity') ?>").val());
	var codeLength = parseInt($("#<?= Html::getInputId($model, 'length') ?>").val());
	var prefix = $("#<?= Html::getInputId($model, 'prefix') ?>").val().toUpperCase();
	if (isNaN(quantity) || isNaN(codeLength))
		return {};
	
	var codes = {};
	for (var k = 0; k < quantity; k++)
	{
		var code = "";
		var dashPos = 0;
		for (var h = 0; h < codeLength; h++)
		{
			var rand = (Math.random() * 1000) % charsCount;
			code += chars.charAt(rand);
			dashPos++;
			if (dashPos % 4 == 0 && h < codeLength - 1)
			{
				dashPos = 0;
				code += "-";
			}
		}
		if (prefix != "")
			code = prefix + "-" + code;
		// Prevent codes starting with GCC for GiftCards
		if (code.toUpperCase().indexOf("GCC") == 0)
			continue;
		codes[code] = true;
	}
	return codes;
}
function renderCodes()
{
	$("#codesContainer").html("");
	var codes = generateCodes();
	var count = 1;
	for (var i in codes)
	{
		var inp = $("<input name='codes[]' type='text' readonly='readonly' class='form-control' style='padding-left:20px;font-size:10px;font-family: Menlo,Monaco,Consolas,\"Courier New\",monospace'>").val(i);
		var div = $("<div class='col-xs-4'>");
		div.append($("<label style='position:absolute;left:0px;top:7px;width:20px;background-color:#ccc;text-align:right;font-weight:bold;'>").html(count + ". "));
		div.append(inp);
		$("#codesContainer").append(div);
		count++;
	}
}
</script>
<?php $this->registerJs("renderCodes()", \yii\web\View::POS_READY); ?>

<div class="container">
<?php 
//
// Render errors if any
//------------------------------------------------------------------------------
?>
<?php if (isset($errors)) : ?>
<div class="alert alert-danger">
	<ul>
	<?php foreach ($errors as $error) : ?>
		<?php foreach ($error as $err) : ?>
		<li><?php echo $err; ?></li>
		<?php endforeach; ?>
	<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>

<?php 
//
// Render codes if any
//------------------------------------------------------------------------------
?>
<?php if (isset($codes)) {
	echo "<div class='alert alert-success'>";
	echo "<h4>Se generaron ".count($codes) ."/{$model->quantity} códigos promocionales</h4>";
	foreach ($codes as $k => $codeId)
	{
		$n = $k + 1;
		echo "<div>";
		echo "<strong>{$n}.</strong> <code>{$codeId}</code>";
		echo "</div>";
	}
	echo "</div>";
	echo "<hr/>";
} ?>

<?php
//
// Render the form
//------------------------------------------------------------------------------
$form = ActiveForm::begin(array_merge([
	'action' => '',
	'options' => ['class' => 'crud-form'],
], $formOptions));
?>

<div class="row fields">
	
	<?= $form->field($model, 'groupId')->textInput(['maxlength' => 20])->hint('Use this field if you like to group promotion codes. It will only be grouped if the name is exactly the same as the group id of a previous promotion code.') ?>
	<?= $form->field($model, 'description')->textInput(['maxlength' => 255])->hint('Enter a description of the reason why this promotion code exists.') ?>
	<?= $form->field($model, 'observations')->textarea(['rows' => 6]) ?>
	<?= $form->field($model, 'timeZoneId')->widget(\common\yii\jui\AutoComplete::className(), [
			'crudUrl' => \yii\helpers\Url::toRoute(['/admin/crud/time-zone']),
		]) ?>
	<?= $form->field($model, 'fromDate')->dateTimeInput()->hint('Local start date of the promotionCode. This means that it only can be used from this date onward.') ?>
	<?= $form->field($model, 'toDate')->dateTimeInput()->hint('The local date when this promotion code expires.') ?>
	<?= $form->field($model, 'currencyId')->widget(\common\yii\jui\AutoComplete::className(), [
			'crudUrl' => \yii\helpers\Url::toRoute(['/admin/crud/currency']),
		]) ?>
	<?= $form->field($model, 'seatPricePercent')->textInput()->hint('The percent of discount to apply to the seats price. Eg. 100%. If is a fixed price then set to 0% and set the seatPriceMin and seatPriceMax to the same value e.g. PEN 20') ?>
	<?= $form->field($model, 'seatPriceMin')->textInput()->hint('The min amount that should be discounted for seat price. After seatPricePercent if is less than this value then it will keep this value.') ?>
	<?= $form->field($model, 'seatPriceMax')->textInput()->hint('The max amount that should be discounted for seat price. After seatPricePercent if is more than this value then it will keep this value.') ?>
	<?= $form->field($model, 'seatFeePercent')->textInput()->hint('The percent of our seat fee that should be discounted. If you do not want to discount the fee set to 0. The whole fee 100.') ?>
	<?= $form->field($model, 'seatCountMin')->textInput()->hint('The minimum amount of seats allowed.') ?>
	<?= $form->field($model, 'seatCountMax')->textInput()->hint('The maximum amount of seats allowed.') ?>
	<?= $form->field($model, 'seatCountMultipleOf')->textInput()->hint('To restrict groups of tickets. 1 means can purchase 1,2,3,4,5,6... seats. 2 means can purchase 2,4,6,8... If you want to buy 6,8,10 then set this value to 2 and min to 6 and max to 10.') ?>
	
	<?= $form->field($model, 'comboPricePercent')->textInput()->hint('The percent of discount to apply to the combo price. Eg. 100%. If is a fixed price then set to 0% and set the comboPriceMin and comboPriceMax to the same value e.g. PEN 20') ?>
	<?= $form->field($model, 'comboPriceMin')->textInput()->hint('The min amount that should be discounted for combo price. After comboPricePercent if is less than this value then it will keep this value.') ?>
	<?= $form->field($model, 'comboPriceMax')->textInput()->hint('The max amount that should be discounted for combo price. After comboPricePercent if is more than this value then it will keep this value.') ?>
	<?= $form->field($model, 'comboFeePercent')->textInput()->hint('The percent of our combo fee that should be discounted. If you do not want to discount the fee set to 0. The whole fee 100.') ?>
	<?= $form->field($model, 'comboCountMin')->textInput()->hint('The minimum amount of combos allowed.') ?>
	<?= $form->field($model, 'comboCountMax')->textInput()->hint('The maximum amount of combos allowed.') ?>
	<?= $form->field($model, 'comboCountMultipleOf')->textInput()->hint('Same as seatCountMultipleOf but for combos') ?>
	
	<?= $form->field($model, 'souvenirPricePercent')->textInput()->hint('The percent of discount to apply to the souvenir price. Eg. 100%. If is a fixed price then set to 0% and set the souvenirPriceMin and souvenirPriceMax to the same value e.g. PEN 20') ?>
	<?= $form->field($model, 'souvenirPriceMin')->textInput()->hint('The min amount that should be discounted for souvenir price. After souvenirPricePercent if is less than this value then it will keep this value.') ?>
	<?= $form->field($model, 'souvenirPriceMax')->textInput()->hint('The max amount that should be discounted for souvenir price. After souvenirPricePercent if is more than this value then it will keep this value.') ?>
	<?= $form->field($model, 'souvenirFeePercent')->textInput()->hint('The percent of our souvenir fee that should be discounted. If you do not want to discount the fee set to 0. The whole fee 100.') ?>
	<?= $form->field($model, 'souvenirCountMin')->textInput()->hint('The minimum amount of souvenirs allowed.') ?>
	<?= $form->field($model, 'souvenirCountMax')->textInput()->hint('The maximum amount of souvenirs allowed.') ?>
	<?= $form->field($model, 'souvenirCountMultipleOf')->textInput()->hint('Same as seatCountMultipleOf but for souvenirs') ?>
	
	<?= $form->field($model, 'active')->checkbox(null, false)->hint('If the promotion code is active or not. You can use this to prevent a code from being used but normally it should always be active.') ?>
	<?= $form->field($model, 'prefix')->textInput(['maxlength' => 10, 'onkeyup' => "renderCodes();"])->hint('El prefijo para anteponer a los codigos generados') ?>
	<?= $form->field($model, 'length')->dropDownList([
			8 => 8,
			12 => 12,
			16 => 16,
			20 => 20,
		], ['onchange' => "renderCodes();"])->hint('La longitud del codigo promocional. Esto no incluye el prefijo') ?>
	<?= $form->field($model, 'quantity')->textInput(['onkeyup' => "renderCodes();"])->hint('La cantidad de codigos a generar') ?>
	
	<div class="form-group">
		<label class="col-sm-2 control-label">Códigos a generar</label>
		<div class="col-sm-10">
			<div id="codesContainer" class="row">
			</div>
		</div>
	</div>
</div>

<div class="row actionbar">
	<div class="col-sm-10 col-sm-push-2">
		<?= Html::submitButton('Generar', ['class' => 'btn btn-success']) ?>
	</div>
</div>
	
<?php ActiveForm::end(); ?>

</div>
