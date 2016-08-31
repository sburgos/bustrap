<?php
use yii\helpers\Inflector;
use common\yii\helpers\StringHelper;

/* @var $generator \common\yii\gii\generators\crud\Generator */

$relationsToShow = [];
foreach ($relations as $relationId => $relation)
{
	if ($relation['type'] == 'hasOne')
	{
		if ($relation['oneToOne'])
		{
			$relationsToShow[$relationId] = $relation;
		}
		continue;
	}
	if (!$relation['hasMany']) continue;
	if (isset($relation['viaTable'])) 
		continue;
	$relationsToShow[$relationId] = $relation;
}
if (count($relationsToShow) == 0) return;

echo "<?php\n";
?>

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use common\yii\gii\generators\crud\event\CrudEvent;

<?= 
"//\n// List of tabs to display\n".
"//----------------------------------------------------------------------------\n"
?>
$tabs = [
	'view' => [ 'label' => <?= $generator->generateString('Detail'); ?>, 'active' => false, 'visible' => true, 'url' => Url::toRoute(array_merge(['view'], $_GET))],
<?php foreach ($relationsToShow as $relationName => $relation) : /*if (!$relation['hasMany'] || isset($relation['viaTable'])) continue;*/ ?>
	'view-<?= Inflector::camel2id($relationName); ?>' => ['label' => <?= $generator->generateString("Tab: " . Inflector::camel2words(StringHelper::basename($generator->modelClass)) . ": " . Inflector::camel2words($relationName)); ?>, 'active' => false, 'visible' => true, 'url' => Url::toRoute(array_merge(['view-<?= Inflector::camel2id($relationName); ?>'], $_GET))],
<?php endforeach; ?>
];
foreach ($tabs as $tabId => $tabInfo) {
	$tabs[$tabId]['active'] = \Yii::$app->requestedAction->id == $tabId || (\Yii::$app->requestedAction->id == "update" && $tabId == "view");
	$tabs[$tabId]['visible'] = (\Yii::$app->user->can($tabInfo['url']));
}

//
// Trigger the filter tabs before rendering
//--------------------------------------------------------------------------
$event = new CrudEvent([
	'model' => $model, 
	'tabs' => $tabs,
]);
Yii::$app->trigger(CrudEvent::FILTER_TABS, $event);

<?= 
"//\n// Tab items\n".
"//----------------------------------------------------------------------------\n".
"?>\n" ?>
<ul class="nav nav-tabs crud-nav-tabs" role="tablist">
	<?= "<?php Yii::\$app->trigger(CrudEvent::RENDER_VIEW_TABS_BEFORE, new CrudEvent(['model' => \$model])); ?>\n"; ?>
	<?= '<?php foreach ($event->tabs as $tabId => $tabInfo) : ?>' . "\n"; ?>
	<?= '<?php if (isset($tabInfo[\'visible\']) && !$tabInfo[\'visible\']) continue; ?>' . "\n"; ?>
	<?= '<?php $class = (isset($tabInfo[\'active\']) && $tabInfo[\'active\']) ? "active" : ""; ?>' . "\n"; ?>
	<li class="<?= '<?= $class ?>' ?>">
		<a href="<?= '<?= $tabInfo[\'url\']; ?>' ?>"><?= '<?= $tabInfo[\'label\'] ?>' ?></a>
	</li>
	<?= '<?php endforeach; ?>' . "\n" ?>
	<?= "<?php Yii::\$app->trigger(CrudEvent::RENDER_VIEW_TABS_AFTER, new CrudEvent(['model' => \$model])); ?>\n"; ?>
</ul>
