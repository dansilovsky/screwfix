<?php

namespace FrontModule;

/**
 * SignupPresenter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class SignupPresenter extends BasePresenter {

	
	
	protected function createComponentSignUpForm()
	{
		$form = new Form();
		$form->addText('username', 'Username:', 30, 30);
		$form->addText('firstname', 'First name', 50, 50);
		$form->addText('surname', 'Surname', 50, 50);
		$form->addPassword('password', 'Password:', 30);
		$form->addCheckbox('remember', 'Remember me');
		$form->addSubmit('signin', 'Sign up');
		$form->onSuccess[] = $this->signUpFormSubmitted;
		return $form;
	}
	
	
	
	/**
	 * 
	 * @param  Nette\Application\UI\Form $form
	 * @throws Nette\Security\AuthenticationException
	 */
	public function signUpFormSubmitted(Form $form)
	{
		
	}
}
