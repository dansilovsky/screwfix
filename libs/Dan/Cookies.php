<?php

namespace Dan;

/**
 * Stores sent cookies, validates them. Cookies can be retrieved via array acces.
 *
 * @author Daniel Silovsky
 * @copyright (c) 2013, Daniel Silovsky
 * @license http://www.screwfix-calendar.co.uk/license
 */
class Cookies implements \ArrayAccess {

	/**
	 * @var array 
	 */
	private $_cookies = array();
	
	/**
	 * Array of cookies that have been validated and passed
	 * 
	 * @var array 
	 */
	private $_validCookies = array();
	
	/**
	 * Array of validator callbacks.
	 * @var array
	 */
	private $_callbacks = array();
	
	/**
	 * @var array
	 */
	private $_options = array(
		'time' => 0,
		'path' => null,
		'domain' => null,
		'secure' => null,
		'httpOnly' => null,
	);
	
	/**
	 *
	 * @var \Nette\Http\Request 
	 */
	private $_request;
	
	/**
	 * @var \Nette\Http\Response 
	 */
	private $_response;
	
	public function __construct(array $options = array(), \Nette\Http\Request $request, \Nette\Http\Response $response)
	{
		$this->_cookies = $request->getCookies();
		$this->_request = $request;
		$this->_response = $response;	
		$this->setOptions($options);
	}
	
	/**
	 * Sets options
	 * 
	 * @param array|string  $options  either array of options eg. array('time' => '+ 100 days', 'reset' => false) or name of options eg. 'time'
	 * @param mixed         $value
	 */
	public function setOptions($options, $value = null)
	{
		if (is_array($options))
		{
			$this->_options = array_merge($this->_options, $options);
		}
		else
		{
			$this->_options[$options] = $value;
		}
	}
	
	public function getOptions()
	{
		return $this->_options;
	}
	
	/**
	 * Before returning cookie, checks if cookie is valid.
	 * 
	 * @param  string  $name
	 * @param  mixed   $default
	 * @return string|null
	 * @throws BadDataSentException
	 * @throws CookiesException
	 */
	public function get($name, $default = null)
	{
		if (isset($this->_validCookies[$name]))
		{
			// if cookie has already been validated and passed validation return it
			return $this->_validCookies[$name];
		}
		
		if (isset($this->_cookies[$name]))
		{
			if (isset($this->_callbacks[$name]))
			{
				$cookie = $this->_cookies[$name];
				$callback = $this->_callbacks[$name];

				if ($callback($cookie) === true)
				{
					// cookie is valid, add it to the valid cookies array and return it
					$this->_validCookies[$name] = $cookie;
					
					return $cookie;
				}
				else
				{
					// invalid cookie, throw exception
					throw new BadDataSentException('Invalid cookie');
				}
			}
			
			// for given cookie has not been set corresponding validator callback
			throw new \Dan\CookiesException('Validator callback for given cookie is not set.');
			
		}
		
		// cookie does not exist return default value
		return $default;
	}
	
	/**
	 * Sends a cookie.
	 * 
	 * @param type $name
	 * @param type $value
	 * @param type $time       if time argument is not given, uses time given in options
	 * @param type $path
	 * @param type $domain
	 * @param type $secure
	 * @param type $httpOnly
	 */
	public function set($name, $value, $time = null, $path = null, $domain = null, $secure = null, $httpOnly = null)
	{
		if ($time === null)
		{
			$time = $this->_options['time'];
		}
		
		if ($path === null)
		{
			$path = $this->_options['path'];
		}
		
		if ($domain === null)
		{
			$domain = $this->_options['domain'];
		}
		
		if ($secure === null)
		{
			$secure = $this->_options['secure'];
		}
		
		if ($httpOnly === null)
		{
			$httpOnly = $this->_options['httpOnly'];
		}
		
		$this->_response->setCookie($name, $value, $time, $path, $domain, $secure, $httpOnly);
	}
	
	public function delete($name, $path = null, $domain = null, $secure = null)
	{
		$this->_response->deleteCookie($name, $path, $domain, $secure);
	}
	
	/**
	 * Registers validating callabacks.
	 * 
	 * @param   string     $name       name must be exactly the same as the name of corresponding cookie
	 * @param   callback   $callback   callback must take as an argument a value of cookie and returns true if validation passed otherwise false.
	 * @return  Screwfix\Cookie
	 */
	public function registerValidator($name, $callback)
	{
		$this->_callbacks[$name] =  $callback;
		
		return $this;
	}
	
	public function offsetSet($offset, $value)
	{
		if (is_null($offset))
		{
			$this->_cookies[] = $value;
		}
		else
		{
			$this->_cookies[$offset] = $value;
		}
	}

	public function offsetExists($offset)
	{
		return isset($this->_cookies[$offset]);
	}

	public function offsetUnset($offset)
	{
		unset($this->_cookies[$offset]);
	}

	/**
	 * Before returning cookie, checks if cookie is valid.
	 * 
	 * @param  string $offset
	 * @return string|null
	 * @throws BadDataSentException
	 * @throws CookiesException
	 */
	public function offsetGet($offset)
	{
		return $this->get($offset);
	}

}
