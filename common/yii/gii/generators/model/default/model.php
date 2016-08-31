<?php
use yii\helpers\Inflector;
use common\yii\helpers\S3ClientHelper;
use common\yii\helpers\SchemaHelper;

/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */

$passwordColumns = [];
$serverDateColumns = [];
$imageColumns = [];
$fileColumns = [];
$videoColumns = [];
$slugColumns = [];
$tokenColumns = [];
$enumColumns = [];
$enumConstants = [];
$enumConstantsTranslations = [];
$firstStringColumn = SchemaHelper::getFirstStringColumnName($tableSchema);
$searchStringColumn = $firstStringColumn;
foreach ($tableSchema->columns as $column)
{
	if (strpos($column->name, 'fullName') !== false)
		$searchStringColumn = $column->name;
	
	if (SchemaHelper::isDateServerColumn($column) || SchemaHelper::isDateTimeServerColumn($column))
		$serverDateColumns[$column->name] = $column;
	
	if (SchemaHelper::isPasswordColumn($column))
		$passwordColumns[$column->name] = $column;
			
	if (SchemaHelper::isTokenColumn($column))
		$tokenColumns[$column->name] = $column;
	
	if (SchemaHelper::isImageColumn($column))
		$imageColumns[$column->name] = $column;

	if (SchemaHelper::isFileColumn($column))
		$fileColumns[$column->name] = $column;

	if (SchemaHelper::isSlugColumn($column))
		$slugColumns[$column->name] = $column;

	if (SchemaHelper::isVideoColumn($column))
		$videoColumns[$column->name] = $column;
	
	if (!empty($column->enumValues))
	{
		$enumColumns[$column->name] = $column;
		foreach ($column->enumValues as $value)
		{
			$constName = SchemaHelper::getEnumConstName($column->name, $value);
			$enumConstants[] = "\tconst {$constName} = '{$value}';";
			$enumConstantsTranslations[] = "\t\t" . $generator->generateString($value) . ";";
		}
	}
}

echo "<?php\n";
?>
namespace <?= $generator->ns ?>;

use Yii;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
<?php foreach ($tableSchema->columns as $column): $comment = empty($column->comment) ? "" : "\t" . $column->comment; ?>
 * @property <?= "{$column->phpType} \${$column->name}{$comment}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation['refClass'] . ($relation['hasMany'] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseClass, '\\') . "\n" ?>
{
	public static $__AJAX_LABEL_FIELDS_COMMA_SEPARATED = '<?= ($searchStringColumn === null ? current($tableSchema->primaryKey) : $searchStringColumn) ?>';
	public static $__AJAX_LABEL_FORMAT = "%s";
	
	<?php foreach ($passwordColumns as $col) : echo "\n"; ?>
	const <?= strtoupper(Inflector::camel2id($col->name, '_')) ?>_MIN_LENGTH = 6;
	<?php endforeach; echo "\n"; ?>
<?php echo count($enumConstants) > 0 ? implode("\n", $enumConstants) . "\n\n" : ""; ?>
<?php if (count($enumConstantsTranslations) > 0) : ?>
	/**
	 * This is a function place just so that gettext can
	 * parse this strings as messages
	 */
	private function __translationsForGetText()
	{<?= "\n" . implode("\n", $enumConstantsTranslations) . "\n"; ?>
	}
	
<?php endif; ?>
	/**
	 * Fields to include when doing toArray
	 */
	public function fields()
	{
		$fields = array_diff_key(
			parent::fields(), 
			array_flip([
				<?= "'" . implode("',\n\t\t\t\t'", array_merge(
					['created_at', 'created_by', 'updated_at', 'updated_by'],
					array_keys($passwordColumns),
					array_keys($tokenColumns)
				)) . "',\n"?>
			])
		);
		<?php foreach ($imageColumns as $imgColName => $imgCol) : ?>
		
		$fields['<?= $imgColName ?>'] = function($row){
			if (empty($row-><?= $imgColName ?>)) return null;
			return $row->get<?= ucfirst(substr($imgColName, 0, -5)) ?>();
		};
		<?php endforeach;?>
		<?php foreach ($fileColumns as $fileColName => $fileCol) : ?>
		
		$fields['<?= $fileColName ?>'] = function($row){
			return $row->get<?= ucfirst(substr($fileColName, 0, -4)) ?>();
		};
		<?php endforeach;?>
		<?php foreach ($videoColumns as $videoColName => $videoCol) : ?>
		
		$fields['<?= $videoColName ?>'] = function($row){
			return $row->get<?= ucfirst(substr($videoColName, 0, -5)) ?>();
		};
		<?php endforeach;?>
		<?php foreach ($enumColumns as $enuColName => $enuCol) : ?>
		
		$fields['<?= $enuColName ?>Label'] = function($row){
			if (empty($row-><?= $enuColName ?>)) return $row-><?= $enuColName ?>;
			return <?= str_replace("'%s'", '$row->' . $enuColName, $generator->generateString('%s')) ?>;
		};
		<?php endforeach;?>
		
		return $fields;
	}
	
	/**
	 * Set behaviors
	 */
	public function behaviors()
	{
		return [<?php echo "\n";
		 
		foreach ($slugColumns as $column)
		{
			$slugBaseColumnName = substr($column->name, 0, -strlen(SchemaHelper::SLUG_COLUMN_SUFFIX));
			if (array_key_exists($slugBaseColumnName, $tableSchema->columns))
				$attributes = [$slugBaseColumnName];
			else 
				$attributes = $tableSchema->primaryKey;
			if (array_key_exists('regionId', $tableSchema->columns))
				$attributes[] = ['region', 'nameSlug'];
			if (array_key_exists('countryId', $tableSchema->columns))
				$attributes[] = 'countryId';
			if (array_key_exists('movieTheaterChainId', $tableSchema->columns))
				$attributes[] = 'movieTheaterChainId';
			echo "\t\t\t'slug_{$column->name}' => [\n\t\t\t\t'class' => \\common\\yii\\behaviors\\SluggableBehavior::className(),\n";
			//echo "\t\t\t\t'attribute' => ['" . implode("', '", $attributes) . "'],\n";
			echo "\t\t\t\t'attribute' => [";
			foreach ($attributes as $attrib)
			{
				if (is_string($attrib))
					echo "'{$attrib}',";
				else if (is_array($attrib)) {
					echo "['" . implode("', '", $attrib) . "'],";
				}
			}
			echo "],\n";
			echo "\t\t\t\t'slugAttribute' => '{$column->name}',\n";
			echo "\t\t\t\t'ensureUnique' => true,\n";
			echo "\t\t\t],\n";
		}
		
		if (count($passwordColumns) > 0)
		{
			echo "\t\t\t'passwords' => [\n";
			echo "\t\t\t\t'class' => \\common\\yii\\behaviors\\PasswordBehavior::className(),\n";
			echo "\t\t\t\t'columns' => ['" . implode("', '", array_keys($passwordColumns)) . "'],\n";
			echo "\t\t\t],\n";
		}
		
		if (count($videoColumns) > 0)
		{
			echo "\t\t\t'videos' => [\n";
			echo "\t\t\t\t'class' => \\common\\yii\\behaviors\\VideoBehavior::className(),\n";
			echo "\t\t\t\t'columns' => ['" . implode("', '", array_keys($videoColumns)) . "'],\n";
			echo "\t\t\t],\n";
		}
		
		if (count($imageColumns) > 0)
		{
			echo "\t\t\t'images' => [\n";
			echo "\t\t\t\t'class' => \\common\\yii\\behaviors\\S3UploadImageBehavior::className(),\n";
			echo "\t\t\t\t'columns' => [\n";
			foreach ($imageColumns as $colName => $column)
			{
				$a = $generator->module . "/" . $tableSchema->name;
				$b = $firstStringColumn === null ? "" : "{" . $firstStringColumn . "}-";
				$c = "{" . implode("}-{", $tableSchema->primaryKey) . "}";
				$d = Inflector::slug(substr($colName, 0, -strlen(SchemaHelper::IMAGE_COLUMN_SUFFIX)));
				echo "\t\t\t\t\t'{$colName}' => ['name' => '{$a}/{$b}{$c}-{$d}'],\n";
			}
			echo "\t\t\t\t],\n";
			echo "\t\t\t],\n";
		}
		
		if (count($fileColumns) > 0)
		{
			echo "\t\t\t'files' => [\n";
			echo "\t\t\t\t'class' => \\common\\yii\\behaviors\\S3UploadFileBehavior::className(),\n";
			echo "\t\t\t\t'columns' => [\n";
			foreach ($fileColumns as $colName => $column)
			{
				$a = $generator->module . "/" . $tableSchema->name;
				$b = $firstStringColumn === null ? "" : "{" . $firstStringColumn . "}-";
				$c = "{" . implode("}-{", $tableSchema->primaryKey) . "}";
				$d = Inflector::slug(substr($colName, 0, -strlen(SchemaHelper::FILE_COLUMN_SUFFIX)));
				echo "\t\t\t\t\t'{$colName}' => ['name' => '{$a}/{$b}{$c}-{$d}'],\n";
			}
			echo "\t\t\t\t],\n";
			echo "\t\t\t],\n";
		}
		
		if (isset($tableSchema->columns['created_at']) && isset($tableSchema->columns['updated_at']))
		{
			echo "\t\t\t'timestamp' => [\n";
			echo "\t\t\t\t'class' => \\yii\\behaviors\\TimestampBehavior::className(),\n";
			echo "\t\t\t\t'value' => function () { return date('Y-m-d H:i:s'); },\n";
			echo "\t\t\t\t'attributes' => [\n";
			echo "\t\t\t\t\t\\yii\\db\\BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],\n";
			echo "\t\t\t\t\t\\yii\\db\\BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',\n";
			echo "\t\t\t\t],\n";
			echo "\t\t\t],\n";
		}
		
		if (isset($tableSchema->columns['created_by']) && isset($tableSchema->columns['updated_by']))
		{
			echo "\t\t\t'blameable' => [\n";
			echo "\t\t\t\t'class' => \\common\\yii\\behaviors\\BlameableBehavior::className(),\n";
			echo "\t\t\t\t'attributes' => [\n";
			echo "\t\t\t\t\t\\yii\\db\\BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_by', 'updated_by'],\n";
			echo "\t\t\t\t\t\\yii\\db\\BaseActiveRecord::EVENT_BEFORE_UPDATE => 'updated_by',\n";
			echo "\t\t\t\t],\n";
			echo "\t\t\t],\n";
		}
		?>
        ];
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $generator->generateTableName($tableName) ?>';
    }
<?php if ($generator->db !== 'db'): ?>

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>

    /**
     * Rules have an index key so it is easy to check by rule type or
     * even remove rules or change them from inherited classes.
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [<?php 
        	echo "\n";
        	foreach ($rules as $id => $rule)
        		echo "\t\t\t'{$id}' => {$rule},\n";
        ?>
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
    
    /**
     * Get the label to use on
     * foreign keys
     */
    public function obtainFKLabel($fields = null, $format = null)
    {
    	if (empty($fields))
    	{
    		$labelFields = ['<?= ($searchStringColumn === null ? current($tableSchema->primaryKey) : $searchStringColumn) ?>'];
			$labelFormat = "%s";
		}
		else
		{
			$labelFields = $fields;
			$labelFormat = $format;
		}
		
		$rowValues = [];
    	foreach ($labelFields as $field)
    		$rowValues[] = isset($this->{$field}) ? $this->{$field} : "";
    	return vsprintf($labelFormat, $rowValues);
    }
    
<?php foreach ($tableSchema->columns as $column): 
		$methodName = null;
		$returnVal = '[] | object | mixed';
		if (SchemaHelper::isJsonColumn($column)) {
			$methodName = Inflector::id2camel(substr($column->name, 0, -strlen(SchemaHelper::JSON_COLUMN_SUFFIX)));
		}
		else if (SchemaHelper::isFileColumn($column)) {
			$returnVal = "[] | null";
			$methodName = Inflector::id2camel(substr($column->name, 0, -strlen(SchemaHelper::FILE_COLUMN_SUFFIX)));
		}
		else if (SchemaHelper::isVideoColumn($column)) {
			$returnVal = "[] | null";
			$methodName = Inflector::id2camel(substr($column->name, 0, -strlen(SchemaHelper::VIDEO_COLUMN_SUFFIX)));
		}
		else if (SchemaHelper::isImageColumn($column)) {
			$returnVal = "[[]] | null";
			$methodName = Inflector::id2camel(substr($column->name, 0, -strlen(SchemaHelper::IMAGE_COLUMN_SUFFIX)));
		}
		if ($methodName === null) continue;
	?>
	/**
	 * Get the value of <?= Inflector::id2camel($column->name); ?> already json decoded.
	 *
<?php if (SchemaHelper::isFileColumn($column)) : ?>
	 * Returns an array with the information of the file
	 * 	 S3ClientHelper::S3KEY_NAME (<?= S3ClientHelper::S3KEY_NAME ?>) => // s3 key name
	 * 	 S3ClientHelper::S3BUC_NAME (<?= S3ClientHelper::S3BUC_NAME ?>) => // s3 bucket name
	 * 	 S3ClientHelper::S3URL_NAME (<?= S3ClientHelper::S3URL_NAME ?>) => // url to the file
	 * 	 size => // the size in bytes of the file
	 *  
<?php endif; ?>
<?php if (SchemaHelper::isImageColumn($column)) : ?>
	 * Returns an array with all the available formats. Each value in the
	 * array is indexed for easy retrieval of a specific format and contains
	 * the following
	 * 	 S3ClientHelper::S3KEY_NAME (<?= S3ClientHelper::S3KEY_NAME ?>) => // s3 key name
	 * 	 S3ClientHelper::S3BUC_NAME (<?= S3ClientHelper::S3BUC_NAME ?>) => // s3 bucket name
	 * 	 S3ClientHelper::S3URL_NAME (<?= S3ClientHelper::S3URL_NAME ?>) => // url to the file
	 * 	 size => // the size in bytes of the file
	 * 	 extension => // extension of the file
	 * 	 with => // with in pixels of the original image
	 * 	 height => // height in pixels of the original image
	 *
<?php endif; ?>
	 * @return <?= $returnVal . "\n"; ?>
	 */
	public function get<?= $methodName; ?>()
	{
		if (empty($this-><?= $column->name ?>))
			return null;
		return @json_decode($this-><?= $column->name ?>, true);
	}
	
<?php endforeach; ?>
<?php foreach ($tableSchema->columns as $column): if (!SchemaHelper::isFileColumn($column) && !SchemaHelper::isImageColumn($column)) continue; ?>
	/**
	 * Get the URL to the <?= Inflector::id2camel($column->name); ?>.
	 * The file stores JSON data with the S3 Key and S3 Url so this
	 * method helps getting the Url
	 */
	public function get<?= Inflector::id2camel($column->name); ?>Url($schema = null)
	{
		if (empty($this-><?= $column->name ?>))
			return null;
		$data = @json_decode($this-><?= $column->name ?>, true);
		if (!$data || !is_array($data) || 
			!isset($data[\common\yii\helpers\S3ClientHelper::S3URL_NAME]))
			return null;
		$url = $data[\common\yii\helpers\S3ClientHelper::S3URL_NAME];
		if (($schema == 'http' || $schema == 'https') <?= "&& "?> \yii\helpers\StringHelper::startsWith($url, '//'))
			$url = $schema . ":" . $url;
		return $url;
	}
	
<?php endforeach; ?>
<?php foreach ($tableSchema->columns as $column): if (!SchemaHelper::isPasswordColumn($column)) continue; ?>	
	/**
	 * Verify the password <?= Inflector::id2camel($column->name); ?>.
	 * The idea is that you can pass a plain password string and this
	 * will check if it is a valid password
	 */
	public function verify<?= Inflector::id2camel($column->name); ?>($plainPassword)
	{
		if (empty($plainPassword)) return false;
		return \Yii::$app->getSecurity()->validatePassword(
			$plainPassword, 
			$this-><?= $column->name . "\n"; ?>
		);
	}
	
<?php endforeach; ?>
<?php foreach ($relations as $name => $relation): ?>
    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation['phpReturn'] . "\n" ?>
    }
    
<?php endforeach; ?>
<?php if (count($serverDateColumns) > 0) : ?>
	/**
	 * Override so we do not allow server dates to skip translation
	 * to local dates
	 */
	public function __set($name, $value)
    {
    	<?php 
    	foreach ($serverDateColumns as $column) 
    	{
    		$format = 'Y-m-d';
    		if (SchemaHelper::isDateTimeServerColumn($column))
    			$format .= " H:i:s";
    		$localColumnName = substr($column->name, 0, -strlen(SchemaHelper::DATE_SERVER_COLUMN_SUFFIX)) . SchemaHelper::DATE_COLUMN_SUFFIX;
    		if (!array_key_exists($localColumnName, $tableSchema->columns))
    			continue;
    		$timeZoneAttribute = null;
    		foreach (SchemaHelper::$dateServerFKNameForTimezone as $key => $tzAttrib)
    		{
    			if (array_key_exists($key, $tableSchema->columns))
    			{
    				$timeZoneAttribute = "\$this->" . implode("->", $tzAttrib);
    				break;
    			}
    		}
    		if ($timeZoneAttribute === null)
    			continue;
    	echo "\n"; ?>
    	// Set the local date whenever changing the server date
    	if ($name == '<?= $column->name ?>' && is_string($value) && !empty($value)) {
    		$dt = new \DateTime($value);
    		$dt->setTimezone(new \DateTimeZone(<?= $timeZoneAttribute?>));
    		parent::__set('<?= $localColumnName ?>', $dt->format('<?= $format ?>'));
    	}
    	
    	<?php }; echo "\n"; ?>
    	return parent::__set($name, $value);
    }
<?php endif; ?>
}
