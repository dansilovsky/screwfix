<?php
namespace AdminModule;

/**
 * UserRepository
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/licence
 */
class AdminPresenter extends BasePresenter {
        
        public function renderDefault()
        {
                $this->template->content = 'I am Admin:default.';
        }
	
	public function renderCalendar()
	{
		// vyrenderuje adminovsky kalendar i kdyz by to mozna mohlo byt primo v renderDefault pak by bylo treba prepsat acl v configuraci
		$this->template->content = 'I am Admin:calendar.';
	}
	
	/**
	 * Creates acl resourse to by used in $this->isAllowed()
	 * In contrast to overriden method ads an action (eg. Admin:Admin:calendar)
	 * 
	 * @return string
	 */
	public function getResource()
	{
		return $this->name . ':' . $this->action;
	}
        
}