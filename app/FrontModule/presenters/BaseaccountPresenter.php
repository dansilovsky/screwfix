<?php
namespace FrontModule;

use Nette\Application\UI\Form;

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
	
	protected function createComponentCredentialsForm() 
	{
		$form = new Form($this, 'credentialsForm');
		
		$form->addSelect('select', 'Select', array('option1' => 'option1', 'option2' => 'option2',));
		$form->addCheckbox('remember', 'Remember me');
		
		$form->addText('username', null, 30, 30)
			->setAttribute('placeholder', 'Username')
			->setAttribute('value', $this->identity->username)
			->addRule(Form::MIN_LENGTH, 'Username must contain at least %d characters.', 3)
			->addRule(Form::MAX_LENGTH, 'Username is too long. Use maximum of %d characters.', 60)
			->addRule(Form::PATTERN, 'Username can contain only alphabetical characters or underscore.', '\w{3,60}');
		$form->addText('email', 'Email', 30, 30)
			->setAttribute('placeholder', 'Email')
			->setRequired('Enter an email please.')
			->addRule(Form::MAX_LENGTH, 'Email is too long. Use maximum of %d characters.', 255)
			->addRule(Form::EMAIL, 'Invalid email address.');
		$form->addPassword('password', null, 30)
			->setAttribute('placeholder', 'Password')
			->setRequired('Enter a password please.')
			->addRule(Form::MIN_LENGTH, 'Password must contain at least %d characters.', 6);
		$form->addPassword('verifyPassword', null, 30)
			->setAttribute('placeholder', 'Retype password')
			->setRequired('Reenter a password please.')
			->addRule(Form::EQUAL, 'Passwords do not match.', $form['password']);
		$form->addProtection('Time limit has expired. Please send the form again.', 1800);
		$form->onSuccess[] = $this->credentialsFormSubmitted;
		
		return $form;
	}
	
	public function credentialsFormSubmitted(Form $form) {
		
	}
	
	protected function createComponentSetupForm() {
		$sysPatternSelection = $this->sysPatternFacade->getFormSelection();	
		
		$form = new Form($this, 'setupForm');
		
		$form->addSelect('sysPattern', 'Select pattern', $sysPatternSelection);
		
		reset($sysPatternSelection);
		$defaultPattern = $this->buildDefaultInputPattern(\Nette\Utils\Json::decode(key($sysPatternSelection)));
		
		$form['pattern'] = $this->patternInputFactory->create();
		$form['pattern']->setDefaultValue($defaultPattern);
		
		$form->addSubmit('send', 'Send')
			->setAttribute('class', 'button');
		
		$form->onSuccess[] = $this->setupFormSubmitted;
		
		return $form;
	}
	
	public function setupFormSubmitted() 
	{

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
	
}
