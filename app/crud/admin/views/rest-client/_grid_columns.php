<?php
$allColumns = [
'id' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'id',
    'format' => 'text',
    'isPrimaryKey' => true,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
    'controller' => 'rest-client',
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
'platform' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'platform',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'version' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'version',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'sendSiftFingerprint' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'sendSiftFingerprint',
    'format' => 'boolean',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
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
'ownerName' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'ownerName',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'allowCreditCards' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'allowCreditCards',
    'format' => 'boolean',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'allowStoredCreditCards' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'allowStoredCreditCards',
    'format' => 'boolean',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'allowPaypal' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'allowPaypal',
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
// 		'secretToken' => $allColumns['secretToken'],
		'displayName' => $allColumns['displayName'],
		'platform' => $allColumns['platform'],
		'version' => $allColumns['version'],
		'sendSiftFingerprint' => $allColumns['sendSiftFingerprint'],
		'active' => $allColumns['active'],
		'ownerName' => $allColumns['ownerName'],
		'allowCreditCards' => $allColumns['allowCreditCards'],
		'allowStoredCreditCards' => $allColumns['allowStoredCreditCards'],
		'allowPaypal' => $allColumns['allowPaypal'],
// 		'created_at' => $allColumns['created_at'],
// 		'created_by' => $allColumns['created_by'],
// 		'updated_at' => $allColumns['updated_at'],
// 		'updated_by' => $allColumns['updated_by'],
	
	],
];
