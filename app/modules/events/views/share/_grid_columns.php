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
    'controller' => 'share',
],
'priceId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'priceId',
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
'amount' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'amount',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'price' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'price.name',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Price',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'priceId' => $allColumns['price'],
//		'priceId' => $allColumns['priceId'],
		'name' => $allColumns['name'],
		'amount' => $allColumns['amount'],
	
	],
];
