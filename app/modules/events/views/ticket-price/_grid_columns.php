<?php
$allColumns = [
'ticketId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'ticketId',
    'format' => 'text',
    'isPrimaryKey' => true,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'controller' => 'ticket-price',
],
'name' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'name',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
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
'shares' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'shares',
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
'ticket' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'ticket.id',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Ticket',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
'ticketPricePa' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'ticketPricePa.currency',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Ticket Price Pa',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'ticketId_PK' => $allColumns['ticketId'],
		'ticketId' => $allColumns['ticket'],
//		'ticketId' => $allColumns['ticketId'],
		'name' => $allColumns['name'],
		'amount' => $allColumns['amount'],
		'shares' => $allColumns['shares'],
		'currency' => $allColumns['currency'],
	
	],
];
