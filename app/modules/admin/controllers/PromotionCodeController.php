<?php
namespace modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\StringHelper;
use modules\admin\models\PromotionCodeGenerator;
use orm\admin\PromotionCode;

class PromotionCodeController extends Controller
{
	/**
	 * Generate multiple codes at once
	 *
	 * @return Ambigous <string, string>
	 */
	public function actionGenerator()
	{
		$model = new PromotionCodeGenerator();
		$model->loadDefaultValues();
		
		if (Yii::$app->request->isPost)
		{
			
			$post = Yii::$app->request->post();
			$model->id = "000";
			if ($model->load($post) && $model->validate() && isset($_POST['codes']))
			{
				$codesGenerated = [];
				foreach ($_POST['codes'] as $codeNumber)
				{
					$promoCode = new PromotionCode($model->toArray());
					$promoCode->id = $codeNumber;
					try {
						if ($promoCode->save())
							$codesGenerated[] = $promoCode->id;
					}
					catch (\Exception $ex) {
						
					}
				}
				return $this->render('generator', [
					'model' => $model,
					'codes' => $codesGenerated,
				]);
			}
			else
			{
				return $this->render('generator', [
					'model' => $model,
					'errors' => $model->getErrors()
				]);
			}
		}
		return $this->render('generator', [
			'model' => $model
		]);
	}
}
