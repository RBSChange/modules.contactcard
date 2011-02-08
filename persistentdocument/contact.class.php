<?php
/**
 * contactcard_persistentdocument_contact
 * @package contactcard
 */
class contactcard_persistentdocument_contact extends contactcard_persistentdocument_contactbase implements indexer_IndexableDocument, form_FormReceiver
{
	/**
	 * @see f_persistentdocument_PersistentDocumentImpl::getTreeNodeLabel()
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
	 * Get the indexable document
	 *
	 * @return indexer_IndexedDocument
	 */
	public function getIndexedDocument()
	{
		if ($this->getIndexingstatus() == false)
		{
			return null;
		}
		
		$indexedDoc = new indexer_IndexedDocument();
		$indexedDoc->setId($this->getId());
		$indexedDoc->setDocumentModel('modules_contactcard/contact');
		$indexedDoc->setLabel($this->getLabel());
		$indexedDoc->setLang(RequestContext::getInstance()->getLang());
		$indexedDoc->setText($this->getAddress1() . " " . $this->getAddress2() . " " . $this->getZipcode() . " " . $this->getCity() . " " . $this->getCountry() . " " . $this->getLastname() . " " . $this->getFirstname() . " " . $this->getFunction() . " " . $this->getPhone() . " " . $this->getPost() . " " . $this->getFax());
		return $indexedDoc;
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