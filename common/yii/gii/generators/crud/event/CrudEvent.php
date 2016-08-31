<?php
namespace common\yii\gii\generators\crud\event;

use Yii;
use yii\base\Event;
use common\yii\helpers\AppHelper;

class CrudEvent extends Event
{
	const RENDER_INDEX_BUTTONS = 'crud_layout_render_index_buttons';
	const RENDER_INDEX_BEFORE_GRID = 'crud_layout_render_index_before_grid';
	const RENDER_INDEX_AFTER_GRID = 'crud_layout_render_index_after_grid';
	
	const RENDER_VIEW_BUTTONS = 'crud_layout_render_view_buttons';
	const RENDER_VIEW_TABS_BEFORE = 'crud_layout_render_view_tabs_before';
	const RENDER_VIEW_TABS_AFTER = 'crud_layout_render_view_tabs_after';
	const RENDER_VIEW_RELATION_BEFORE = 'crud_layout_render_view_relation_before';
	const RENDER_VIEW_RELATION_AFTER = 'crud_layout_render_view_relation_after';

	const FILTER_TABS = 'crud_layout_filter_tabs';
	const FILTER_GRID_COLUMNS = 'crud_layout_filter_grid_columns';
	const FILTER_INDEX_PAGE_LINKS = 'crud_layout_filter_index_page_links';
	const RENDER_INDEX_PAGE_BEFORE = 'crud_layout_render_index_page_before';
	const RENDER_INDEX_PAGE_AFTER = 'crud_layout_render_index_page_after';
	
	public $model = null;
	
	public $modelClass = null;
	
	public $relatedClass = null;
	
	public $dataProvider = null;
	
	public $columns = null;
	
	public $tabs = null;
	
	public $moduleId = null;
	
	public $links = null;
	
	public $requestedModuleId = null;
	
	public function __construct($config = [])
	{
		$this->requestedModuleId = \Yii::$app->controller->module->getUniqueId();
		
		parent::__construct($config);
		if ($this->model !== null)
			$this->modelClass = get_class($this->model);
	}
}