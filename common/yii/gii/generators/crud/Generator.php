<?php
namespace common\yii\gii\generators\crud;

use yii\gii\generators\crud\Generator as YiiGenerator;
use yii\helpers\VarDumper;
use yii\helpers\Inflector;
use common\yii\helpers\SchemaHelper;
use common\yii\helpers\StringHelper;
use yii\helpers\FileHelper;
use yii\gii\CodeFile;
use yii\db\Schema;

class Generator extends YiiGenerator
{
	public $moduleID;
	public $modelClass = null;//"modules\\admin\\orm\\";
	public $enableI18N = true;
	public $messageCategory = 'crud';
	public $allClassesInNamespace = false;
	
	public function rules()
	{
		return array_merge(parent::rules(), [
			['moduleID', 'safe'],
			['allClassesInNamespace', 'safe'],
		]);
	}
	
	public function stickyAttributes()
	{
		return [];
	}
	
	/**
	 * Checks if model class is valid
	 */
	public function validateModelClass()
	{
		/* @var $class ActiveRecord */
		$class = $this->modelClass;
		$pk = $class::primaryKey();
		if (empty($pk)) {
			$this->addError('modelClass', "The table associated with $class must have primary key(s).");
		}
	}
	
	/**
	 * Get the view path for multiple modules
	 * 
	 * (non-PHPdoc)
	 * @see \yii\gii\generators\crud\Generator::getViewPath()
	 */
	public function getViewPath()
	{
		$path = str_replace("\\", "/", StringHelper::dirname(StringHelper::dirname($this->controllerClass)));
		return \Yii::getAlias("@{$path}/views/" . StringHelper::basename($this->controllerID));
	}
	
	/**
	 * Do default generator or generate all models in
	 * the same namespace
	 * 
	 * (non-PHPdoc)
	 * @see \yii\gii\generators\crud\Generator::generate()
	 */
	public function generate()
	{
		if (!$this->allClassesInNamespace)
			return parent::generate();
		
		$generatedFiles = [];
		
		// Generate all models
		$ns = StringHelper::dirname($this->modelClass);
		$origModelClass = $this->modelClass;
		$origSearchClass = $this->searchModelClass;
		$origControllerClass = $this->controllerClass;
		
		$nsParts = explode("\\", $ns);
		$nsAlias = array_shift($nsParts);
		$files = FileHelper::findFiles(\Yii::getAlias("@{$nsAlias}") . "/" . implode("/", $nsParts), ['recursive' => false]);
		$classes = [];
		foreach ($files as $file)
		{
			if (StringHelper::endsWith($file, ".php"))
				$classes[] = $ns . "\\" . StringHelper::basename(substr($file, 0, -strlen(".php")));
		}
		
		foreach ($classes as $className)
		{
			$this->modelClass = $className;
			$this->searchModelClass = $ns . "\\search\\Search" . StringHelper::basename($className);
			//$this->controllerClass =  $nsAlias . "\\" . str_replace("/", "\\", $this->moduleID) . "\\controllers\\" . StringHelper::basename($className) . "Controller";
			$this->controllerClass =  str_replace("/", "\\", StringHelper::dirname($origControllerClass)) . "\\" . StringHelper::basename($className) . "Controller";
			$generatedFiles = array_merge($generatedFiles, parent::generate());
		}
		$this->modelClass = $origModelClass;
		$this->searchModelClass = $origSearchClass;
		$this->controllerClass =  $origControllerClass;
			
		// Generate the IndexController
		$controllerFile = StringHelper::dirname(\Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')))) . "/IndexController.php";
		$generatedFiles[] = new CodeFile($controllerFile, $this->render('indexController.php', ['classes' => $classes]));
		$viewFile = StringHelper::dirname(StringHelper::dirname(\Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\'))))) . "/views/index/index.php";
		$generatedFiles[] = new CodeFile($viewFile, $this->render('indexControllerView.php'));
		
		// Generate the Module
		$moduleFile = StringHelper::dirname(\Yii::getAlias('@' . str_replace('\\', '/', ltrim(StringHelper::dirname($this->controllerClass), '\\')))) . "/Module.php";
		$generatedFiles[] = new CodeFile($moduleFile, $this->render('module.php'));
		
		return $generatedFiles;
	}
	
	/**
	 * Send the relations for the model to all views
	 * 
	 * (non-PHPdoc)
	 * @see \yii\gii\Generator::render()
	 */
	public function render($template, $params = [])
	{
		$modelClass = $this->modelClass;
		
		if (($pos = strpos($modelClass::tableName(), '.')) !== false) {
			$schemaName = substr($modelClass::tableName(), 0, $pos);
		} else {
			$schemaName = '';
		}
		
		$allRelations = SchemaHelper::generateRelations($modelClass::getDb(), $schemaName, StringHelper::dirname($modelClass));
		$className = StringHelper::basename($modelClass); 
		$params['relations'] = isset($allRelations[$className]) ? $allRelations[$className] : [];
		
		return parent::render($template, $params);	
	}
	
	/**
	 * Return the column to use as name
	 * 
	 * (non-PHPdoc)
	 * @see \yii\gii\generators\crud\Generator::getNameAttribute()
	 */
	public function getNameAttribute()
	{
		return SchemaHelper::getFirstStringColumnName($this->getTableSchema());
	}
	
	/**
	 * Generates column format
	 * @param \yii\db\ColumnSchema $column
	 * @return string
	 */
	public function generateColumnFormat($column)
	{
		if (SchemaHelper::isJsonColumn($column))
			return 'json';
		else if (SchemaHelper::isImageColumn($column))
			return 's3Image';
		else if (SchemaHelper::isFileColumn($column))
			return 's3File';
		else if (SchemaHelper::isEmailColumn($column))
			return 'email';
		else if (SchemaHelper::isHtmlColumn($column))
			return 'htmlBox';
		else if (SchemaHelper::isVideoColumn($column))
			return 'video';
		else if (SchemaHelper::isWeekDayColumn($column))
			return 'weekday';
		else if (stripos($column->name, 'time') !== false && $column->phpType === 'integer')
			return 'text';
		else
			return parent::generateColumnFormat($column);
	}
	
	/**
	 * Generates code for active search field
	 * @param string $attribute
	 * @return string
	 */
	public function generateActiveSearchField($attribute)
	{
		$tableSchema = $this->getTableSchema();
		if ($tableSchema === false) {
			return "\$form->field(\$model, '$attribute')";
		}
		
		// Calculate columns that are foreign keys
		$foreignKeys = [];
		foreach ($tableSchema->foreignKeys as $fk)
		{
			$refTable = array_shift($fk);
			$refId = current($fk);
			$colName = key($fk);
			$foreignKeys[$colName] = [$refTable, $refId];
		}
		
		if (array_key_exists($attribute, $foreignKeys))
		{
			$crudUrl = '/' . $this->moduleID . '/crud/' . str_replace("_", "-", $foreignKeys[$attribute][0]) . '';
			if (strpos($foreignKeys[$attribute][0], ".") !== false)
			{
				$parts = explode(".", $foreignKeys[$attribute][0]);
				if (count($parts) == 2)
				{
					$schema_parts = explode("_", $parts[0]);
					if (count($schema_parts) == 2)
					{
						$crudUrl = "/{$schema_parts[1]}/crud/" . str_replace("_", "-", $parts[1]) . '';
					}
				}
			}
			
			return "\$form->field(\$model, '$attribute')->widget(\common\yii\jui\AutoComplete::className(), [\n".
			"\t\t\t'crudUrl' => \yii\helpers\Url::toRoute(['{$crudUrl}']),\n" .
			"\t\t])";
		}
		
		$column = $tableSchema->columns[$attribute];
		if ($column->phpType === 'boolean') {
			return "\$form->field(\$model, '$attribute')->checkbox(null, true, true)";
		} else if (SchemaHelper::isWeekDayColumn($column)) {
			return "\$form->field(\$model, '$attribute')->weekdayInput()";
		} else {
			return "\$form->field(\$model, '$attribute')";
		}
	}
	
	/**
	 * Generate the active field to use on the form
	 * 
	 * @return string
	 */ 
	public function generateActiveField($attribute)
	{
		$tableSchema = $this->getTableSchema();
		
		// Calculate columns that are foreign keys
		$foreignKeys = [];
		foreach ($tableSchema->foreignKeys as $fk)
		{
			$refTable = array_shift($fk);
			$refId = current($fk);
			$colName = key($fk);
			$foreignKeys[$colName] = [$refTable, $refId];
		}
		
		if (array_key_exists($attribute, $foreignKeys))
		{
			$crudUrl = '/' . $this->moduleID . '/crud/' . str_replace("_", "-", $foreignKeys[$attribute][0]) . '';
			if (strpos($foreignKeys[$attribute][0], ".") !== false)
			{
				$parts = explode(".", $foreignKeys[$attribute][0]);
				if (count($parts) == 2)
				{
					$schema_parts = explode("_", $parts[0]);
					if (count($schema_parts) == 2)
					{
						$crudUrl = "/{$schema_parts[1]}/crud/" . str_replace("_", "-", $parts[1]) . '';
					}
				}
			}
				
			$hint = "";
			if ($tableSchema !== false && isset($tableSchema->columns[$attribute]))
			{
				$column = $tableSchema->columns[$attribute];
				if (!empty($column->comment))
					$hint = "->hint('" . \yii\helpers\Html::encode($column->comment) . "')";
			}
			return "\$form->field(\$model, '$attribute'){$hint}->widget(\common\yii\jui\AutoComplete::className(), [\n".
				"\t\t\t'crudUrl' => \yii\helpers\Url::toRoute(['{$crudUrl}']),\n" .
			"\t\t])";
		}
		
		if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) 
		{
			if (preg_match('/^(password|pass|passwd|passcode)$/i', $attribute)) {
				return "\$form->field(\$model, '$attribute')->passwordInput()";
			} else {
				return "\$form->field(\$model, '$attribute')";
			}
		}
		
		$column = $tableSchema->columns[$attribute];
		$hint = "";
		if (!empty($column->comment))
			$hint = "->hint('" . \yii\helpers\Html::encode($column->comment) . "')";
		$format = $this->generateColumnFormat($column);
		if ($format === 'boolean') {
			return "\$form->field(\$model, '$attribute')->checkbox(null, false){$hint}";
		} 
		else 
		{
			if (SchemaHelper::isPasswordColumn($column)) {
				$input = 'passwordInput';
			} else if (SchemaHelper::isWeekDayColumn($column)) {
				$input = 'weekdayInput';
			} else if (SchemaHelper::isTimeColumn($column)) {
				$input = 'timeInput';
			} else if (SchemaHelper::isImageColumn($column)) {
				$input = 's3ImageInput';
			} else if (SchemaHelper::isFileColumn($column)) {
				$input = 's3FileInput';
			} else if (SchemaHelper::isDateColumn($column)) {
				$input = 'dateInput';
			} else if (SchemaHelper::isDateTimeColumn($column)) {
				$input = 'dateTimeInput';
			} else if (SchemaHelper::isTokenColumn($column)) {
				$input = 'tokenInput';
			} else if (SchemaHelper::isEmailColumn($column)) {
				$input = 'emailInput';
			} else if (SchemaHelper::isHtmlColumn($column)) {
				$input = 'htmlInput';
			} else if (SchemaHelper::isVideoColumn($column)) {
				$input = 'videoInput';
			} else if ($column->type === 'text') {
				return "\$form->field(\$model, '$attribute')->textarea(['rows' => 6]){$hint}";
			} else {
				$input = 'textInput';
			}
			if (is_array($column->enumValues) && count($column->enumValues) > 0) {
				$dropDownOptions = [];
				foreach ($column->enumValues as $enumValue) {
					$constName = SchemaHelper::getEnumConstName($column->name, $enumValue);
					$dropDownOptions[] = "\t\t\t\\{$this->modelClass}::{$constName} => " . $this->generateString(Inflector::humanize($enumValue)) . ",";
				}
				return "\$form->field(\$model, '$attribute')->dropDownList([\n" . 
						implode("\n", $dropDownOptions) . 
					"\n\t\t], ['prompt' => '']){$hint}";
			} elseif ($column->phpType !== 'string' || $column->size === null) {
				return "\$form->field(\$model, '$attribute')->$input(){$hint}";
			} else {
				return "\$form->field(\$model, '$attribute')->$input(['maxlength' => $column->size]){$hint}";
			}
		}
	}
	
	/**
	 * Generates search conditions
	 * @return array
	 */
	public function generateSearchConditions()
	{
		$columns = [];
		if (($table = $this->getTableSchema()) === false) {
			$class = $this->modelClass;
			/* @var $model \yii\base\Model */
			$model = new $class();
			foreach ($model->attributes() as $attribute) {
				$columns[$attribute] = 'unknown';
			}
		} else {
			foreach ($table->columns as $column) {
				if (in_array($column->name,['created_at','created_by','updated_at','updated_by'])) continue;
				$columns[$column->name] = $column->type;
			}
		}
	
		$likeConditions = [];
		$hashConditions = [];
		foreach ($columns as $column => $type) {
			switch ($type) {
				case Schema::TYPE_SMALLINT:
				case Schema::TYPE_INTEGER:
				case Schema::TYPE_BIGINT:
				case Schema::TYPE_BOOLEAN:
				case Schema::TYPE_FLOAT:
				case Schema::TYPE_DOUBLE:
				case Schema::TYPE_DECIMAL:
				case Schema::TYPE_MONEY:
				//case Schema::TYPE_DATE:
				//case Schema::TYPE_TIME:
				//case Schema::TYPE_DATETIME:
				case Schema::TYPE_TIMESTAMP:
					$hashConditions[] = "'{$column}' => \$this->{$column},";
					break;
				default:
					$likeConditions[] = "->andFilterWhere(['like', '{$column}', \$this->{$column}])";
					break;
			}
		}
	
		$conditions = [];
		if (!empty($hashConditions)) {
			$conditions[] = "\$query->andFilterWhere([\n"
					. str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
					. "\n" . str_repeat(' ', 8) . "]);\n";
		}
		if (!empty($likeConditions)) {
			$conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
		}
	
		return $conditions;
	}
}