<?php
use yii\helpers\Html;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model orm\event\Event */
/* @var $form common\yii\widgets\ActiveForm */

$admin = \orm\event\AdminManager::find()->where(['adminId' => \Yii::$app->user->id])->asArray()->one();

$managerId = $admin['manageId'];

// 
// Determine the form action
//------------------------------------------------------------------------------
if (!isset($formAction)) {
	if ($model->isNewRecord)
		$formAction = ['/events/event/create'];
	else {
		$formAction = ['/events/event/update'];
		if (count($model->primaryKey()) == 1)
		{
			$modelPk = $model->primaryKey();
			$pkKey = current($modelPk);
			$formAction['id'] = $model->{$pkKey};
		}
		else { 
			foreach ($model->primaryKey() as $key)
				$formAction[$key] = $model->{$key};
		}
	}
}

// 
// Determine if __goback should be appended to the form action
//------------------------------------------------------------------------------
if (isset($appendGoBack)) {
	if ($appendGoBack === true)
		$formAction['__goback'] = $_SERVER['REQUEST_URI'];
	else if (is_string($appendGoBack))
		$formAction['__goback'] = appendGoBack;
} else if (isset($_GET['__goback'])) {
	$formAction['__goback'] = $_GET['__goback'];
}

// 
// Determine columns to hide
//------------------------------------------------------------------------------
if (!isset($hiddenColumns) || !is_array($hiddenColumns))
	$hiddenColumns = ['latitude','longitude','managerId'];

// 
// Determine form options
//------------------------------------------------------------------------------
if (!isset($formOptions) || !is_array($formOptions))
	$formOptions = [];
	
// 
// Determine submit container class
//------------------------------------------------------------------------------
if (!isset($submitContainerClass))
	$submitContainerClass = "col-sm-10 col-sm-push-2";

// 
// Setup submitButton settings
//------------------------------------------------------------------------------
$submitButton = [
	'label' => $model->isNewRecord ? 'Add' : 'Save',
	'options' => [
		'class' => 'btn ' . ($model->isNewRecord ? "btn-success" : "btn-primary"),
	],
];
if ($formAction == ['index']) {
	$submitButton['label'] = 'Search';
	$submitButton['options']['class'] = "btn btn-info";
}


// 
// Render the form
//------------------------------------------------------------------------------
$form = ActiveForm::begin(array_merge([
	'action' => $formAction,
	'options' => ['class' => 'crud-form'],
], $formOptions)); 
?>

	<div class="row fields">
	<?php if (in_array('name', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'name')->textInput(['maxlength' => 300]) ?>
	<?php if (in_array('name', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('latitude', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'latitude')->textInput(['maxlength' => 45]) ?>
	<?php if (in_array('latitude', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('longitude', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'longitude')->textInput(['maxlength' => 45]) ?>
	<?php if (in_array('longitude', $hiddenColumns)) echo "</div>"; ?>

		<div class="form-group field-event-geo required">
			<label class="col-sm-2 control-label" for="event-geo">Geoposition</label>
			<div class="col-sm-10"><div id="map" style="width:100%; height:200px;"></div><div class="help-block"></div></div>
		</div>

	<?php if (in_array('stock', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'stock')->textInput() ?>
	<?php if (in_array('stock', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('toDate', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'toDate')->dateTimeInput() ?>
	<?php if (in_array('toDate', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('fromDate', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'fromDate')->dateTimeInput() ?>
	<?php if (in_array('fromDate', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('image', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'image')->textInput(['maxlength' => 200]) ?>
	<?php if (in_array('image', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('description', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
	<?php if (in_array('description', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('managerId', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'managerId')->textInput(['value' => $managerId]) ?>
	<?php if (in_array('managerId', $hiddenColumns)) echo "</div>"; ?>

	<?php if (in_array('ticketText', $hiddenColumns)) echo "<div style='display:none;'>"; ?>
		<?= $form->field($model, 'ticketText')->textarea(['rows' => 6]) ?>
	<?php if (in_array('ticketText', $hiddenColumns)) echo "</div>"; ?>

	</div>

	<div class="row actionbar">
		<div class="<?= $submitContainerClass; ?>">
			<?php if (isset($formAction['__goback'])) : ?>
				<?= Html::a('Cancel', 
					$formAction['__goback'], [
						'class' => 'btn btn-default', 
						'tabindex' => -1,
						'onclick' => "if ($(this).parents('.modal').length == 0) return true; $(this).parents('.modal').modal('hide');return false;",
					]
				) ?>
			<?php endif; ?>
			<?= Html::submitButton($submitButton['label'], $submitButton['options']) ?>
		</div>
	</div>
	
<?php ActiveForm::end(); ?>

<script>
	var map;
	var markers = [];

function initMap() {
	map = new google.maps.Map(document.getElementById('map'), {
		center: {lat: -12.0782893, lng: -76.9620044},
		zoom: 14
	});

	map.addListener('click', function(event){
		setMapOnAll(null);
		addMarker(event.latLng, map);

		$("[name=\"Event[latitude]\"]").val(event.latLng.lat());
		$("[name=\"Event[longitude]\"]").val(event.latLng.lng());
	});
}

function setMapOnAll(map) {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
	}
}

function addMarker(location, map) {
	// Add the marker at the clicked location, and add the next-available label
	// from the array of alphabetical characters.
	var marker = new google.maps.Marker({
		position: location,
		map: map
	});
	markers.push(marker);
}
</script>
