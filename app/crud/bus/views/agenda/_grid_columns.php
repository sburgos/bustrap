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
    'controller' => 'agenda',
],
'titulo' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'titulo',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'descripcion' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'descripcion',
    'format' => 'ntext',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'lugar' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'lugar',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'desdeDate' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'desdeDate',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'hastaDate' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'hastaDate',
    'format' => 'text',
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
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'titulo' => $allColumns['titulo'],
// 		'descripcion' => $allColumns['descripcion'],
		'lugar' => $allColumns['lugar'],
		'desdeDate' => $allColumns['desdeDate'],
		'hastaDate' => $allColumns['hastaDate'],
		'active' => $allColumns['active'],
	
	],
];
