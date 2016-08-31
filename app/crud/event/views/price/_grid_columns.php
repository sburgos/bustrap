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
    'controller' => 'price',
],
'eventId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'eventId',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
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
'description' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'description',
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
'stock' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'stock',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'toDate' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'toDate',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'fromDate' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'fromDate',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'event' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'event.name',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Event',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'eventId' => $allColumns['event'],
//		'eventId' => $allColumns['eventId'],
		'name' => $allColumns['name'],
		'description' => $allColumns['description'],
		'currency' => $allColumns['currency'],
		'stock' => $allColumns['stock'],
		'toDate' => $allColumns['toDate'],
		'fromDate' => $allColumns['fromDate'],
	
	],
];
