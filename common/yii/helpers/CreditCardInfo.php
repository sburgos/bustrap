<?php
namespace common\yii\helpers;

use common\yii\helpers\Binlist;

class CreditCardInfo
{
	const TYPE_AMERICAN_EXPRESS = 'American_Express';
    const TYPE_UNIONPAY         = 'Unionpay';
    const TYPE_DINERS_CLUB      = 'Diners_Club';
    const TYPE_DINERS_CLUB_US	= 'Diners_Club_US';
    const TYPE_DISCOVER         = 'Discover';
    const TYPE_JCB              = 'JCB';
    const TYPE_LASER            = 'Laser';
    const TYPE_MAESTRO          = 'Maestro';
    const TYPE_MASTERCARD       = 'Mastercard';
    const TYPE_SOLO             = 'Solo';
    const TYPE_VISA             = 'Visa';

    /**
     * List of allowed Bin Lengths
     *
     * @var array
     */
    public static $cardLength = array(
        self::TYPE_AMERICAN_EXPRESS	=> [15],
        self::TYPE_DINERS_CLUB		=> [14],
        self::TYPE_DINERS_CLUB_US	=> [16],
        self::TYPE_DISCOVER			=> [16],
        self::TYPE_JCB				=> [15,16],
        self::TYPE_LASER			=> [16, 17, 18, 19],
        self::TYPE_MAESTRO			=> [12, 13, 14, 15, 16, 17, 18, 19],
        self::TYPE_MASTERCARD		=> [16],
        self::TYPE_SOLO				=> [16, 18, 19],
        self::TYPE_UNIONPAY			=> [16, 17, 18, 19],
        self::TYPE_VISA				=> [16],
    );

    /**
     * List of accepted Bin numbers
     *
     * @var array
     */
    public static $cardType = array(
        self::TYPE_VISA => ['4'],
    	self::TYPE_MASTERCARD => ['51', '52', '53', '54', '55'],
        self::TYPE_AMERICAN_EXPRESS => ['34', '37'],
        self::TYPE_DINERS_CLUB => ['300', '301', '302', '303', '304', '305', '36'],
        self::TYPE_DISCOVER => [
			'6011', '622126', '622127', '622128', '622129', '62213',
        	'62214', '62215', '62216', '62217', '62218', '62219',
        	'6222', '6223', '6224', '6225', '6226', '6227', '6228',
        	'62290', '62291', '622920', '622921', '622922', '622923',
        	'622924', '622925', '644', '645', '646', '647', '648',
        	'649', '65'
        ],
        self::TYPE_JCB => [
        	'1800', '2131', '3528', '3529', '353', '354', '355', '356', '357', '358'
        ],
        self::TYPE_LASER => ['6304', '6706', '6771', '6709'],
        self::TYPE_MAESTRO => [
        	'5018', '5020', '5038', '6304', '6759', '6761', '6762', '6763',
        	'6764', '6765', '6766', '6016',
        ],
        self::TYPE_SOLO => ['6334', '6767'],
        self::TYPE_UNIONPAY => [
        	'622126', '622127', '622128', '622129', '62213', '62214',
        	'62215', '62216', '62217', '62218', '62219', '6222', '6223',
        	'6224', '6225', '6226', '6227', '6228', '62290', '62291',
        	'622920', '622921', '622922', '622923', '622924', '622925'
		],
		self::TYPE_DINERS_CLUB_US => ['54', '55'],
    );

	protected $number = '';
	protected $numberSafe = '';
	protected $numberDisplay = '';
	protected $bin = '';
	protected $last4 = '';
	protected $type = null;
	protected $binInfo = null;
	protected $countryCode = null;

	/**
	 * Construct by using the credit card number
	 *
	 * @param string $ccNumber
	 * @param boolean $validateLength
	 */
	public function __construct($ccNumber, $validateLength = true)
	{
		// Keep only digits of $ccNumber into $number
		$ccNumber = (string)$ccNumber;
		$len = strlen($ccNumber);
		$number = "";
		for ($k=0; $k<$len; $k++)
		{
			if ($ccNumber[$k] >= '0' && $ccNumber[$k] <= '9')
				$number .= $ccNumber[$k];
		}
		
		// Build a safe display version of the credit card number
		$len = strlen($number);
		$numberSafe = "";
		for ($k=0; $k<$len; $k++)
		{
			if ($k < 6)
				$numberSafe .= $number[$k];
			else if ($k >= $len - 4)
				$numberSafe .= $number[$k];
			else
				$numberSafe .= 'X';
		}
		$this->number = $number;
		$this->numberSafe = $numberSafe;
		$this->numberDisplay = implode("-", str_split($this->numberSafe, 4));
		$this->bin = substr($this->number, 0, 6);
		$this->last4 = substr($this->number, -4);
		
		$this->type = null;
		foreach (static::$cardType as $cardType => $bins)
		{
			foreach ($bins as $prefix)
			{
				if (substr($this->number, 0, strlen($prefix)) == $prefix)
				{
					if (!$validateLength || in_array(strlen($this->number), static::$cardLength[$cardType]))
					{
						$this->type = $cardType;
						break 2;
					}
				}
			}
		}
	}
	
	/**
	 * Get the first 6 digits
	 * 
	 * @return string
	 */
	public function getBin()
	{
		return $this->bin;
	}

	/**
	 * Get the last 4 digits
	 *
	 * @return NULL|string
	 */
	public function getLast4()
	{
		return $this->last4;
	}

	/**
	 * Get the ccNumber
	 *
	 * @return Ambigous <NULL, string>
	 */
	public function getNumber()
	{
		return $this->number;
	}
	
	/**
	 * Get the credit card number but only show the bin and last4
	 * @return string
	 */
	public function getNumberSafe()
	{
		return $this->numberSafe;
	}
	
	/**
	 * Get the credit card number but only show the bin and last4 and also
	 * split every 4 characters
	 * @return string
	 */
	public function getNumberDisplay()
	{
		return $this->numberDisplay;
	}

	/**
	 * Get the type of credit card. See constants TYPE_XXXX
	 *
	 * If will return null if the credit card type could not
	 * be determined
	 *
	 * @return NULL|string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Get the BinInfo
	 *
	 * This will consume an external service. It will use the
	 * Binlist helper
	 *
	 * @return \orm\v1\pay\BinInfo
	 */
	public function getBinInfo()
	{
		if ($this->binInfo === null)
		{
			$binInfo = Binlist::get($this->getBin());
			$this->binInfo = $binInfo;
		}
		return $this->binInfo;
	}

	/**
	 * Return the country code based on the bin.
	 *
	 * This will consume an external service. It will use the
	 * Binlist helper
	 *
	 * @return NULL|string
	 */
	public function getCountryCode()
	{
		if ($this->countryCode === null)
		{
			$binInfo = $this->getBinInfo();
			if ($binInfo)
				$this->countryCode = strtoupper($binInfo->countryCode);
		}
		return $this->countryCode;
	}

	/**
	 * Determine if the length of the credit card number is valid
	 *
	 * @return boolean
	 */
	public function isValidLength()
	{
		if ($this->ccNumber === null)
			return false;
		$ccNumberLength = strlen($this->ccNumber);
		$ccType = $this->getType();
		if ($ccType === null)
			return false;

		// Check length
		return in_array($ccNumberLength, $this->cardLength[$ccType]);
	}

	/**
	 * Determine if the checksum is valid
	 * @return boolean
	 */
	public function isValidChecksum()
	{
		$length = strlen($this->number);
		$value = $this->ccNumber;
		$sum    = 0;
        $weight = 2;

        for ($i = $length - 2; $i >= 0; $i--) {
            $digit = $weight * $value[$i];
            $sum += floor($digit / 10) + $digit % 10;
            $weight = $weight % 2 + 1;
        }

        if ((10 - $sum % 10) % 10 != $value[$length - 1]) {
            return false;
        }

        return true;
	}
}