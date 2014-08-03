<?php
namespace Screwfix;
/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends \Nette\Application\UI\Presenter {
	
	/**
	 * @var Nette\Security\User
	 */
	protected $user;
	
	/**
	 * @var \Nette\Security\Identity
	 */
	protected $identity;
	
	/**
	 * Acl
	 * @var \Nette\Security\Permission 
	 */
	protected $acl;
	
	protected $request;
	
	protected $response;
	
	/**
	 * @var Cookies 
	 */
	protected $cookies;
	
	/**
	 * @var UserFacade 
	 */
	protected $userFacade;
	
	/**
	 * @var PatternFacade 
	 */
	protected $patternFacade;

	/**
	 * @var SysPatternFacade 
	 */
	protected $sysPatternFacade;
	
	/**
	 * @var NoteFacade 
	 */
	protected $noteFacade;
	/**
	 * @var SysNoteFacade 
	 */
	protected $sysNoteFacade;
	
	/**
	 * @var HolidayFacade
	 */
	protected $holidayFacade;
	
	/**
	 * @var BankHolidayFacade
	 */
	protected $bankHolidayFacade;
	
	/** @var \Screwfix\CalendarDateTimeFactory @inject **/
	public $calendarDateFactory;
	
	/** @var \Screwfix\ShiftPatternDateFactory @inject **/
	public $shiftPatternDateFactory;
	
	/** @var \Screwfix\AroundIteratorFactory @inject **/
	public $aroundIteratorFactory;

	protected function startup()
        {
		
//		$date = new \Nette\DateTime();
//		
//		$date->setTimestamp(0);
//		echo $date->format('Y-m-d D'), '<br>';
//		
//		$date->setTimestamp(ShiftPatternDate::START);		
//		echo $date->format('Y-m-d D'), '<br>';
//		$date->setTimestamp(ShiftPatternDate::START + ShiftPatternDate::DAY);		
//		echo $date->format('Y-m-d D'), '<br>';
//		
//		$date->setTimestamp(ShiftPatternDate::START);
//		
//		
//		$date->setDate(1970,1,5);
//		$date->setTime(0, 0, 0);
//		echo $date->format('Y-m-d H:i:s D --- U'), '<br>';
//		
//		for ($i=0; $i<100; $i++)
//		{
//			$ts = ShiftPatternDate::START + (ShiftPatternDate::WEEK * $i);
//			$date->setTimestamp($ts);
//			echo $i, ') ', $date->format('r --- U'), '<br>';
//			
//			
//		}exit;
//
//		
//		
//		$iterator = new CalendarMonthPeriod('1970/02/01', 12);
//		
//		$ShiftPatternDate = new ShiftPatternDate();
//		$ShiftPatternDate->set('2014-05-20 00:00:00');
//		$week = $ShiftPatternDate->week(6);
//		echo "<br>------!!!------<br><pre>";
//		var_dump($week);
//		exit;
//		echo "</pre><br>------!!!------<br>";
//		$patternArray = array(//weeks			
//			0 => array(//days
//				0 => array('15:00', '23:00'),
//				1 => array('15:00', '23:00'),
//				2 => array('15:00', '23:00'),
//				3 => array('15:00', '23:00'),
//				4 => array('15:00', '23:00'),
//				5 => NULL,
//				6 => NULL,
//			),
//			1 => array(//days
//				0 => array('07:00', '15:00'),
//				1 => array('07:00', '15:00'),
//				2 => array('07:00', '15:00'),
//				3 => array('07:00', '15:00'),
//				4 => array('07:00', '15:00'),
//				5 => NULL,
//				6 => array('09:30', '17:30'),
//			),
//			2 => array(//days
//				0 => array('15:00', '23:00'),
//				1 => array('15:00', '23:00'),
//				2 => array('15:00', '23:00'),
//				3 => array('15:00', '23:00'),
//				4 => NULL,
//				5 => NULL,
//				6 => NULL,
//				
//			),
//			3 => array(//days
//				0 => array('07:00', '15:00'),
//				1 => array('07:00', '15:00'),
//				2 => array('07:00', '15:00'),
//				3 => array('07:00', '15:00'),
//				4 => array('07:00', '15:00'),
//				5 => NULL,
//				6 => NULL,
//			),
//			4 => array(//days
//				0 => array('15:00', '23:00'),
//				1 => array('15:00', '23:00'),
//				2 => array('15:00', '23:00'),
//				3 => array('15:00', '23:00'),
//				4 => array('15:00', '23:00'),
//				5 => NULL,
//				6 => array('09:30', '17:30'),
//				
//			),
//			5 => array(//days
//				0 => array('07:00', '15:00'),
//				1 => array('07:00', '15:00'),
//				2 => array('07:00', '15:00'),
//				3 => array('07:00', '15:00'),
//				4 => NULL,
//				5 => NULL,
//				6 => NULL,
//			),
//		);
//		$shiftPattern = new ShiftPatternFilter(new ShiftPatternDate);
//		$shiftPattern->setPattern($patternArray);
//		
//		$bankHolidaysArr = array(
//			'1970-08-05' => 'Summer bh.',
//			'1970-12-25' => 'Another bh.'
//		);
//		
//		$bankHoliday = new BankHolidayFilter($bankHolidaysArr);
//		
//		$sysNote = new SysNoteFilter($this->context->sysNoteRepository->notesBetween('1970-01-01', '1971-01-01'));
//		
//		$calendarData = new CalendarData($iterator);
//		$calendarData->addFilter($shiftPattern)
//			->addFilter($sysNote)
//			->addFilter($bankHoliday)
//			->build();
//		
//		foreach ($calendarData as $key => $val)
//		{
//			echo $key.' ';
//			echo '<br>timestamp: ' . $val->getTimestamp();
//			echo '<br>' . $val->format('D');
//			\Nette\Diagnostics\Debugger::dump($val);
//			echo '<br>';
//		}
//		
//		exit;
		
		// green team 1
		$patternArray = array(//weeks			
			0 => array(//days
				0 => array('15:00', '23:00'),
				1 => array('15:00', '23:00'),
				2 => array('15:00', '23:00'),
				3 => array('15:00', '23:00'),
				4 => array('15:00', '23:00'),
				5 => NULL,
				6 => NULL,
			),
			1 => array(//days
				0 => array('07:00', '15:00'),
				1 => array('07:00', '15:00'),
				2 => array('07:00', '15:00'),
				3 => array('07:00', '15:00'),
				4 => array('07:00', '15:00'),
				5 => NULL,
				6 => array('09:30', '17:30'),
			),
			2 => array(//days
				0 => array('15:00', '23:00'),
				1 => array('15:00', '23:00'),
				2 => array('15:00', '23:00'),
				3 => array('15:00', '23:00'),
				4 => NULL,
				5 => NULL,
				6 => NULL,
				
			),
			3 => array(//days
				0 => array('07:00', '15:00'),
				1 => array('07:00', '15:00'),
				2 => array('07:00', '15:00'),
				3 => array('07:00', '15:00'),
				4 => array('07:00', '15:00'),
				5 => NULL,
				6 => NULL,
			),
			4 => array(//days
				0 => array('15:00', '23:00'),
				1 => array('15:00', '23:00'),
				2 => array('15:00', '23:00'),
				3 => array('15:00', '23:00'),
				4 => array('15:00', '23:00'),
				5 => NULL,
				6 => array('09:30', '17:30'),
				
			),
			5 => array(//days
				0 => array('07:00', '15:00'),
				1 => array('07:00', '15:00'),
				2 => array('07:00', '15:00'),
				3 => array('07:00', '15:00'),
				4 => NULL,
				5 => NULL,
				6 => NULL,
			),
		);
		
//		// red team 4
//		$patternArray = array(//weeks			
//			0 => array(//days
//				0 => array('07:00','15:00'),
//				1 => array('07:00','15:00'),
//				2 => array('07:00','15:00'),
//				3 => array('07:00','15:00'),
//				4 => array('07:00','15:00'),
//				5 => NULL,
//				6 => NULL,
//			),
//			1 => array(//days
//				0 => array('15:00','23:00'),
//				1 => array('15:00','23:00'),
//				2 => array('15:00','23:00'),
//				3 => array('15:00','23:00'),
//				4 => array('15:00','23:00'),
//				5 => NULL,
//				6 => array('09:30', '17:30'),
//			),
//			2 => array(//days
//				0 => array('07:00','15:00'),
//				1 => array('07:00','15:00'),
//				2 => array('07:00','15:00'),
//				3 => array('07:00','15:00'),
//				4 => NULL,
//				5 => NULL,
//				6 => NULL,
//				
//			),
//			3 => array(//days
//				0 => array('15:00','23:00'),
//				1 => array('15:00','23:00'),
//				2 => array('15:00','23:00'),
//				3 => array('15:00','23:00'),
//				4 => array('15:00','23:00'),
//				5 => NULL,
//				6 => NULL,
//			),
//			4 => array(//days
//				0 => array('07:00','15:00'),
//				1 => array('07:00','15:00'),
//				2 => array('07:00','15:00'),
//				3 => array('07:00','15:00'),
//				4 => array('07:00','15:00'),
//				5 => NULL,
//				6 => array('09:30', '17:30'),
//				
//			),
//			5 => array(//days
//				0 => array('15:00','23:00'),
//				1 => array('15:00','23:00'),
//				2 => array('15:00','23:00'),
//				3 => array('15:00','23:00'),
//				4 => NULL,
//				5 => NULL,
//				6 => NULL,
//			),
//		);
//		
		$shiftPattern = new ShiftPatternFilter(new ShiftPatternDate);
		$shiftPattern->setPattern($patternArray);
		
		
                parent::startup();
		
		// cookies setup
		$this->cookies = $this->context->cookies;
		$this->registerCookiesValidators();
		
		$this->request = $this->context->httpRequest;
		$this->response = $this->context->httpResponse;
		
		
		
		// use absolute urls
		$this->absoluteUrls = true;
		
		$this->user = $this->getUser();
		$this->identity = $this->user->getIdentity();
		$this->acl = $this->context->authorizator;
		
		// facades
		$this->userFacade = $this->context->userFacade;
		$this->patternFacade = $this->context->patternFacade;
		$this->sysPatternFacade = $this->context->sysPatternFacade;
		$this->noteFacade = $this->context->noteFacade;
		$this->sysNoteFacade = $this->context->sysNoteFacade;
		$this->holidayFacade = $this->context->holidayFacade;
		$this->bankHolidayFacade = $this->context->bankHolidayFacade;
		
		// check if acces is allowed for user
		if (!$this->isAllowed())
		{
			throw new \Screwfix\UnauthorizedAcces();
		}
        }
	
	public function beforeRender()
	{
		$this->template->identity = $this->user->getIdentity();
		$this->template->acl = $this->acl;		
		$this->template->userUrl = $this->user->isLoggedIn() ? $this->link(':Front:User:', $this->identity->username) : null;		
		$this->template->isStickyFooter = $this->isStickyFooter();
	}


	/**
	 * Is user allowed to acces this presenter and action.
	 * 
	 * @throws Nette\InvalidStateException
	 * @return bool
	 */
	protected function isAllowed()
	{
		$role = $this->user->isLoggedIn() ? $this->user->getIdentity()->role : $this->user->guestRole;
		$resource = $this->getResource();
		return $this->acl->isAllowed($role, $resource);
	}
	
	/**
	 * Creates acl resourse to be used by $this->isAllowed() (eg. Front:User)
	 * 
	 * @return string
	 */
	protected function getResource()
	{
		return $this->name;
	}
	
	public function handleSignOut()
	{
	    $this->getUser()->logout();
	    $this->redirect(':Front:Home:');
	}
	
	public function isStickyFooter()
	{
		return ($this->name !== "Front:Home");
	}
	
	/**
	 * It is automatically launched in BasePresenter::startup. 
	 * If you need to register any cookies validators implement it in a children methods.
	 */
	protected function registerCookiesValidators(){
		
	}

}
