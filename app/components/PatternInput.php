<?php
namespace Screwfix;

use Nette\Forms\Form,
	Nette\Utils\Html;

/**
 * PatternInput
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class PatternInput extends \Nette\Forms\Controls\BaseControl
{
	/**
	 * Array containg first day of pattern and pattern itself
	 * @var Array 
	 */
	private $_value;
	
	/**
	 * Json string containg first day of pattern and pattern itself
	 * @var string 
	 */
	private $_input;
	
	/**
	 * Pattern array as extracted from input.
	 * @var array 
	 */
	private $_pattern;
	
	/**
	 * Date string extracted from input.
	 * @var string 
	 */
	private $_firstDay;
	
	private $_template;
	
	/**
	 * @var DateTime
	 */
	private $_date;

	/**
	 * @return ShiftPatternDate
	 */
	public function __construct(Template $template, DateTime $date, $templateFileName)
	{
		parent::__construct(null);
		$this->addRule(__CLASS__ . '::validatePattern', 'Shift pattern is invalid.');
		
		$this->_template = $template;
		$this->_template->setFile(__DIR__ . "/$templateFileName");
		
		$this->_template->registerHelper('dayName', function($dayNumber) { return DateTime::dayName($dayNumber); });
		
		$this->_template->registerHelper('padTime', function($t) { 
			if ($t < 10) {
				return '0' . $t;
			}
			return $t;			
		});
		
		$this->_template->registerHelper('selectOption', function ($timeUnit, $time, $type) {
			$timeUnit = (int) $timeUnit;
			list($hour, $minute) = explode(':', $time);
			
			$hour = (int) $hour;
			$minute = (int) $minute;
			
			if ($type === 'h')
			{
				return $timeUnit === $hour ? 'selected="selected"' : '';
			}
			
			if ($type === 'm')
			{
				return $timeUnit === $minute ? 'selected="selected"' : '';
			}
		});
		
		// returns curren formated date and moves date one day forward
		$this->_template->registerHelper('day', function($date) {
			$day = $date->format('j F');
			$date->addDay();
			
			return $day;
		});
		
		$this->_date = $date;
	}

	/**
	 * @param array $value array containing firstDay and pattern
	 * @throws PatternInput_InvalidData_Exception
	 */
	public function setValue($value)
	{	
		if ($value)
		{
			$this->_input = \Nette\Utils\Json::encode($value);
			
			$this->_value = $value;
			
			if (!self::validatePattern($this))
			{
				throw new PatternInput_InvalidData_Exception;
			}
		}
		else
		{
			$this->_value = null;
		}
	}

	/**
	 * @return array|null
	 */
	public function getValue()
	{
		return self::validatePattern($this)
			? $this->_value
			: null;
	}


	public function loadHttpData()
	{
		$value = $this->getHttpData(Form::DATA_LINE);
		
		$this->_input = $value;
		
		$this->_value = \Nette\Utils\Json::decode($value, \Nette\Utils\Json::FORCE_ARRAY);
	}


	/**
	 * Generates control's HTML element.
	 */
	public function getControl()
	{		
		$this->_template->name = $this->getHtmlName();
		$this->_template->id = $this->getHtmlId();
		$this->_template->pattern = $this->_value['pattern'];
		$this->_template->input = $this->_input;		
		$this->_template->date = $this->_date->modify($this->_value['firstDay']);
		$this->_template->compile();
		
		return $this->_template;	
	}

	/**
	 * @return bool
	 */
	public static function validatePattern(\Nette\Forms\IControl $control)
	{
		$pattern = $control->_value['pattern'];
		
		$firstDay = $control->_value['firstDay'];
		
		foreach ($pattern as $week)
		{			
			foreach ($week as $day)
			{
				if ($day !== null)
				{
					if (
						!preg_match('/^\d{2}:\d{2}$/', $day[0]) &&
						!preg_match('/^\d{2}:\d{2}$/', $day[1])

					) 
					{ 
						return false;
					}
				}
			}
		}
		
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $firstDay))
		{
			return false;
		}
		
		return true;
	}
	
	/**
	 * Builds pattern array from input pattern array
	 * 
	 * @param array $inputPattern
	 * @return array
	 */
	static public function buildPatternArray(array $inputPattern)
	{		
		$pattern = array();
		
		$previousWeekNum = -1;
		
		foreach ($inputPattern as $key => $day)
		{
			if ($key === 0)
			{
				// its firstDay element
				continue;
			}
			$dayValues = list($weekNum, $dayNum, $from) = explode(',', $day);
			
			$to = isset($dayValues[3]) ? $dayValues[3] : null;
			
			$weekNum = (int) $weekNum;
			$dayNum = (int) $dayNum;
			
			if ($weekNum !== $previousWeekNum)
			{
				$previousWeekNum = $weekNum;
				$pattern[$weekNum] = array();		
			}
			
			if ($from !== 'null')
			{
				$pattern[$weekNum][$dayNum] = array($from, $to);
			}
			else
			{
				$pattern[$weekNum][$dayNum] = null;
			}
		}
		
		return $pattern;
	}
}

