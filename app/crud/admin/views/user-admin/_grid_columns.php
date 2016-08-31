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
    'controller' => 'user-admin',
],
'username' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'username',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'displayName' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'displayName',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'secretToken' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'secretToken',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'authToken' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'authToken',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'userPassword' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'userPassword',
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
'created_at' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'created_at',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'created_by' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'created_by',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'updated_at' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'updated_at',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'updated_by' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'updated_by',
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
		'username' => $allColumns['username'],
		'displayName' => $allColumns['displayName'],
// 		'secretToken' => $allColumns['secretToken'],
// 		'authToken' => $allColumns['authToken'],
// 		'userPassword' => $allColumns['userPassword'],
		'active' => $allColumns['active'],
// 		'created_at' => $allColumns['created_at'],
// 		'created_by' => $allColumns['created_by'],
// 		'updated_at' => $allColumns['updated_at'],
// 		'updated_by' => $allColumns['updated_by'],
	
	],
];
