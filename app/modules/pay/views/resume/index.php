<?php
$fontSize = "12px";
$fontFamily = "Verdana";
$singleColumn = false;

?>

<div class="col-xs-12 col-md-9">
	<div class="col-xs-12 step-one main-content">
		<form id="prices-form" action="" method="post">
			<?= \common\yii\helpers\Html::csrfMetaTags() ?>
			<input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
			<div class="col-xs-12 messages-container"></div>
			<h1>Ha reservado su ticket</h1>
			<hr />
			<table style='font-size:<?= $fontSize ?>;width:100%;border-collapse:collapse;font-family:<?= $fontFamily ?>;'>
				<tr>
					<td>
						<div style='padding-bottom:3px;'>
							<strong>Evento:</strong> <?= $event->name; ?>
						</div>
						<div style='padding-bottom:3px;'>
							<strong>Desde:</strong> <?= $event->toDate; ?>
						</div>
						<div style='padding-bottom:3px;'>
							<strong>Hasta:</strong> <?= $event->fromDate; ?>
						</div>
						<div style='padding-bottom:3px;'>
							<strong>Descripción:</strong> <br/><?= $event->description; ?>
						</div>
					</td>
				</tr>
				<tr>
					<td style="padding-top:10px;">
						<table style="width:100%;border-collapse:collapse;font-size:<?= $fontSize ?>;font-family:<?= $fontFamily ?>;">
							<tr>
								<th style="width:80%;background-color:#eee;padding:5px;border-bottom:1px solid #ccc;">Precio</th>
								<th style="background-color:#eee;padding:5px;text-align:right;border-bottom:1px solid #ccc;">Valor</th>
							</tr>
							<tr>
								<td><?= $ticket['ticketPrices'][0]['priceName'] ?> (<?= $ticket['ticketPrices'][0]['shareName'] ?>)</td>
								<?php
									$moneda = 'S/.';
									if($ticket['ticketPrices'][0]['currency'] == 'dolares')
										$moneda = 'USD$';
								?>
								<td style="text-align:right;"><?=$moneda ?> <?= $ticket['ticketPrices'][0]['amount'] ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br/>
			<?php
			if( ! empty($error)):
				?>
				<div class="alert alert-danger" role="alert"><?= $error; ?></div>
				<?php
			endif;
			?>
			<label>
				<input type="checkbox" name="tys" id="tys" /> Acepto los términos y condiciones de compra
			</label>
			<button type="button" class="btn btn-block btn-primary" id="btn_pago">
				<span class="glyphicon glyphicon-usd"></span>
				Pagar con culqi
			</button>
		</form>
		<br clear="all"/>
	</div>
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
<!-- Aquí configuramos el botón de pago de Culqi. -->
<script>
	// Código del comercio
	checkout.codigo_comercio = '<?= $data['codigo_comercio'] ?>';
	// La informacion_venta es el contenido del parámetro que recibiste en la creación de la venta.
	checkout.informacion_venta = '<?= $data['informacion_venta'] ?>';
	checkout._csrf = "<?=Yii::$app->request->getCsrfToken()?>";
	// Activa el botón de pago, al darle click mostrará el formulario de pago
	document.getElementById('btn_pago').addEventListener('click', function (e) {
		if(document.getElementById('tys').checked)
		{
			checkout.abrir();
		}
		else {
			alert("Debe aceptar los términos y condiciones.");
		}
		e.preventDefault();
	});
	// Esta función es llamada al terminar el proceso de pago.
	// Debe de ser usada siempre, para poder obtener la respuesta.
	function culqi(checkout)
	{
		// Aquí recibes la respuesta del formulario de pago.
		// Si el usuario cierra el formulario de pago: checkout.respuesta tendrá en valor "checkout_cerrado"
		console.log(checkout.respuesta);
		// Cierra el formulario de pago de Culqi.
		checkout.cerrar();
		// Envía la respuesta cifrada que recibiste del formulario de Culqi a tu
		// servidor para descifrarlo, tu servidor lo descifra con la librería
		// de culqi y con esos datos muestra la vista de venta realizada
		var json = JSON.stringify({
			informacionDeVentaCifrada: checkout.respuesta
		});
		post('/pay/proccess?ticket=<?= $ticket['id'] ?>', json);
	};
</script>