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
    'controller' => 'ticket',
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
'asistantId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'asistantId',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'sellingDate' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'sellingDate',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'status' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'status',
    'format' => 'boolean',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'asistant' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'asistant.name',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Asistant',
    'headerOptions' => [
        'style' => 'text-align:center;',
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
		'asistantId' => $allColumns['asistant'],
//		'asistantId' => $allColumns['asistantId'],
		'sellingDate' => $allColumns['sellingDate'],
		'status' => $allColumns['status'],
	
	],
];
