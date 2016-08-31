<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use common\assets\FontAwesomeAsset;
use common\yii\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */
\app\assets\PayAsset::register($this);

// Get default layout config
$headerView = '_parts/header.php';
$footerView = '_parts/footer.php';
$showHeader = true;
$showFooter = true;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta name="msapplication-config" content="none"/>
	<?= $this->render('_parts/head'); ?>
</head>
<body class="pay">
<?php $this->beginBody() ?>
<?php if ($showHeader) echo $this->render($headerView) ?>
<?= isset($this->blocks['preContent']) ? $this->blocks['preContent'] : ''; ?>
<div class="site-content">
	<div class="container">
		<div class="row">
			<div>
				<?= $content ?>
			</div>
		</div>
	</div>
</div>
<?php if ($showFooter) echo $this->render($footerView) ?>
<?php $this->endBody() ?>
<?= isset($this->blocks['inlineScript']) ? $this->blocks['inlineScript'] : ''; ?>

</body>
</html>
<?php $this->endPage() ?>
