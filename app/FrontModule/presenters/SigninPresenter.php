<?php
namespace FrontModule;

use Nette\Application\UI\Form;

/**
 * Presenter
 */
class SigninPresenter extends BasePresenter {
	
	protected function createComponentSignInForm()
	{
		$form = new Form($this, 'signInForm');
		$form->addText('username', null, 30, 30)
			->setAttribute('placeholder', 'Username')
			->setRequired('Enter an username please.')
			->addRule(Form::MIN_LENGTH, 'Username must contain at least %d characters.', 3)
			->addRule(Form::MAX_LENGTH, 'Username is too long. Use maximum of %d characters.', 60)
			->addRule(Form::PATTERN, 'Username can contain only alphabetical characters or underscore.', '\w{3,60}');
		$form->addPassword('password', null, 30)
			->setAttribute('placeholder', 'Password')
			->setRequired('Enter a password please.');
		$form->addCheckbox('remember', 'Remember me');
		$form->addSubmit('signin', 'Sign in')
			->setAttribute('class', 'button');
		// time limit is 30min. (60 * 30 = 1800)
		$form->addProtection('Time limit has expired. Please send the form again.', 1800);
		$form->onSuccess[] = $this->signInFormSubmitted;
		return $form;
	}
	
	/**
	 * 
	 * @param  Nette\Application\UI\Form $form
	 * @throws Nette\Security\AuthenticationException
	 */
	public function signInFormSubmitted(Form $form)
	{
		try
		{
			$user = $this->getUser();
			$values = $form->getValues();
			if ($values->remember)
			{
				$user->setExpiration('+14 days', FALSE);
			}
			$user->login($values->username, $values->password);
			$this->redirect('Home:');
		}
		catch (\Nette\Security\AuthenticationException $e)
		{
			$form->addError('Invalid username or password.');
		}
	}
        
}