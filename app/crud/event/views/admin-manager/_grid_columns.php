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
    'controller' => 'admin-manager',
],
'manageId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'manageId',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'adminId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'adminId',
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
'manage' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'manage.name',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Manage',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'manageId' => $allColumns['manage'],
//		'manageId' => $allColumns['manageId'],
		'adminId' => $allColumns['adminId'],
		'status' => $allColumns['status'],
	
	],
];
