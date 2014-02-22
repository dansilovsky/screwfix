<?php
namespace FrontModule;

/**
 * Presenter
 */
class UserPresenter extends \Screwfix\BasePresenter {


	protected function startup()
	{
		parent::startup();
	}

	public function renderDefault($user)
	{
		$this->template->content = 'I am User:default - username:' . $user;
	}
        
        public function renderAccount($user)
        {
                $this->template->content = 'I am User:account - username:' . $user;   
        }
	
	/**
	 * Is user allowed to acces this presenter.
	 * Overrides the method that is using proper acl
	 * 
	 * @return bool
	 */
	protected function isAllowed()
	{
		// user has to be logged in and logged in user must match user given to this presenter
		return ($this->user->isLoggedIn() && $this->user->getIdentity()->username === $this->params['user']);
	}
}