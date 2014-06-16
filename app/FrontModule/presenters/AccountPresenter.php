<?php
namespace FrontModule;

use Nette\Application\UI\Form;

/**
 * AccountPresenter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class AccountPresenter extends BaseaccountPresenter {
	
	protected function createComponentCredentialsForm() 
	{
		$form = parent::createComponentCredentialsForm();
		
		$form->addSubmit('edit', 'Edit')
			->setAttribute('class', 'button');
		
		return $form;
	}
	
	protected function createComponentSetupForm() {
		$sysPatternSelection = $this->sysPatternFacade->getFormSelection();	
		
		$form = new Form($this, 'setupForm');
		
		$form->addSelect('sysPattern', 'Select pattern', $sysPatternSelection);
		
		reset($sysPatternSelection);
		$defaultPattern = $this->buildDefaultInputPattern(\Nette\Utils\Json::decode(key($sysPatternSelection)));
		
		$form['pattern'] = $this->patternInputEditFactory->create();
		$form['pattern']->setDefaultValue($defaultPattern);
		
		$form->addSubmit('send', 'Send')
			->setAttribute('class', 'button');
		
		$form->onSuccess[] = $this->setupFormSubmitted;
		
		return $form;
	}
	
	public function setupFormSubmitted() 
	{

	}
}
