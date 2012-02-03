<?php
/**
 * @package modules.contactcard.lib.services
 */
class contactcard_ModuleService extends ModuleBaseService
{
	/**
	 * Singleton
	 * @var contactcard_ModuleService
	 */
	private static $instance = null;

	/**
	 * @return contactcard_ModuleService
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	
	/**
	 * @deprecated (will be removed in 4.0) use $notification->registerCallback() and $notification->sendToContact()
	 */
	public function sendNotificationToContactCallback($notification, $contact, $callback = null, $callbackParameter = null)
	{
	
		if ($notification === null)
		{
			if (Framework::isInfoEnabled()) { Framework::info(__METHOD__ . ' No notification to send.'); }
			return false;
		}
		else if ($contact === null)
		{
			if (Framework::isInfoEnabled()) { Framework::info(__METHOD__ . ' No contact to send notification.'); }
			return false;
		}
		else if (count($contact->getEmailAddresses()) == 0)
		{	
			if (Framework::isInfoEnabled()) {
				Framework::info(__METHOD__ . ' No email on contact (' . $contact->__toString() . ') to send notification.');
			}
			return false;
		}
		
		if (is_array($callback))
		{
			$notification->registerCallback($callback[0], $callback[1], $callbackParameter);
		}

		return $notification->sendToContact($contact);
	}
	
	/**
	 * @deprecated (will be removed in 4.0) with no replacement
	 */
	public function getNotificationParametersCallback($params)
	{

	}
	
	/**
	 * @deprecated (will be removed in 4.0) with no replacement
	 */
	public function getNotificationParameters($contact)
	{
		return $contact->getDocumentService()->getNotificationParameters($contact);
	}
}