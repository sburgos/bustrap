<?php
$fontSize = "12px";
$fontFamily = "Verdana";
$singleColumn = false;
?>
<div class="col-xs-12 col-md-9">
	<div class="col-xs-12 step-one main-content">
		<div class="col-xs-12 messages-container"></div>
		<h1>¡Su compra se realizó satisfactoriamente!</h1>
		<hr />
		<div style="margin:20px; background: #fff; padding: 20px;" id="ticket-print">
			<table style='font-size:<?= $fontSize ?>;width:100%;border-collapse:collapse;font-family:<?= $fontFamily ?>;'>
				<tr>
					<td>
						<table style='font-size:<?= $fontSize ?>;width:100%;border-collapse:collapse;font-family:<?= $fontFamily ?>;'>
							<tr>
								<td style='text-align:<?= ($singleColumn ? 'center' : 'left') ?>;vertical-align:top;'>
									<h2><?= $event->name; ?></h2>
									<div style="margin:15px; text-align: center;">
										<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $ticket['id'] ?>" />
										<div style='margin-bottom:5px;'><strong style='font-size:1.2em;'>Ticket N# <?= $ticket['id'] ?></strong></div>
									</div>
								</td>
							</tr>
						</table>
						<table style='font-size:<?= $fontSize ?>;width:100%;border-collapse:collapse;font-family:<?= $fontFamily ?>;'>
							<tr>
								<td>
									<hr style='border-bottom:0px;margin:10px 0px 10px 0px;'/>
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
					</td>
				</tr>
				<tr><td style=""></td></tr>
				<tr><td style=""><hr/></td></tr>
				<tr>
					<td style="font-size:18px;line-height:18px;">
						<strong>*Recuerda llevar este ticket impreso o en tu celular.</strong>
					</td>
				</tr>
				<tr>
					<td style="font-size:12px;line-height:18px;">
						<hr />
						<?= $ticket['event']['ticketText'] ?>
					</td>
				</tr>
			</table>
		</div>
		<button type="submit" class="btn btn-block btn-primary imprimir" onclick="window.print()">
			Imprimir Ticket
			<span class="glyphicon glyphicon-print"></span>
		</button>
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
<style>

	@media print {
		body * {
			visibility: hidden;
		}
		#ticket-print, #ticket-print * {
			visibility: visible;
		}
		#ticket-print {
			position: absolute;
			left: 0;
			top: 0;
		}
	}
</style>