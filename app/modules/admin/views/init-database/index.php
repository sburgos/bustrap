<?php

use yii\helpers\Url;
?>
<style>
ol.my-list li {
	padding-bottom:3px;
	float:left;
	margin-right:35px;
}
ol.my-list li .btn {
	padding:2px 8px;
	text-align:left;
}
</style>
<h1>Inicializar valores de la base de datos</h1>
<p class="lead">
	Puede ejecutar cualquiera de estos comandos en cualquier momento
	no se modificará la información existente.
</p>
<ol class="my-list">
	<li><a class="btn btn-primary" href="<?= Url::toRoute(['languages']); ?>">Agregar Idiomas</a></li>
	<li><a class="btn btn-primary" href="<?= Url::toRoute(['currencies']); ?>">Agregar Monedas</a></li>
	<li><a class="btn btn-primary" href="<?= Url::toRoute(['timezones']); ?>">Agregar Zonas horarias</a></li>
	<li><a class="btn btn-primary" href="<?= Url::toRoute(['countries']); ?>">Agregar Paises</a></li>
	<?php if (count($countries) > 0) : ?>
		<li>Cargar ubigeos de: 
			<ol>
				<?php foreach ($countries as $countryId => $country) : if ($countryId=='ZZ') continue; ?>
				<li><a class="btn btn-primary" href="<?= Url::toRoute(['import-ubigeos', 'countryId' => $countryId]); ?>"><?= $country['name']; ?></a></li>
				<?php endforeach; ?>
			</ol>
		</li>
	<?php endif; ?>
</ol>