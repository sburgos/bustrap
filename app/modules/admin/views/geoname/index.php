<?php
use common\yii\helpers\Html;
use yii\helpers\Url;
use common\yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model orm\admin\Country */

$this->title = 'GeoNames ' . 'Country' . ": " . $country->name;
$this->params['breadcrumbs'][] = ['label' => 'Countries', 'url' => ['/admin/crud/country']];
$this->params['breadcrumbs'][] = ['label' => $country->name, 'url' => ['/admin/crud/country/view', 'id' => $country->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<h1>Cargar GeoNames (Regiones y Ciudades) de <?= Html::encode($country->name); ?></h1>

<p class="lead">
	Permite cargar las regiones y ciudades usando la lista de 
	<a href="http://www.geonames.org/" target="_blank">Geo Names</a>.
	No se eliminará nada existente. Solo se agregarán las regiones o ciudades
	nuevas y se actualizarán los nombres de las existentes. Si alguna ciudad
	o región no se encuentra en la nueva lista de geonames entonces solo se
	notificará para una eliminación manual.
</p>

<h2>Instrucciones</h2>

<div class="paso1">
	<h4>1. Descarga el archivo geoname desde http://download.geonames.org/export/dump/<?= $country->id ?>.zip</h4>
	<?php if ($fileExists) : ?>
		<p class='lead text-warning'>
			Existe una version de este archivo del <?php echo $fileLastModif; ?>.
		</p>
	<?php endif; ?>
	<button type="button" class="btn btn-primary" onclick="doDownload();">Descargar<?php if ($fileExists) echo " nueva versión"; ?></button>
	<span class="fa fa-refresh fa-spin fa-3x" style="display:none;"></span>
</div>

<div class="paso2" style="<?php if (!$fileExists) echo "display:none;"; ?>">
	<h4>2. Descomprime y parsea el archivo para ver las diferencias</h4>
	<button type="button" class="btn btn-primary" onclick="doParseFile();">Procesar archivo</button>
	<span class="fa fa-refresh fa-spin fa-3x" style="display:none;"></span>
</div>

<div class="paso3" style="display:none;">
	<h4>3. Persiste los cambios en la base de datos</h4>
	<button type="button" class="btn btn-primary" onclick="doSubmit();">Aplicar cambios</button>
	<span class="fa fa-refresh fa-spin fa-3x" style="display:none;"></span>
</div>

<div class="paso4" style="display:none;">
	<h4>LISTO!!</h4>
</div>

<script>
function doDownload()
{
	$(".paso1 button").hide();
	$(".paso1 .fa-refresh").show();
	$.ajax({
		url:"<?= Url::toRoute(['download', 'id' => $country->id]) ?>",
		dataType:'json',
		success:function(res){
			$(".paso1 .fa-refresh").hide();
			if (!res.ok)
			{
				console.log(res);
				alert("Error downloading file");
				return;
			}
			$(".paso2").show();
		},
		error:function(res){
			$(".paso1 .fa-refresh").hide();
			console.log(res);
			alert("Error downloading file");
		}
	});
}
function doParseFile()
{
	$(".paso2 button").hide();
	$(".paso2 .fa-refresh").show();
	$.ajax({
		url:"<?= Url::toRoute(['parse', 'id' => $country->id]) ?>",
		dataType:'json',
		success:function(res){
			$(".paso2 .fa-refresh").hide();
			if (!res.ok)
			{
				alert(res.error);
				return;
			}
			$(".paso3").show();
			$(".paso2").append($("<div class='tblres' style='max-height:500px;overflow:auto;'>" + res.result + "</div>"));
		},
		error:function(res){
			$(".paso2 .fa-refresh").hide();
			console.log(res);
			alert("Error parsing file");
		}
	});
}
function doSubmit()
{
	$(".paso3 button").hide();
	$(".paso3 .fa-refresh").show();
	$.ajax({
		url:"<?= Url::toRoute(['apply', 'id' => $country->id]) ?>",
		dataType:'json',
		data:$(".paso2 input").serialize(),
		type:'post',
		success:function(res){
			$(".paso3 .fa-refresh").hide();
			if (!res.ok)
			{
				alert(res.error);
				return;
			}
			$(".paso1,.paso2,.paso3").hide();
			$(".paso4").show();
		},
		error:function(res){
			$(".paso3 .fa-refresh").hide();
			console.log(res);
			alert("Error applying changes");
		}
	});
}
</script>