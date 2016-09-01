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
    'controller' => 'smart-node',
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
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'latitude' => $allColumns['latitude'],
		'longitude' => $allColumns['longitude'],
	
	],
];
