<?php
namespace FrontModule;

use Nette\Application\UI\Form;

/**
 * Presenter
 */
class SigninPresenter extends BasePresenter {
        
	private $userRepository;
	
	protected function startup()
	{
		parent::startup();
		
		$this->userRepository = $this->context->userRepository;
	}
	
	protected function createComponentSignInForm()
	{
		$form = new Form($this, 'signInForm');
		$form->addText('username', null, 30, 30)
			->setAttribute('placeholder', 'Username')
			->setRequired('Enter an username please.');
		$form->addPassword('password', null, 30)
			->setAttribute('placeholder', 'Password')
			->setRequired('Enter a password please.');
		$form->addCheckbox('remember', 'Remember me');
		$form->addSubmit('signin', 'Sign in');
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
			$form->addError('Neplatné uživatelské jméno nebo heslo.');
		}
	}
        
}