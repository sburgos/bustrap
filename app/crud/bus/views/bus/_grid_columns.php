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
    'controller' => 'bus',
],
'idLine' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'idLine',
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
'busImage' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'busImage',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'extraInfo' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'extraInfo',
    'format' => 'ntext',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'active' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'active',
    'format' => 'boolean',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'mode' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'mode',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'idLine0' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'idLine0.name',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Id Line0',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'idLine' => $allColumns['idLine'],
		'name' => $allColumns['name'],
		'busImage' => $allColumns['busImage'],
// 		'extraInfo' => $allColumns['extraInfo'],
		'active' => $allColumns['active'],
		'mode' => $allColumns['mode'],
	
	],
];
