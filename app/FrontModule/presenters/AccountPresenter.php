<?php
namespace FrontModule;

use Nette\Application\UI\Form,
	Screwfix\BaseaccountForm;

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
		
		$form = new BaseaccountForm($this, 'setupForm');
		
		$form->addSelect('sysPatternSelect', 'Select pattern', $sysPatternSelection);
		
		reset($sysPatternSelection);
		$defaultPattern = $this->buildDefaultInputPattern(\Nette\Utils\Json::decode(key($sysPatternSelection)));
		
		$form['patternInput'] = $this->patternInputEditFactory->create();
		$form['patternInput']->setDefaultValue($defaultPattern);
		
		$form->addSubmit('send', 'Send')
			->setAttribute('class', 'button');
		
		$form->onSuccess[] = $this->setupFormSubmitted;
		
		return $form;
	}
	
	public function setupFormSubmitted() 
	{

	}
}
