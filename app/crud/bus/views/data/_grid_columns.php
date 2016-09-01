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
    'controller' => 'data',
],
'idSmartNode' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'idSmartNode',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'moveDate' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'moveDate',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'velocity' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'velocity',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'isTraffic' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'isTraffic',
    'format' => 'boolean',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'idSmartNode0' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'idSmartNode0.latitude',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Id Smart Node0',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'idSmartNode' => $allColumns['idSmartNode'],
		'moveDate' => $allColumns['moveDate'],
		'velocity' => $allColumns['velocity'],
		'isTraffic' => $allColumns['isTraffic'],
	
	],
];
