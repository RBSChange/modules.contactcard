<?php
class contactcard_ContactScriptDocumentElement extends import_ScriptDocumentElement
{
	/**
	 * @return contactcard_persistentdocument_contact
	 */
	protected function initPersistentDocument()
	{
		return contactcard_ContactService::getInstance()->getNewDocumentInstance();
	}
	
	/**
	 * @return array
	 */
	protected function getDocumentProperties ()
	{
		$properties = parent::getDocumentProperties();		
		if (isset($properties['titlestr']))
		{
			$label = $properties['titlestr'];
			$list = list_ListService::getInstance()->getByListId('modules_contactcard/title');
			foreach ($list->getItemdocumentsArray() as $document)
			{
				if ($document->getLabel() == $label)
				{
					$properties['title'] = $document;
					break;
				}
			}
			unset($properties['titlestr']);
		}
		
		return $properties;
	}
}