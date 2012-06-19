<?php
/**
 * @package modules.contactcard
 * @method contactcard_WebcontactService getInstance()
 */
class contactcard_WebcontactService extends f_persistentdocument_DocumentService
{
	/**
	 * @return contactcard_persistentdocument_webcontact
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_contactcard/webcontact');
	}

	/**
	 * Create a query based on 'modules_contactcard/webcontact' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_contactcard/webcontact');
	}
}