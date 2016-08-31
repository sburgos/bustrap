<?php
$allColumns = [
'userAdminId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'userAdminId',
    'format' => 'text',
    'isPrimaryKey' => true,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'controller' => 'user-admin-role',
],
'roleAdminId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'roleAdminId',
    'format' => 'text',
    'isPrimaryKey' => true,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'controller' => 'user-admin-role',
],
];

return [
	'all' => $allColumns,
	'active' => [
		'userAdminId_PK' => $allColumns['userAdminId'],
		'userAdminId' => $allColumns['userAdminId'],
		'roleAdminId_PK' => $allColumns['roleAdminId'],
		'roleAdminId' => $allColumns['roleAdminId'],
	
	],
];
