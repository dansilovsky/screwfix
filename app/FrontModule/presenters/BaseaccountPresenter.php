<?php
namespace FrontModule;

use Nette\Application\UI\Form,
	Screwfix\BaseaccountForm;

/**
 * BaseaccountPresenter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
abstract class BaseaccountPresenter extends BasePresenter {
	
	/** @var \Screwfix\PatternInputEditFactory @inject */
	public $patternInputEditFactory;
	
	/** @var \Screwfix\PatternInputOverviewFactory @inject */
	public $patternInputOverviewFactory;
	
	/** @var \Screwfix\ShiftPatternIteratorFactory @inject */
	public $patternIteratorFactory;
	
	/** @var \Screwfix\ShiftPatternFilterFactory @inject */
	public $shiftPatternFilterFactory;
	
	public function credentialsFormSubmitted(Form $form) {
		
	}
	
	/**
	 * Builds default input pattern value array from pattern array.
	 * 
	 * @param array $pattern
	 * @return array
	 */
	public function buildDefaultInputPattern(array $pattern)
	{		
		$result = array(
			'pattern' => array(), 
			'firstDay' => null
		);
		
		$patternIterator = $this->patternIteratorFactory->create($pattern);		
		
		foreach($patternIterator as $week)
		{
			$result['pattern'][] = $week;
		}
		
		$firstDay = $this->calendarDateFactory->create()
			->floor(\Screwfix\CalendarDateTime::W)
			->format(\Screwfix\DateTime::FORMAT_DATE);
		
		$result['firstDay'] = $firstDay;
		
		return $result;
	}
	
	/**
	 * Adjusts patterns weeks order by given date of first day of pattern.
	 * Commonly pattern sent by client from signup or edit.
	 * 
	 * @param array $pattern
	 * @param string $date a date of first day in pattern. The format of date is yyyy-mm-dd
	 * @return array adjusted pattern
	 */
	public function adjustPattern(array $pattern, $date)
	{
		$adjustedPattern = array();
		
		$patternCount = count($pattern);
		
		$patternWeekNumber = $this->shiftPatternDateFactory->create($date)
			->week($patternCount);
		
		$aroundIterator = $this->aroundIteratorFactory->create($pattern)
			->setStart($patternCount - $patternWeekNumber);		
		
		foreach($aroundIterator as $week)
		{
			$adjustedPattern[] = $week;
		}
		
		return $adjustedPattern;
	}	
}
