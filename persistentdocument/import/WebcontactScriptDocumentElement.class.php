<?php
/**
 * contactcard_WebcontactScriptDocumentElement
 * @package modules.contactcard.persistentdocument.import
 */
class contactcard_WebcontactScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return contactcard_persistentdocument_webcontact
	 */
	protected function initPersistentDocument()
	{
		return contactcard_WebcontactService::getInstance()->getNewDocumentInstance();
	}
}