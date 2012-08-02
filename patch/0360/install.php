<?php
/**
 * contactcard_patch_0360
 * @package modules.contactcard
 */
class contactcard_patch_0360 extends patch_BasePatch
{
 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$newPath = f_util_FileUtils::buildWebeditPath('modules/contactcard/persistentdocument/contact.xml');
		$newModel = generator_PersistentModel::loadModelFromString(f_util_FileUtils::read($newPath), 'contactcard', 'contact');
		$newProp = $newModel->getPropertyByName('websiteurl');
		f_persistentdocument_PersistentProvider::getInstance()->addProperty('contactcard', 'contact', $newProp);
		
		$newPath = f_util_FileUtils::buildWebeditPath('modules/contactcard/persistentdocument/contact.xml');
		$newModel = generator_PersistentModel::loadModelFromString(f_util_FileUtils::read($newPath), 'contactcard', 'contact');
		$newProp = $newModel->getPropertyByName('allowcontact');
		f_persistentdocument_PersistentProvider::getInstance()->addProperty('contactcard', 'contact', $newProp);
		
		$newPath = f_util_FileUtils::buildWebeditPath('modules/contactcard/persistentdocument/contact.xml');
		$newModel = generator_PersistentModel::loadModelFromString(f_util_FileUtils::read($newPath), 'contactcard', 'contact');
		$newProp = $newModel->getPropertyByName('contactpage');
		f_persistentdocument_PersistentProvider::getInstance()->addProperty('contactcard', 'contact', $newProp);
		$this->execChangeCommand('compile-db-schema');
	}
}