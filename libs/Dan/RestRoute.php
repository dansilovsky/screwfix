<?php

namespace Dan;

class RestRoute extends \Nette\Application\Routers\Route {

	const METHOD_POST = 4;
	const METHOD_GET = 8;
	const METHOD_PUT = 16;
	const METHOD_DELETE = 32;
	const RESTFUL = 64;

	public function match(\Nette\Http\IRequest $httpRequest)
	{
		$httpMethod = $httpRequest->getMethod();
		
		$flags = $this->getFlags();

		if (($flags & self::RESTFUL) == self::RESTFUL)
		{
			$presenterRequest = parent::match($httpRequest);
			if ($presenterRequest != NULL)
			{
				switch ($httpMethod)
				{
					case 'GET':
						$action = 'default';
						break;
					case 'POST':
						$action = 'create';
						break;
					case 'PUT':
						$action = 'update';
						break;
					case 'DELETE':
						$action = 'delete';
						break;
					default:
						$action = 'default';
				}

				$params = $presenterRequest->getParameters();
				$params['action'] = $action;
				$presenterRequest->setParameters($params);
				return $presenterRequest;
			}
			else
			{
				return NULL;
			}
		}

		if (($flags & self::METHOD_POST) == self::METHOD_POST && $httpMethod != 'POST')
		{
			return NULL;
		}

		if (($flags & self::METHOD_GET) == self::METHOD_GET && $httpMethod != 'GET')
		{
			return NULL;
		}

		if (($flags & self::METHOD_PUT) == self::METHOD_PUT && $httpMethod != 'PUT')
		{
			return NULL;
		}

		if (($flags & self::METHOD_DELETE) == self::METHOD_DELETE && $httpMethod != 'DELETE')
		{
			return NULL;
		}

		return parent::match($httpRequest);
	}

}