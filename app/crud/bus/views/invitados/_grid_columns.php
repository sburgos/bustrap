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
    'controller' => 'invitados',
],
'nombre' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'nombre',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'correo' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'correo',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => '',
    ],
    'headerOptions' => [
        'style' => '',
    ],
],
'skype' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'skype',
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
'agendaId' => [
    'class' => 'common\\yii\\grid\\DataColumn',
    'attribute' => 'agendaId',
    'format' => 'text',
    'isPrimaryKey' => false,
    'contentOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
    'headerOptions' => [
        'style' => 'width:10px;text-align:center;',
    ],
],
'agenda' => [
    'class' => 'yii\\grid\\DataColumn',
    'attribute' => 'agenda.titulo',
    'format' => 'text',
    'contentOptions' => [
        'style' => 'text-align:left;',
    ],
    'header' => 'Agenda',
    'headerOptions' => [
        'style' => 'text-align:center;',
    ],
],
];

return [
	'all' => $allColumns,
	'active' => [
		'id' => $allColumns['id'],
		'nombre' => $allColumns['nombre'],
		'correo' => $allColumns['correo'],
		'skype' => $allColumns['skype'],
		'active' => $allColumns['active'],
		'agendaId' => $allColumns['agenda'],
//		'agendaId' => $allColumns['agendaId'],
	
	],
];
