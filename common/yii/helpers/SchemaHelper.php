<?php
namespace common\yii\helpers;

use yii\db\ColumnSchema;
use yii\db\TableSchema;
use yii\db\Schema;
use yii\helpers\Inflector;

class SchemaHelper
{
	const TOKEN_COLUMN_SUFFIX = 'Token';
	const PASSWORD_COLUMN_SUFFIX = 'Password';
	const SLUG_COLUMN_SUFFIX = 'Slug';
	const HTML_COLUMN_SUFFIX = 'Html';
	const EMAIL_COLUMN_SUFFIX = 'Email';
	const JSON_COLUMN_SUFFIX = 'Json';
	const FILE_COLUMN_SUFFIX = 'File';
	const IMAGE_COLUMN_SUFFIX = 'Image';
	const VIDEO_COLUMN_SUFFIX = 'Video';
	const RATING_COLUMN_SUFFIX = 'Rating';
	const URL_COLUMN_SUFFIX = 'Url';
	const DATE_COLUMN_SUFFIX = 'Date';
	const DATE_SERVER_COLUMN_SUFFIX = 'DateServer';
	const WEEK_DAY_COLUMN_SUFFIX = 'WeekDay';
	const TIME_COLUMN_SUFFIX = 'Time';
	public static $dateServerFKNameForTimezone = [
		'timeZoneId' => ['timeZoneId'],
		'timeZone' => ['timeZone'],
		'countryId' => ['country', 'timeZoneId'],
		'cityId' => ['city', 'timeZoneId'],
	];
	
	/**
	 * Generate the constant name for a column and a value
	 * 
	 * @param string $columnName
	 * @param string $enumValue
	 * @return string
	 */
	public static function getEnumConstName($columnName, $enumValue)
	{
		return strtoupper(Inflector::camel2id($columnName, '_'))
			. "_" . strtoupper(Inflector::camel2id($enumValue, '_'));
	}
	
	/**
	 * Determine if it is a week day column
	 *
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isWeekDayColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->phpType != 'integer')
			return false;
		return StringHelper::endsWith($column->name, static::WEEK_DAY_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a time column
	 *
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isTimeColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->phpType != 'string' && $column->size < 5)
			return false;
		return StringHelper::endsWith($column->name, static::TIME_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a column with HTML content
	 *
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isHtmlColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->dbType != Schema::TYPE_TEXT)
			return false;
		return StringHelper::endsWith($column->name, static::HTML_COLUMN_SUFFIX);
	}

	/**
	 * Determine if it is a password column
	 *
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isPasswordColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->phpType != 'string')
			return false;
		return StringHelper::endsWith($column->name, static::PASSWORD_COLUMN_SUFFIX);
	}

	/**
	 * Determine if it is an column for random tokens
	 *
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isTokenColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->phpType != 'string')
			return false;
		return StringHelper::endsWith($column->name, static::TOKEN_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a slug column
	 *
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isSlugColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->phpType != 'string')
			return false;
		return StringHelper::endsWith($column->name, static::SLUG_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is an email column
	 *
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isEmailColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->phpType != 'string')
			return false;
		return StringHelper::endsWith($column->name, static::EMAIL_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a column that stores json content
	 *
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isJsonColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->dbType != Schema::TYPE_TEXT)
			return false;
		return StringHelper::endsWith($column->name, static::JSON_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a file column which stores information
	 * of a stored S3 file
	 *
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isFileColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->dbType != Schema::TYPE_TEXT)
			return false;
		return StringHelper::endsWith($column->name, static::FILE_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is an image column which stores information
	 * of a stored S3 image
	 *
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isImageColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->dbType != Schema::TYPE_TEXT)
			return false;
		return StringHelper::endsWith($column->name, static::IMAGE_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it represents a date time that has a localized
	 * version of the date with a specific timezone
	 * 
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isVideoColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->dbType != Schema::TYPE_TEXT)
			return false;
		return StringHelper::endsWith($column->name, static::VIDEO_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a column for rating someting. Values
	 * from 0 to 5
	 * 
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isRatingColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->phpType != 'integer' && $column->phpType != 'double')
			return false;
		return StringHelper::endsWith($column->name, static::RATING_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a column for rating someting. Values
	 * from 0 to 5
	 * 
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isUrlColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->phpType != 'string')
			return false;
		return StringHelper::endsWith($column->name, static::URL_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a column for dates
	 * 
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isDateColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->dbType != Schema::TYPE_DATE)
			return false;
		return StringHelper::endsWith($column->name, static::DATE_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a column for date times
	 * 
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isDateTimeColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->dbType != Schema::TYPE_DATETIME)
			return false;
		return StringHelper::endsWith($column->name, static::DATE_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a column for dates in the server timezone
	 * 
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isDateServerColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->dbType != Schema::TYPE_DATE)
			return false;
		return StringHelper::endsWith($column->name, static::DATE_SERVER_COLUMN_SUFFIX);
	}
	
	/**
	 * Determine if it is a column for date times in the server timezone
	 * 
	 * @param ColumnSchema $column
	 * @return boolean
	 */
	public static function isDateTimeServerColumn($column)
	{
		if ($column->autoIncrement)
			return false;
		if ($column->dbType != Schema::TYPE_DATETIME)
			return false;
		return StringHelper::endsWith($column->name, static::DATE_SERVER_COLUMN_SUFFIX);
	}
	
	/**
	 * Get the first string column name
	 * 
	 * @param TableSchema $tableSchema
	 * @return NULL
	 */
	public static function getFirstStringColumnName($tableSchema)
	{
		$firstStringColumn = null;
		if (isset($tableSchema->columns['fullName']))
			return 'fullName';
		foreach ($tableSchema->columns as $column)
		{
			if (static::isImageColumn($column) || static::isFileColumn($column) ||
				static::isFileColumn($column) || static::isHtmlColumn($column) ||
				static::isPasswordColumn($column) || static::isVideoColumn($column))
				continue;
			if ($column->phpType == 'string')
			{
				if ($firstStringColumn === null)
					$firstStringColumn = $column->name;
				
				if (stripos($column->name, 'name') !== false || stripos($column->name, 'title') !== false)
				{
					$firstStringColumn = $column->name;
					break;
				}
			}
		}
		return $firstStringColumn;
	}
	
	/**
	 * Get a models foreign key relations
	 * 
	 * Returns an array of key value pairs where the key
	 * is the name of the relation and the value is an array with:
	 *   'displayColumn' => name of the column to display
	 *   'info' => the foreign key info @see generateRelations 
	 * 
	 * @param string $modelClass
	 */
	public static function getModelFKRelations($modelClass)
	{
		if (is_object($modelClass))
			$modelClass = get_class($modelClass);
		
		if (($pos = strpos($modelClass::tableName(), '.')) !== false) {
			$schemaName = substr($modelClass::tableName(), 0, $pos);
		} else {
			$schemaName = '';
		}
		
		$relations = SchemaHelper::generateRelations($modelClass::getDb(), $schemaName, StringHelper::dirname($modelClass));
		$fks = [];
		if (array_key_exists(StringHelper::basename($modelClass), $relations))
		{
			foreach ($relations[StringHelper::basename($modelClass)] as $fkName => $fkInfo)
			{
				if ($fkInfo['type'] != 'hasOne') continue;
				if (strpos($fkInfo['refClass'], "\\") === false)
					$refModelClass = StringHelper::dirname($modelClass) . "\\" . $fkInfo['refClass'];
				else
					$refModelClass = $fkInfo['refClass'];
				$stringColumn = SchemaHelper::getFirstStringColumnName($refModelClass::getTableSchema());
				if (empty($stringColumn)) {
					try {
						$stringColumn = current(array_keys($refModelClass::getTableSchema()->columns));
					}
					catch (\Exception $ex) {
						\common\yii\helpers\SendAlert::configurationError("cine", "aa",[
							'modelClass' => $modelClass,
							'fkName' => $fkName,
							'fkInfo' => $fkInfo,
							'refModelClass' => $refModelClass,
							'ex' => $ex->getMessage(),
						]);
						throw $ex;
					}
				}
				$fks[lcfirst($fkName)] = [
					'displayColumn' => $stringColumn,
					'info' => $fkInfo,
				];
			}
		}
		return $fks;
	}
	
	/**
	 * Generate a relation name (copied from yii\gii\model\Generator)
	 * 
	 * @param unknown $relations
	 * @param unknown $className
	 * @param unknown $table
	 * @param unknown $key
	 * @param unknown $multiple
	 * @return Ambigous <string, mixed>
	 */
	protected static function generateRelationName($relations, $className, $table, $key, $multiple)
	{
		if (!empty($key) && substr_compare($key, 'id', -2, 2, true) === 0 && strcasecmp($key, 'id')) {
			$key = rtrim(substr($key, 0, -2), '_');
		}
		if ($multiple) {
			$key = Inflector::pluralize($key);
		}
		$name = $rawName = Inflector::id2camel($key, '_');
		$i = 0;
		while (isset($table->columns[lcfirst($name)])) {
			$name = $rawName . ($i++);
		}
		while (isset($relations[$className][lcfirst($name)])) {
			$name = $rawName . ($i++);
		}
	
		return $name;
	}
	
	/**
	 * Check a pivot table (copied from yii\gii\model\Generator)
	 * 
	 * @param unknown $table
	 * @return boolean|multitype:multitype:unknown
	 */
	protected static function checkPivotTable($table)
	{
		$pk = $table->primaryKey;
		if (count($pk) !== 2) {
			return false;
		}
		$fks = [];
		foreach ($table->foreignKeys as $refs) {
			if (count($refs) === 2) {
				if (isset($refs[$pk[0]])) {
					$fks[$pk[0]] = [$refs[0], $refs[$pk[0]]];
				} elseif (isset($refs[$pk[1]])) {
					$fks[$pk[1]] = [$refs[0], $refs[$pk[1]]];
				}
			}
		}
		if (count($fks) === 2 && $fks[$pk[0]][0] !== $fks[$pk[1]][0]) {
			return $fks;
		} else {
			return false;
		}
	}
	
	/**
	 * Generate the whole schema relations (modified from yii\gii\model\Generator)
	 * 
	 * @param \yii\db\Connection $db
	 * @param string $schemaName
	 * @param string $baseNS 
	 * @return Ambigous <multitype:, multitype:string boolean Ambigous <string, mixed> >
	 */
	public static function generateRelations($db, $schemaName, $baseNS = null)
	{
		$relations = [];
		foreach ($db->getSchema()->getTableSchemas($schemaName) as $table) {
			$tableName = $table->name;
			$className = Inflector::id2camel($tableName, '_');
			$tableForeignKeys = $table->foreignKeys;
			uasort($tableForeignKeys, function($a, $b){
				return strcmp($a[0], $b[0]);
			});
			foreach ($tableForeignKeys as $refs) {
				$refTable = $refs[0];
				unset($refs[0]);
				$fks = array_keys($refs);
				$refClassName = Inflector::id2camel($refTable, '_');
		
				// Add relation for this table
				$link = ArrayHelper::toStringPhpRepresentation(array_flip($refs));
				$relationName = static::generateRelationName($relations, $className, $table, $fks[0], false);
				
				if (strpos($refClassName, ".") !== false)
				{
					$refClassNameParts = explode(".", $refClassName);
					if (count($refClassNameParts) == 2)
					{
						$refSchemaName = $refClassNameParts[0];
						if (StringHelper::startsWith($refSchemaName, "Papaya"))
						{
							$moduleId = strtolower(str_replace("Papaya", "", $refSchemaName));
							if ($baseNS === null)
								$refClassName = "\\orm\\{$moduleId}\\" . Inflector::id2camel($refClassNameParts[1], '_');
							else
								$refClassName = "\\" . StringHelper::dirname($baseNS) . "\\{$moduleId}\\" . Inflector::id2camel($refClassNameParts[1], '_');
						}
					}
				}
				
				$relations[$className][$relationName] = [
					'type' => 'hasOne',
					'oneToOne' => false,
					'link' => array_flip($refs),
					'refClass' => $refClassName,
					'hasMany' => false,
					'phpReturn' => "return \$this->hasOne($refClassName::className(), $link);",
				];
		
				// Add relation for the referenced table
				$hasMany = false;
				if (count($table->primaryKey) > count($fks)) {
					$hasMany = true;
				} else {
					foreach ($fks as $key) {
						if (!in_array($key, $table->primaryKey, true)) {
							$hasMany = true;
							break;
						}
					}
				}
				$link = ArrayHelper::toStringPhpRepresentation($refs);
				$relationName = static::generateRelationName($relations, $refClassName, $refTable, $className, $hasMany);
				$relations[$refClassName][$relationName] = [
					'type' => ($hasMany ? 'hasMany' : 'hasOne'),
					'oneToOne' => ($hasMany ? false : true),
					'link' => $refs,
					'refClass' => $className,
					'hasMany' => $hasMany,
					'phpReturn' => "return \$this->" . ($hasMany ? 'hasMany' : 'hasOne') . "($className::className(), $link);",
				];
			}
		
			if (($fks = static::checkPivotTable($table)) === false) {
				continue;
			}
			$table0 = $fks[$table->primaryKey[0]][0];
			$table1 = $fks[$table->primaryKey[1]][0];
			$className0 = Inflector::id2camel($table0, '_');
			$className1 = Inflector::id2camel($table1, '_');
		
			$link = ArrayHelper::toStringPhpRepresentation([$fks[$table->primaryKey[1]][1] => $table->primaryKey[1]]);
			$viaLink = ArrayHelper::toStringPhpRepresentation([$table->primaryKey[0] => $fks[$table->primaryKey[0]][1]]);
			$relationName = static::generateRelationName($relations, $className0, $db->getTableSchema($table0), $table->primaryKey[1], true);
			$relations[$className0][$relationName] = [
				'type' => ($hasMany ? 'hasMany' : 'hasOne'),
				'oneToOne' => ($hasMany ? false : true),
				'link' => [$fks[$table->primaryKey[1]][1] => $table->primaryKey[1]],
				'viaTable' => [
					'name' => $table->schemaName . "." . $table->name,
					'link' => [$table->primaryKey[0] => $fks[$table->primaryKey[0]][1]],
				],
				'refClass' => $className1,
				'hasMany' => true,
				'phpReturn' => "return \$this->hasMany($className1::className(), $link)->viaTable('" . ($table->schemaName . "." . $table->name) . "', $viaLink);",
			];
		
			$link = ArrayHelper::toStringPhpRepresentation([$fks[$table->primaryKey[0]][1] => $table->primaryKey[0]]);
			$viaLink = ArrayHelper::toStringPhpRepresentation([$table->primaryKey[1] => $fks[$table->primaryKey[1]][1]]);
			$relationName = static::generateRelationName($relations, $className1, $db->getTableSchema($table1), $table->primaryKey[0], true);
			$relations[$className1][$relationName] = [
				'type' => ($hasMany ? 'hasMany' : 'hasOne'),
				'oneToOne' => ($hasMany ? false : true),
				'link' => [$fks[$table->primaryKey[0]][1] => $table->primaryKey[0]],
				'viaTable' => [
					'table' => $table->schemaName . "." . $table->name,
					'links' => [$table->primaryKey[1] => $fks[$table->primaryKey[1]][1]],
				],
				'refClass' => $className0,
				'hasMany' => true,
				'phpReturn' => "return \$this->hasMany($className0::className(), $link)->viaTable('" . ($table->schemaName . "." . $table->name) . "', $viaLink);",
			];
		}
		
		return $relations;
	}
}