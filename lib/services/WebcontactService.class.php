<?php
class contactcard_WebcontactService extends f_persistentdocument_DocumentService
{
	/**
	 * @var contactcard_WebcontactService
	 */
	private static $instance;

	/**
	 * @return contactcard_WebcontactService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

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
		return $this->pp->createQuery('modules_contactcard/webcontact');
	}
}