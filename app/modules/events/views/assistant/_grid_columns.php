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
    'controller' => 'assistant',
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
'email' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'email',
    'format' => 'email',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'dni' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'dni',
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
		'name' => $allColumns['name'],
		'email' => $allColumns['email'],
		'dni' => $allColumns['dni'],
		'eventId' => $allColumns['event'],
//		'eventId' => $allColumns['eventId'],
		'status' => $allColumns['status'],
	
	],
];
