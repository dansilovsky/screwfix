<?php
namespace FrontModule;

use Nette\Application\UI\Form;

/**
 * SignupPresenter
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class SignupPresenter extends BasePresenter {

	private $userFacade;

	protected function startup()
	{
		parent::startup();

		$this->userFacade = $this->context->userFacade;
	}

	protected function createComponentSignUpForm()
	{
		$form = new Form($this, 'signUpForm');
		$form->addText('username', null, 30, 30)
			->setAttribute('placeholder', 'Username')
			->setRequired('Enter an username please.')
			->addRule(Form::MIN_LENGTH, 'Username must contain at least %d characters.', 3)
			->addRule(Form::MAX_LENGTH, 'Username is too long. Use maximum of %d characters.', 60)
			->addRule(Form::PATTERN, 'Username can contain only alphabetical characters or underscore.', '\w{3,60}');
		$form->addText('email', null, 30, 30)
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
		$form->addCheckbox('remember', 'Remember me');
		$form->addSubmit('signup', 'Create account');
		// time limit is 30min. (60 * 30 = 1800)
		$form->addProtection('Time limit has expired. Please send the form again.', 1800);
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
			$formValues = $form->getValues();

			$userUsernameRow = $this->userFacade->getByUsername($formValues->username);

			$userEmailRow = $this->userFacade->getByEmail($formValues->email);

			if ($userUsernameRow !== false || $userEmailRow !== false)
			{
				if ($userUsernameRow !== false)
				{
					$form['username']->addError('This username is already taken. Please use different one.');
				}

				if ($userEmailRow !== false)
				{
					$form['email']->addError('This email is already taken. Please use different one.');
				}
			}
			else {
				$hashedPassword = \Screwfix\Authenticator::calculateHash($formValues->password);
				
				$userArr = array(
					'username' => $formValues->username,
					'role' => 'member',
					'email' => $formValues->email,
					'password' => $hashedPassword
				);
				
				
//				try 
//				{
					$this->userFacade->save($userArr);
					
					$user = $this->getUser();
					
					if ($formValues->remember)
					{
						$user->setExpiration('+14 days', FALSE);
					}
					
					$user->login($formValues->username, $formValues->password);
					
					$this->redirect('Account:setup');
//				} 
//				catch (\Exception $ex) 
//				{
//					echo "<br>------!!!------<br><pre>";
//					var_dump($ex);
//					exit;
//					echo "</pre><br>------!!!------<br>";
//					$form->addError('Sorry, something went wrong. Please try again.');
//				}				
				
			}
	}
}
