<?php
/**
 * contactcard_persistentdocument_webcontact
 * @package contactcard
 */
class contactcard_persistentdocument_webcontact extends contactcard_persistentdocument_webcontactbase implements form_FormReceiver 
{
	
	/**
	 * @return array
	 */
	public function getEmailAddresses()
	{
		// Get the emails addresses and explode with ','
		return explode(',', $this->getMails());
	}
	
}