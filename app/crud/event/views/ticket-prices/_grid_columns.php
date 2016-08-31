<?php
$allColumns = [
'id' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'id',
    'format' => 'text',
    'isPrimaryKey' => true,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'controller' => 'ticket-prices',
],
'ticketId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'ticketId',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'priceId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'priceId',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'priceName' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'priceName',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'shareId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'shareId',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'shareName' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'shareName',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
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
'paid' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'paid',
    'format' => 'boolean',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'ticket' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'ticket.sellingDate',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Ticket',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'ticketId' => $allColumns['ticket'],
//		'ticketId' => $allColumns['ticketId'],
		'priceId' => $allColumns['priceId'],
		'priceName' => $allColumns['priceName'],
		'shareId' => $allColumns['shareId'],
		'shareName' => $allColumns['shareName'],
		'currency' => $allColumns['currency'],
		'amount' => $allColumns['amount'],
		'paid' => $allColumns['paid'],
	
	],
];
