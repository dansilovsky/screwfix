<?php

namespace Screwfix;

/**
 * Unauthorized acces exeption
 */
class UnauthorizedAcces extends \Exception {
	
}

/**
 * Thrown when client sends bad data (get, post, cookie, session, ...). 
 * Probably due to medling with data.
 */
class BadDataSentException extends \Exception {
	
}

class ShiftPatternFilter_ArrayNotSet_Exception extends \Exception {
	
}

class CalendarIterator_InvalidIterationMode_Exception extends \Exception {
	
}

class CalendarData_FiltersNotSet_Exception extends \Exception {
	
}

class CalendarData_CalendarPeriodNotSet_Exception extends \Exception {
	
}

class CalendarData_InvalidSpawnMode_Exception extends \Exception {
	
}

class CalendarDataInterpreter_InvalidCalendarDataProcessorType_Exception extends \LogicException {
	
}

class CalendarProcessorException extends \LogicException {
	
}

class ShiftPatternDateException extends \LogicException {
	
}
