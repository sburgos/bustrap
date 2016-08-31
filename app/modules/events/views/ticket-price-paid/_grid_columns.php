<?php
$allColumns = [
'ticketPriceId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'ticketPriceId',
    'format' => 'text',
    'isPrimaryKey' => true,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'controller' => 'ticket-price-paid',
],
'amount' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'amount',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'sharesNumber' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'sharesNumber',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'currency' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'currency',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'ticketPrice' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'ticketPrice.name',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Ticket Price',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'ticketPriceId_PK' => $allColumns['ticketPriceId'],
		'ticketPriceId' => $allColumns['ticketPrice'],
//		'ticketPriceId' => $allColumns['ticketPriceId'],
		'amount' => $allColumns['amount'],
		'sharesNumber' => $allColumns['sharesNumber'],
		'currency' => $allColumns['currency'],
	
	],
];
