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
    'controller' => 'role-rest-permit',
],
'roleRestId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'roleRestId',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'priority' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'priority',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'regex' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'regex',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'type' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'type',
    'format' => 'text',
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
		'roleRestId' => $allColumns['roleRestId'],
		'priority' => $allColumns['priority'],
		'regex' => $allColumns['regex'],
		'type' => $allColumns['type'],
// 		'created_at' => $allColumns['created_at'],
// 		'created_by' => $allColumns['created_by'],
// 		'updated_at' => $allColumns['updated_at'],
// 		'updated_by' => $allColumns['updated_by'],
	
	],
];
