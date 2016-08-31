<?php
namespace common\yii\filters;

use yii\filters\ContentNegotiator as YiiContentNegotiator;
use yii\web\Request;
use yii\web\Response;
use yii\helpers\StringHelper;

class ContentNegotiator extends YiiContentNegotiator
{
	public $except = [];
	
	/**
	 * @param Request $request
     * @param Response $response
     * 
     * (non-PHPdoc)
	 * @see \yii\filters\ContentNegotiator::negotiateContentType()
	 */
	protected function negotiateContentType($request, $response)
	{
		if (!empty($this->except))
		{
			$uniqueId = "/" . ltrim(strtolower($request->url), "/");
			foreach ($this->except as $keyId)
			{
				if (StringHelper::startsWith($request->url, $keyId))
					return;
			}
		}
		return parent::negotiateContentType($request, $response);
	}
}