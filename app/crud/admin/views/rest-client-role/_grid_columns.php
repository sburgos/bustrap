<?php
$allColumns = [
'restClientId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'restClientId',
    'format' => 'text',
    'isPrimaryKey' => true,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
    'controller' => 'rest-client-role',
],
'roleRestId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'roleRestId',
    'format' => 'text',
    'isPrimaryKey' => true,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'controller' => 'rest-client-role',
],
];

return [
	'all' => $allColumns,
	'active' => [
		'restClientId_PK' => $allColumns['restClientId'],
		'restClientId' => $allColumns['restClientId'],
		'roleRestId_PK' => $allColumns['roleRestId'],
		'roleRestId' => $allColumns['roleRestId'],
	
	],
];
