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
    'controller' => 'smart-routes',
],
'idRoute' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'idRoute',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
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
'idRoute0' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'idRoute0.name',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Id Route0',
    'headerOptions' => [
        'style' => 'text-align:center;',
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
		'idRoute' => $allColumns['idRoute'],
		'idSmartNode' => $allColumns['idSmartNode'],
	
	],
];
