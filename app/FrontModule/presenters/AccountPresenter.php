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
		$form = new BaseaccountForm($this, 'credentialsForm');
		
		$form->addText('username', null, 30, 30)
			->setAttribute('placeholder', 'Username')
			->setDefaultValue($this->identity->username)
			->addRule(Form::MIN_LENGTH, 'Username must contain at least %d characters.', 3)
			->addRule(Form::MAX_LENGTH, 'Username is too long. Use maximum of %d characters.', 60)
			->addRule(Form::PATTERN, 'Username can contain only alphabetical characters or underscore.', '\w{3,60}');
		$form->addText('email', 'Email', 30, 30)
			->setAttribute('placeholder', 'Email')
			->setDefaultValue($this->identity->email)
			->setRequired('Enter an email please.')
			->addRule(Form::MAX_LENGTH, 'Email is too long. Use maximum of %d characters.', 255)
			->addRule(Form::EMAIL, 'Invalid email address.');
		$form->addProtection('Time limit has expired. Please send the form again.', 1800);
		$form->onSuccess[] = $this->credentialsFormSubmitted;
		
		$form->addSubmit('edit', 'Edit')
			->setAttribute('class', 'button');
		
		return $form;
	}
	
	public function credentialsFormSubmitted(Form $form)
	{
		$formValues = $form->getValues();
		
		$userArr = array();
		
		$identity = $this->identity;
		
		if ($formValues->username !== $identity->username)
		{
			$userArr['username'] = $formValues->username;
		}
		
		if ($formValues->email !== $identity->email)
		{
			$userArr['email'] = $formValues->email;
		}
		
		if ($userArr)
		{
			try
			{
				$this->userFacade->update($identity->id, $userArr);
				
				if (isset($userArr['username']))
				{
					$identity->username = $userArr['username'];
				}
				
				if (isset($userArr['email']))
				{
					$identity->email = $userArr['email'];
				}
			} 
			catch (Exception $ex) 
			{
				$form->addError('Sorry, something went wrong. Please try again.');
			}
		}
		
	}
	
	protected function createComponentPasswordForm()
	{
		$form = new BaseaccountForm($this, 'passwordForm');
		
		$form->addPassword('password', null, 30)
			->setAttribute('placeholder', 'Current password')
			->setRequired('Enter the current password please.')
			->addRule(Form::MIN_LENGTH, 'Password must contain at least %d characters.', 6)	;
		$form->addPassword('newPassword', null, 30)
			->setAttribute('placeholder', 'New password')
			->setRequired('Enter a new password please.')
			->addRule(Form::NOT_EQUAL, 'New password must be different from the old one.', $form['password'])
			->addRule(Form::MIN_LENGTH, 'Password must contain at least %d characters.', 6);
		$form->addPassword('verifyPassword', null, 30)
			->setAttribute('placeholder', 'Retype new password')
			->setRequired('Reenter a new password please.')
			->addRule(Form::EQUAL, 'Passwords do not match.', $form['newPassword']);
		$form->addProtection('Time limit has expired. Please send the form again.', 1800);
		$form->onSuccess[] = $this->passwordFormSubmitted;
		
		$form->addSubmit('edit', 'Edit')
			->setAttribute('class', 'button');
		
		return $form;
	}
	
	public function passwordFormSubmitted(Form $form)
	{
		$formValues = $form->getValues();
		
		$hashedPassword = \Screwfix\Authenticator::calculateHash($formValues->password, $this->identity->password);
		
		if ($hashedPassword !== $this->identity->password)
		{			
			$form['password']->addError('Your password was incorrect.');
		}
		else
		{
			try
			{				
				$hashedNewPassword = \Screwfix\Authenticator::calculateHash($formValues->newPassword);

				$this->userFacade->update($this->identity->id, array('password' => $hashedNewPassword));
			} 
			catch (Exception $ex) 
			{
				$form->addError('Sorry, something went wrong. Please try again.');
			}
		}
	}
	
	protected function createComponentSetupForm() 
	{
		$userPatternSelection = $this->patternFacade->getFormSelection($this->identity->id);
		
		$sysPatternSelection = $this->sysPatternFacade->getFormSelection();
		
		// if users pattern equals to any of sys patterns then the equal sys pattern is left out
		$patternSelection = $userPatternSelection + $sysPatternSelection;
		
		$form = new BaseaccountForm($this, 'setupForm');
		
		$form->addSelect('sysPatternSelect', 'Select pattern', $patternSelection);
		
		reset($patternSelection);
		$defaultPattern = $this->buildDefaultInputPattern(\Nette\Utils\Json::decode(key($patternSelection)));
		
		$form['patternInput'] = $this->patternInputOverviewFactory->create();
		$form['patternInput']->setDefaultValue($defaultPattern);
		
		$form->addSubmit('send', 'Send')
			->setAttribute('class', 'button');
		
		$form->onSuccess[] = $this->setupFormSubmitted;
		
		return $form;
	}
	
	public function setupFormSubmitted(Form $form) 
	{
		$formValues = $form->getValues();

		try
		{
			$pattern = $this->adjustPattern($formValues->patternInput['pattern'], $formValues->patternInput['firstDay']);
			
			$patternFilter = $this->shiftPatternFilterFactory->create($pattern);

			$this->patternFacade->update($this->identity->id, $patternFilter);
		}
		catch (\Exception $ex)
		{
			$form->addError('Sorry, something went wrong. Please try again.');
		}
	}
}
