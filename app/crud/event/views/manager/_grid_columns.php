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
    'controller' => 'manager',
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
'phone' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'phone',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'address' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'address',
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
		'name' => $allColumns['name'],
		'email' => $allColumns['email'],
		'phone' => $allColumns['phone'],
		'address' => $allColumns['address'],
	
	],
];
