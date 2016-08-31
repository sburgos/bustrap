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
    'controller' => 'ticket-log',
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
'title' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'title',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'message' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'message',
    'format' => 'ntext',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'rowDate' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'rowDate',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
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
		'title' => $allColumns['title'],
// 		'message' => $allColumns['message'],
		'rowDate' => $allColumns['rowDate'],
	
	],
];
