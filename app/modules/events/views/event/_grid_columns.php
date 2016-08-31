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
    'controller' => 'event',
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
'latitude' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'latitude',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'longitude' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'longitude',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'stock' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'stock',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'toDate' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'toDate',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'fromDate' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'fromDate',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'image' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'image',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'description' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'description',
    'format' => 'ntext',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'managerId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'managerId',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'realUrl' =>[
    'format' => 'raw',
    'attribute' => 'URL',
    'value' => function($model)
    {
        return '<a href="/pay?id='.$model->id.'" target="_blank">/pay?id='.$model->id.'</a>';
    }
],
'ticketText' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'ticketText',
    'format' => 'ntext',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'manager' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'manager.name',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Manager',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'name' => $allColumns['name'],
		'latitude' => $allColumns['latitude'],
		'longitude' => $allColumns['longitude'],
		'stock' => $allColumns['stock'],
		'toDate' => $allColumns['toDate'],
		'fromDate' => $allColumns['fromDate'],
//		'image' => $allColumns['image'],
        'realUrl' => $allColumns['realUrl'],
// 		'description' => $allColumns['description'],
		'managerId' => $allColumns['manager'],
//		'managerId' => $allColumns['managerId'],
// 		'ticketText' => $allColumns['ticketText'],
	
	],
];
