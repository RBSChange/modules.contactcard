<?php
class contactcard_PreferencesService extends f_persistentdocument_DocumentService
{
	/**
	 * @var contactcard_PreferencesService
	 */
	private static $instance;

	/**
	 * @return contactcard_PreferencesService
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
	 * @return contactcard_persistentdocument_preferences
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_contactcard/preferences');
	}

	/**
	 * Create a query based on 'modules_contactcard/preferences' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_contactcard/preferences');
	}
}