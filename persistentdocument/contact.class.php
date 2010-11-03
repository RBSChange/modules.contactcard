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
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
		$nodeAttributes['label'] = $this->getTreeNodeLabel();
		$nodeAttributes[f_tree_parser_AttributesBuilder::BLOCK_ATTRIBUTE] = 'modules_' . $moduleName . '_detail';
		if ($treeType == tree_parser_TreeParser::TYPE_MULTI_LIST)
		{
			try
			{
				$ws = website_WebsiteModuleService::getInstance();
				$page = $ws->getDocumentByContextualTag('contextual_website_website_modules_contactcard_page-contact', $ws->getCurrentWebsite());
				if ($page->isPublicated())
				{
					$contactFormLink = sprintf('<a href="%s" class="link">%s</a>', LinkHelper::getDocumentUrl($page, $this->getLang(), array('formParam[receiverIds]' => $this->getId())), f_Locale::translate('&modules.contactcard.frontoffice.Contactnamed;', array("name" => $nodeAttributes['label'])));
					$nodeAttributes[f_tree_parser_AttributesBuilder::HTMLLINK_ATTRIBUTE] = $contactFormLink;
				}
			}
			catch (Exception $e)
			{
				Framework::exception($e);
			}
		}
		else if ($treeType == 'wlist')
		{
			$picture = $this->getPhoto();
			if ($picture !== null)
			{
				$nodeAttributes['thumbnailsrc'] = MediaHelper::getPublicFormatedUrl($picture, "modules.uixul.backoffice/thumbnaillistitem");
			}
		}
	}
	
	public function hasAddressInformations()
	{
		return f_util_StringUtils::isNotEmpty($this->getAddress1()) || f_util_StringUtils::isNotEmpty($this->getAddress2()) ||  f_util_StringUtils::isNotEmpty($this->getZipcode()) || f_util_StringUtils::isNotEmpty($this->getCity()) || f_util_StringUtils::isNotEmpty($this->getCountry());
	}

}