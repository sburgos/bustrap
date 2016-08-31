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
    'controller' => 'discount',
],
'code' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'code',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'discount' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'discount',
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
		'code' => $allColumns['code'],
		'discount' => $allColumns['discount'],
		'toDate' => $allColumns['toDate'],
		'fromDate' => $allColumns['fromDate'],
		'eventId' => $allColumns['event'],
//		'eventId' => $allColumns['eventId'],
	
	],
];
