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
	private $_pattern;
	
	private $_template;
	
	private $_request;

	public function __construct(Template $template, \Nette\Http\Request $request)
	{
		parent::__construct(null);
		$this->addRule(__CLASS__ . '::validatePattern', 'Shift pattern is invalid.');
		
		$this->_template = $template;
		$this->_template->setFile(__DIR__ . '/PatternInput.latte');
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
		
		$this->_request = $request;
	}


	public function setValue($value)
	{	
		if ($value)
		{			
			$this->_pattern = $value;
			
			if (!self::validatePattern($this))
			{
				throw new PatternInput_InvalidData_Exception;
			}
		}
		else
		{
			$this->_pattern = null;
		}
	}

	/**
	 * @return DateTime|NULL
	 */
	public function getValue()
	{
		return self::validatePattern($this)
			? $this->_pattern
			: null;
	}


	public function loadHttpData()
	{
		$name = $this->getHtmlName() . '[]';
		$this->_pattern = $this->getHttpData(Form::DATA_LINE, '[]');
	}


	/**
	 * Generates control's HTML element.
	 */
	public function getControl()
	{
		
		$pattern = $this->_pattern ? self::buildPatternArray($this->_pattern) : array();
		
		$this->_template->name = $this->getHtmlName();
		$this->_template->pattern = $pattern;
		$this->_template->inputPattern = $this->_pattern;
		$this->_template->compile();
		
		return $this->_template;	
	}

	/**
	 * @return bool
	 */
	public static function validatePattern(\Nette\Forms\IControl $control)
	{
		$pattern = $control->_pattern;
		
		foreach ($pattern as $day)
		{
			if (!preg_match('/^\d,\d,(null|(\d{2}:\d{2},\d{2}:\d{2}))$/', $day)) { return false; }
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
		
		foreach ($inputPattern as $day)
		{
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

