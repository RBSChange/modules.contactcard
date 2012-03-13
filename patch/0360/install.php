<?php
/**
 * contactcard_patch_0360
 * @package modules.contactcard
 */
class contactcard_patch_0360 extends patch_BasePatch
{
//  by default, isCodePatch() returns false.
//  decomment the following if your patch modify code instead of the database structure or content.
    /**
     * Returns true if the patch modify code that is versionned.
     * If your patch modify code that is versionned AND database structure or content,
     * you must split it into two different patches.
     * @return Boolean true if the patch modify code that is versionned.
     */
//	public function isCodePatch()
//	{
//		return true;
//	}
 
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