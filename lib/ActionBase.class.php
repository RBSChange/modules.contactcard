<?php
class contactcard_ActionBase extends f_action_BaseAction
{
		
	/**
	 * Returns the contactcard_ContactService to handle documents of type "modules_contactcard/contact".
	 *
	 * @return contactcard_ContactService
	 */
	public function getContactService()
	{
		return contactcard_ContactService::getInstance();
	}
		
	/**
	 * Returns the contactcard_PreferencesService to handle documents of type "modules_contactcard/preferences".
	 *
	 * @return contactcard_PreferencesService
	 */
	public function getPreferencesService()
	{
		return contactcard_PreferencesService::getInstance();
	}
		
	/**
	 * Returns the contactcard_WebcontactService to handle documents of type "modules_contactcard/webcontact".
	 *
	 * @return contactcard_WebcontactService
	 */
	public function getWebcontactService()
	{
		return contactcard_WebcontactService::getInstance();
	}
		
}