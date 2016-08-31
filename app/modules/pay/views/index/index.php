<div class="col-xs-12 col-md-9">
	<div>
		<a href="/pay/shares?id=<?= $event->id ?>" type="submit" class="btn btn-block btn-info" style="font-size:20px;">
			<span class="glyphicon glyphicon-ok"></span>&nbsp;&nbsp;&nbsp;
			Quiero pagar cuotas
		</a>
	</div>
	<form id="prices-form" action="" method="post">
		<?= \common\yii\helpers\Html::csrfMetaTags() ?>
		<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
		<div class="col-xs-12 step-one main-content">
			<div class="col-xs-12 messages-container"></div>
			<div>
				<div class="col-xs-12">
					<div id="seats" class="item-category col-xs-12">
						<div class="col-xs-12"><h1>Precios <small>Elige la modalidad de pago que desees</small></h1></div>
						<br clear="all"/>
						<div>
							<?php
								foreach($prices as $price):
									$moneda = 'S/.';
									if($price['currency'] == 'dolares')
										$moneda = 'USD$';
							?>
							<div class="radio">
								<label>
									<input type="radio" name="radio" value="<?= $price['shares'][0]['id']; ?>">
									<strong style="font-size: 16px;"><?= $price['name'] ?></strong> - <?=$moneda ?> <?= $price['shares'][0]['amount'] ?>
									<br/><small><i><?= $price['description']; ?></i></small>
								</label>
							</div>
							<?php
								endforeach;
							?>
						</div>
					</div>
				</div>
				<div class="item-category extra-area col-xs-12 "></div>
			</div>
			<div class="user-data col-xs-12">
				<?php
					if( ! empty($error)):
				?>
					<div class="alert alert-danger" role="alert"><?= $error; ?></div>
				<?php
					endif;
				?>
				<div class="heading-title">Información del cliente</div>
				<div class="field-wrapper form-group col-xs-12 ">
					<label>
						<span class="label-text">Nombre:</span>
						<input name="nombre" type="text" class="form-control extra-field">
					</label>
				</div>
				<div class="field-wrapper form-group col-xs-12 ">
					<label>
						<span class="label-text">Correo:</span>
						<input name="correo" type="email" class="form-control extra-field">
					</label>
				</div>
				<div class="field-wrapper form-group col-xs-12">
					<label>
						<span class="label-text">Teléfono:</span>
						<input name="documento"  type="text" class="form-control extra-field">
					</label>
				</div>
			</div>
			<div class="first-step buttonbar cp-button-group ">
				<div class="col-xs-5 col-left">
					<div class="btn-group btn-group-justified">
						<a tabindex="-1" data-toggle="modal" data-target="#cancelModal" class="btn btn-default btn-block" onclick="window.history.back();">
							<span class="glyphicon glyphicon-remove"></span>
							Cancelar
						</a>
					</div>
				</div>
				<div class="col-xs-7 col-right">
					<button type="submit" class="btn btn-block btn-primary">
						Siguiente
						<span class="glyphicon glyphicon-chevron-right"></span>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="col-xs-12 col-md-3">
	<div class="movie-section col-xs-12 movie-banner-content main-content">
		<div class="movie-details col-xs-12">
			<figure class="movie-image-container"><img class="image-responsive movie-image" src="<?= $event->image; ?>">

				<div class="movie-description"><h1><?= $event->name; ?></h1>

					<table class="purchase-detail">
						<tbody>
						<tr class="desc-text-item">
							<td class="desc-text-col desc-text-bold" colspan="2">Descripción</td>
						</tr>
						<tr class="desc-text-item">
							<td class="desc-text-col" colspan="2"><?= $event->description ?></td>
						</tr>
						<tr class="desc-text-item">
							<td class="desc-text-col desc-text-bold">Desde</td>
							<td class="desc-text-col"><?= $event->toDate ?></td>
						</tr>
						<tr class="desc-text-item">
							<td class="desc-text-col desc-text-bold">Hasta</td>
							<td class="desc-text-col"><?= $event->fromDate ?></td>
						</tr>
						<tr class="desc-text-item">
							<td class="desc-text-col desc-text-bold">&nbsp;</td>
							<td class="desc-text-col"></td>
						</tr>
						<tr class="desc-text-item">
							<td class="desc-text-col desc-text-bold" colspan="2">Organizador:</td>
						</tr>
						<tr class="desc-text-item">
							<td class="desc-text-col desc-text-bold">Nombre:</td>
							<td class="desc-text-col"><?= $event->manager->name; ?></td>
						</tr>
						<tr class="desc-text-item">
							<td class="desc-text-col desc-text-bold">Teléfono:</td>
							<td class="desc-text-col"><?= $event->manager->phone; ?></td>
						</tr>
						<tr class="desc-text-item">
							<td class="desc-text-col desc-text-bold">Correo:</td>
							<td class="desc-text-col"><?= $event->manager->email; ?></td>
						</tr>
						</tbody>
					</table>
				</div>
			</figure>
		</div>
	</div>
</div>