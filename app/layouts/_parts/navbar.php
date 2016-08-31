<?php 
use yii\widgets\Breadcrumbs;
use yii\helpers\Inflector;
use app\layouts\event\LayoutEvent;
use yii\bootstrap\NavBar;
use app\layouts\widgets\Nav;

$layoutEvent = new LayoutEvent();
Yii::$app->trigger(LayoutEvent::PREPARE_MENU, $layoutEvent);

$displayAlert = 'display:none';
$items =[];
if (!Yii::$app->user->isGuest){

	$userRole = \orm\admin\UserAdminRole::find()
		->select(['roleAdminId'])
		->where(['userAdminId' => Yii::$app->user->id])
		->andWhere(['roleAdminId' => 2])
		->asArray()
		->one();

	$items = [];

}

NavBar::begin([
	'brandLabel' => '<img alt="Brand" src="/img/isotipo.jpg">',
	'options' => [
		'class' => 'navbar navbar-inverse',
	],
	'innerContainerOptions' => [
		'class' => 'container-fluid',
	],
	'renderInnerContainer' => true,
	
]);
echo Nav::widget([
	'items' => $layoutEvent->menuModules,
	'activateItems' => true,
	'activateParents' => true,
	'options' => [
		'class' => 'nav navbar-nav'
	]
]);

echo Nav::widget([
	'items' => $layoutEvent->menu,
	'activateItems' => true,
	'activateParents' => true,
	'options' => [
		'class' => 'nav navbar-nav'
	]
]);
echo Nav::widget([
	'items' => $layoutEvent->rightMenu,
	'activateItems' => true,
	'activateParents' => true,
	'options' => [
		'class' => 'nav navbar-nav navbar-right'
	]
]);
echo Nav::widget([
	'encodeLabels' => false,
	'items' => ['modulePicker' => [
									'label' => '<span class="glyphicon glyphicon-warning-sign" style="color:orange;"></span>',
									'items' => $items,
									]],

	'options' => [
		'class' => 'nav navbar-nav navbar-right',
		'style' => $displayAlert,
	]
]);
NavBar::end();
?>

<div class="layout-bcrumbs">
	<?php 
	$bCrumbs = isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [];
	$mod = Yii::$app->controller->module;
	$parentBCrumbs = [];
	$path = "./";
	if (!isset($this->params['attachParentBreadcrumbs']) || $this->params['attachParentBreadcrumbs'])
	{
		while ($mod && $mod->module && !($mod instanceof \yii\web\Application))
		{
			if ($mod->id == 'admin' && $mod->module instanceof \yii\web\Application) 
			{
				break;
			}
			$parentBCrumbs[] = [
				'label' => isset($mod->title) ? $mod->title : Inflector::camel2words($mod->id),
				'url' => [$path],
			];
			$mod = $mod->module;
			$path .= "../";
		}
	}
	$parentBCrumbs = array_reverse($parentBCrumbs);
	?>
	<?= Breadcrumbs::widget(['links' => array_merge($parentBCrumbs, $bCrumbs)]) ?>
</div>
