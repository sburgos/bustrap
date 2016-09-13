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
    'controller' => 'route',
],
'lineId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'lineId',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'latitude' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'latitude',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'longitude' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'longitude',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'ida' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'ida',
    'format' => 'boolean',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'line' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'line.name',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Line',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'lineId' => $allColumns['line'],
//		'lineId' => $allColumns['lineId'],
		'latitude' => $allColumns['latitude'],
		'longitude' => $allColumns['longitude'],
		'ida' => $allColumns['ida'],
	
	],
];
