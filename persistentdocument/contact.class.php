<?php
/**
 * @package contactcard
 */
class contactcard_persistentdocument_contact extends contactcard_persistentdocument_contactbase implements form_FormReceiver
{
	/**
	 * @return String
	 */
	function getTreeNodeLabel()
	{
		if (is_null($this->getLastname()))
		{
			return $this->getName();
		}
		else
		{
			return $this->getLastname() . ' ' . $this->getFirstname();
		}
	}
	
	/**
	 * @return array
	 */
	public function getEmailAddresses()
	{
		$emailAddressArray = explode(',', $this->getMails());
		foreach ($emailAddressArray as &$emailAddress)
		{
			$emailAddress = trim($emailAddress);
		}
		return $emailAddressArray;
	}
	
	/**
	 * @return boolean
	 */
	public function hasAddressInformations()
	{
		return f_util_StringUtils::isNotEmpty($this->getAddress1()) || f_util_StringUtils::isNotEmpty($this->getAddress2()) ||  f_util_StringUtils::isNotEmpty($this->getZipcode()) || f_util_StringUtils::isNotEmpty($this->getCity()) || f_util_StringUtils::isNotEmpty($this->getCountry());
	}
}