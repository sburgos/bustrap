<?php
namespace common\yii\gii\generators\model;

use Yii;
use yii\gii\generators\model\Generator as ModelGenerator;
use common\yii\helpers\StringHelper;
use yii\db\Schema;
use yii\db\ColumnSchema;
use yii\gii\CodeFile;
use common\yii\helpers\SchemaHelper;
use yii\helpers\Inflector;

class Generator extends ModelGenerator
{	
	public $module = null;
	public $tableName = '*';
	public $ns = "orm\\";
    public $enableI18N = true;
    
    public function stickyAttributes()
    {
    	return [];
    }
    
    public function rules()
    {
    	return array_merge([[['module'], 'filter', 'filter'=>'trim']], parent::rules());
    }
    
    /**
     * Add parent models so we can add more functionality on them
     * without affecting the auto generated Gii class
     * 
     * (non-PHPdoc)
     * @see \yii\gii\generators\model\Generator::generate()
     */
    public function generate()
    {
    	$files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            $className = $this->generateClassName($tableName);
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $className,
                'tableSchema' => $tableSchema,
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$className]) ? $relations[$className] : [],
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $className . '.php',
                $this->render('model.php', $params)
            );
        }
        return $files;
    }
    
    protected function generateClassName($tableName, $useSchemaName = null)
    {
    	if (isset($this->classNames[$tableName])) {
    		return $this->classNames[$tableName];
    	}
    
    	$schemaName = '';
    	$fullTableName = $tableName;
    	if (($pos = strrpos($tableName, '.')) !== false) {
    		if (($useSchemaName === null && $this->useSchemaName) || $useSchemaName) {
    			$schemaName = substr($tableName, 0, $pos) . '_';
    		}
    		$tableName = substr($tableName, $pos + 1);
    	}
    
    	$db = $this->getDbConnection();
    	$patterns = [];
    	$patterns[] = "/^{$db->tablePrefix}(.*?)$/";
    	$patterns[] = "/^(.*?){$db->tablePrefix}$/";
    	if (strpos($this->tableName, '*') !== false) {
    		$pattern = $this->tableName;
    		if (($pos = strrpos($pattern, '.')) !== false) {
    			$pattern = substr($pattern, $pos + 1);
    		}
    		$patterns[] = '/^' . str_replace('*', '(\w+)', $pattern) . '$/';
    	}
    	$className = $tableName;
    	foreach ($patterns as $pattern) {
    		if (preg_match($pattern, $tableName, $matches)) {
    			$className = $matches[1];
    			break;
    		}
    	}
    
    	return $this->classNames[$fullTableName] = Inflector::id2camel(/*$schemaName.*/$className, '_');
    }
    
    /**
     * Generate some more extra rules based on specific column names
     * 
     * (non-PHPdoc)
     * @see \yii\gii\generators\model\Generator::generateRules()
     */
    public function generateRules($table)
    {
    	$rules = [];
    	
    	// Determine column types
    	$stringColumns = [];
    	$nullableColumns = [];
    	$emailColumns = [];
    	$fileColumns = [];
    	$imageColumns = [];
    	$jsonColumns = [];
    	$videoColumns = [];
    	$dateColumns = [];
    	$dateTimeColumns = [];
    	$ratingColumns = [];
    	$urlColumns = [];
    	$passwordColumns = [];
    	$tokenColumns = [];
    	foreach ($table->columns as $column)
    	{
    		if ($column->autoIncrement)
    			continue;
    		if ($column->enumValues)
    		{
    			$range = [];
    			foreach ($column->enumValues as $enum)
    			{
    				$range[] = 'self::' . strtoupper(Inflector::camel2id($column->name, '_')) . '_' . strtoupper($enum);
    			}
    			$rules["inarray_{$column->name}"] = "['" .  $column->name . "', 'in', 'range' => [" . implode(", ", $range) . "]]";
    		}
    		if ($column->allowNull)
    		{
    			if (!SchemaHelper::isImageColumn($column) && !SchemaHelper::isFileColumn($column))
    				$nullableColumns[] = $column->name;
    		}
    		if ($column->type == Schema::TYPE_STRING || $column->type == Schema::TYPE_TEXT)
    		{
    			if (!SchemaHelper::isImageColumn($column) && !SchemaHelper::isFileColumn($column))
    				$stringColumns[] = $column->name;
    		}
    		if (SchemaHelper::isEmailColumn($column))
    			$emailColumns[] = $column->name;
    		if (SchemaHelper::isFileColumn($column))
    			$fileColumns[] = $column->name;
    		if (SchemaHelper::isImageColumn($column))
    			$imageColumns[] = $column->name;
    		if (SchemaHelper::isJsonColumn($column))
    			$jsonColumns[] = $column->name;
    		if (SchemaHelper::isUrlColumn($column))
    			$urlColumns[] = $column->name;
    		if (SchemaHelper::isVideoColumn($column))
    			$videoColumns[] = $column->name;
    		if (SchemaHelper::isDateColumn($column))
    			$dateColumns[] = $column->name;
    		if (SchemaHelper::isDateTimeColumn($column))
    			$dateTimeColumns[] = $column->name;
    		if (SchemaHelper::isRatingColumn($column))
    			$ratingColumns[] = $column->name;
    		if (SchemaHelper::isPasswordColumn($column))
    			$passwordColumns[] = $column->name;
    		if (SchemaHelper::isTokenColumn($column))
    			$tokenColumns[] = $column->name;
    	}
    	
	    // Add Json columns
    	if (count($jsonColumns) > 0) {
    		$rules['json'] = "[['" . implode("', '", $jsonColumns) . "'], '\\\\common\\\\yii\\\\validators\\\\FilterJsonEncode']";
    	}
    	
    	// Add trim filter to string columns
    	if (count($stringColumns) > 0)
    		$rules['trim'] = "[['" . implode("', '", $stringColumns) . "'], 'trim']";

    	// Add convert to null nullable columns
    	if (count($nullableColumns) > 0) {
    		$rules['nullable'] = "[['" . implode("', '", $nullableColumns) . "'], 'default']";
    	}
    	
    	// Add email columns
    	if (count($emailColumns) > 0) {
    		$rules['email'] = "[['" . implode("', '", $emailColumns) . "'], 'email']";
    	}
    	
    	// Add url columns
    	if (count($urlColumns) > 0) {
    		$rules['url'] = "[['" . implode("', '", $urlColumns) . "'], 'url']";
    	}
    	
    	// Add date columns
    	if (count($dateColumns) > 0 || count($dateTimeColumns) > 0) 
    	{
    		foreach (array_merge($dateColumns, $dateTimeColumns) as $dateCol)
    		{
    			$format = 'php:Y-m-d';
    			if (SchemaHelper::isDateTimeColumn($table->columns[$dateCol]))
    				$format .= " H:i:s";
    			$serverColumnName = substr($dateCol, 0, -strlen(SchemaHelper::DATE_COLUMN_SUFFIX)) . SchemaHelper::DATE_SERVER_COLUMN_SUFFIX;
    			
    			if (isset($table->columns[$serverColumnName]))
    			{
    				$timeZoneAttribute = "null";
    				foreach (SchemaHelper::$dateServerFKNameForTimezone as $key => $tzAttrib)
    				{
    					if (array_key_exists($key, $table->columns))
    					{
    						$timeZoneAttribute = "['" . implode("', '", $tzAttrib) . "']";
    						break;
    					}
    				}
    				$rules["date-{$dateCol}"] = "['{$dateCol}', '\\common\\yii\\validators\\DateValidator', 'format' => '{$format}', 'timestampAttribute' => '{$serverColumnName}', 'timeZoneAttribute' => {$timeZoneAttribute}]";
    			}
    			else
    				$rules["date-{$dateCol}"] = "['{$dateCol}', '\\common\\yii\\validators\\DateValidator', 'format' => '{$format}']";
    		}
    	}
    	
    	// Add file columns. Set isEmpty to detect that if the value is
    	// already a JSON with the info about the uploaded file then it
    	// will be skipped
    	if (count($fileColumns) > 0) {
    		$rules['file'] = "[['" . implode("', '", $fileColumns) . "'], 'file']";
    	}
    	
    	// Add image columns
    	if (count($imageColumns) > 0) {
    		$rules['image'] = "[['" . implode("', '", $imageColumns) .
				 "'], 'image']";
		}
    	
		// Add rating columns
    	if (count($ratingColumns) > 0) {
    		$rules['rating'] = "[['" . implode("', '", $ratingColumns) . "'], 'number', 'min' => 0, 'max' => 5]";
    	}
    	
		// Add password columns
    	if (count($passwordColumns) > 0) {
    		foreach ($passwordColumns as $colName)
    			$rules["password-{$colName}"] = "['{$colName}', 'string', 'min' => static::" . strtoupper(Inflector::camel2id($colName, '_')) ."_MIN_LENGTH]";
    	}
    	
		return array_merge($rules, $this->yiiDefaultGenerateRules($table));
	}
	
	/**
	 * Get the relations
	 * @return Ambigous <multitype:, multitype:string boolean Ambigous <string, mixed> >
	 */
	protected function generateRelations()
    {
        if (!$this->generateRelations) {
            return [];
        }

        $db = $this->getDbConnection();
        
        if (($pos = strpos($this->tableName, '.')) !== false) {
        	$schemaName = substr($this->tableName, 0, $pos);
        } else {
        	$schemaName = '';
        }
        
        return SchemaHelper::generateRelations($db, $schemaName, $this->ns);
    }

	/**
	 * Generates validation rules for the specified table.
	 * 
	 * This code is almost the same as the original generator. It just
	 * adds key names for the rules.
	 * 
	 * It also skips setting to string images and files
	 * 
	 * @param \yii\db\TableSchema $table
	 *        	the table schema
	 * @return array the generated validation rules
	 */
	protected function yiiDefaultGenerateRules($table)
	{
		$types = [];
		$lengths = [];
		foreach ($table->columns as $column)
		{
			if ($column->autoIncrement)
			{
				continue;
			}
			if (! $column->allowNull && $column->defaultValue === null)
			{
				if ($column->name != 'created_at' && $column->name != 'updated_at' &&
					$column->name != 'created_by' && $column->name != 'updated_by')
				{
					$types['required'][] = $column->name;
				}
			}
			switch ($column->type)
			{
				case Schema::TYPE_SMALLINT:
				case Schema::TYPE_INTEGER:
				case Schema::TYPE_BIGINT:
					$types['integer'][] = $column->name;
					break;
				case Schema::TYPE_BOOLEAN:
					$types['boolean'][] = $column->name;
					break;
				case Schema::TYPE_FLOAT:
				case Schema::TYPE_DOUBLE:
				case Schema::TYPE_DECIMAL:
				case Schema::TYPE_MONEY:
					$types['number'][] = $column->name;
					break;
				case Schema::TYPE_DATE:
				case Schema::TYPE_TIME:
				case Schema::TYPE_DATETIME:
				case Schema::TYPE_TIMESTAMP:
					if (!SchemaHelper::isDateServerColumn($column) && !SchemaHelper::isDateTimeServerColumn($column))
						$types['safe'][] = $column->name;
					break;
				default: // strings
					if (!SchemaHelper::isImageColumn($column) && !SchemaHelper::isFileColumn($column))
					{
						if ($column->size > 0)
						{
							$lengths[$column->size][] = $column->name;
						}
						else
						{
							$types['string'][] = $column->name;
						}
					}
			}
		}
		$rules = [];
		foreach ($types as $type => $columns)
		{
			$rules[$type] = "[['" . implode("', '", $columns) . "'], '$type']";
		}
		foreach ($lengths as $length => $columns)
		{
			$rules["string_{$length}"] = "[['" . implode("', '", $columns) .
				 "'], 'string', 'max' => $length]";
		}
		
		// Unique indexes rules
		try
		{
			$db = $this->getDbConnection();
			$uniqueIndexes = $db->getSchema()->findUniqueIndexes($table);
			foreach ($uniqueIndexes as $uniqueColumns)
			{
				// Avoid validating auto incremental columns
				if (! $this->isColumnAutoIncremental($table, $uniqueColumns))
				{
					$attributesCount = count($uniqueColumns);
					
					if ($attributesCount == 1)
					{
						$rules["unique_{$uniqueColumns[0]}"] = "[['" . $uniqueColumns[0] . "'], 'unique']";
					}
					elseif ($attributesCount > 1)
					{
						$labels = array_intersect_key(
							$this->generateLabels($table), 
							array_flip($uniqueColumns));
						$lastLabel = array_pop($labels);
						$columnsList = implode("', '", $uniqueColumns);
						$columnsListKey = implode("_", $uniqueColumns);
						$rules["unique_{$columnsListKey}"] = "[['" . $columnsList .
							 "'], 'unique', 'targetAttribute' => ['" .
							 $columnsList .
							 "'], 'message' => 'The combination of " .
							 implode(', ', $labels) . " and " . $lastLabel .
							 " has already been taken.']";
					}
    			}
    		}
    	} catch (NotSupportedException $e) {
    		// doesn't support unique indexes information...do nothing
    	}
    
    	return $rules;
    }
}