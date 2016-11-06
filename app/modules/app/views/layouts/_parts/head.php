<?php
use yii\helpers\Html;
?>
<meta charset="<?= Yii::$app->charset ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<link type="image/vnd.microsoft.icon" href="/favicon.ico" rel="shortcut icon">
<?= Html::csrfMetaTags()?>
<title><?= Html::encode($this->title) ?></title>
<?php $this->head() ?>