<?php
/**
 * @method contactcard_ContactService getInstance()
 */
class contactcard_ContactService extends f_persistentdocument_DocumentService
{
	/**
	 * @return contactcard_persistentdocument_contact
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_contactcard/contact');
	}

	/**
	 * Create a query based on 'modules_contactcard/contact' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_contactcard/contact');
	}

	/**
	 * @param contactcard_persistentdocument_contact $document
	 * @param integer $parentNodeId
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{
		$label = "";
		if ($document->getFirstname() !== null)
		{
			$label .= $document->getFirstname()." ";
		}
		if ($document->getLastname() !== null)
		{
			$label .= $document->getLastName()." ";
		}
		if ($document->getName() !== null)
		{
			if (!f_util_StringUtils::isEmpty($label))
			{
				$label .= "- ";
			}
			$label .= $document->getName();
		}
		if (f_util_StringUtils::isEmpty($label))
		{
			$label = f_Locale::translate("&modules.contactcard.bo.general.Defaultlabel;");
		}
		$document->setLabel($label);
	}
	
	/**
	 * @param contactcard_persistentdocument_contact $document
	 * @param string $forModuleName
	 * @param array $allowedSections
	 * @return array
	 */
	public function getResume($document, $forModuleName, $allowedSections = null)
	{
		$data = parent::getResume($document, $forModuleName, $allowedSections);
		$data['properties']['emails'] = implode(", " , $document->getEmailAddresses());
		return $data;
	}
	
	/**
	 * @see f_persistentdocument_DocumentService::getDisplayPage()
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return website_persistentdocument_page
	 */
	public function getDisplayPage($document)
	{
		if ($document->isPublished())
		{
			try 
			{
				$website = website_WebsiteService::getInstance()->getCurrentWebsite();
				return TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_modules_contactcard_contact', $website);
			}
			catch (Exception $e)
			{
				Framework::warn(__METHOD__ . ':' . $e->getMessage());
			}
		}
		return null;
	}
	
	/**
	 * @return string[]
	 */
	public function getAllcompanyNames()
	{	
		$contactcardInstance = contactcard_ContactService::getInstance();
		$query = $contactcardInstance->createQuery();
		$query->add(Restrictions::published())->setProjection(Projections::groupProperty('name'));
		$companynamesAvailable = $query->findColumn('name');
		return $companynamesAvailable;
	}
	
	/**
	 * @return string[] 
	 */
	
	public function getAllFunctions()
	{
		$contactcardInstance = contactcard_ContactService::getInstance();
		$query = $contactcardInstance->createQuery();
		$query->add(Restrictions::published())->setProjection(Projections::groupProperty('function'));
		$functionsAvailable = $query->findColumn('function');
		return $functionsAvailable;
	}
	
	function getPublishedFiltered($restrictions, $orders = array())
	{
		$query = $this->createQuery();
		$query->add(Restrictions::published());
		foreach ($restrictions as $restriction)
		{
			$query->add($restriction);
		}
		foreach ($orders as $order)
		{
			$query->addOrder($order);
		}
		return $query->find();
	}
	
	/**
	 * @param contactcard_persistentdocument_contact $document
	 * @param array<string, string> $attributes
	 * @param integer $mode
	 * @param string $moduleName
	 */
	public function completeBOAttributes($document, &$attributes, $mode, $moduleName)
	{
		$attributes['label'] = $document->getTreeNodeLabel();
		if ($mode & DocumentHelper::MODE_RESOURCE)
		{
			try
			{
				$content = f_Locale::translate('&modules.contactcard.frontoffice.Contactnamed;', array("name" => $attributes['label']));
				$attributes['htmllink'] = '<a class="link" href="#" rel="cmpref:' . $document->getId() . '" lang="' . $document->getLang() . '">' . htmlspecialchars($content, ENT_NOQUOTES, 'UTF-8') . '</a>';
			}
			catch (Exception $e)
			{
				Framework::exception($e);
			}
		}
		if ($mode & DocumentHelper::MODE_CUSTOM)
		{
			$picture = $document->getPhoto();
			if ($picture !== null)
			{
				$attributes['thumbnailsrc'] = MediaHelper::getPublicFormatedUrl($picture, "modules.uixul.backoffice/thumbnaillistitem");
			}
		}
	}
	
	/**
	 * @see f_util_HtmlUtils::renderDocumentLink
	 * @param media_persistentdocument_media $document
	 * @param array $attributes
	 * @param string $content
	 * @param string $lang
	 * @return string
	 */
	public function getXhtmlFragment($document, $attributes, $content, $lang)
	{
		try
		{
			$ws = website_WebsiteService::getInstance();
			$page = TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_modules_contactcard_page-contact', $ws->getCurrentWebsite());
			if ($page->isPublished())
			{
				$contactFormLink = LinkHelper::getDocumentUrl($page, $lang, array('formParam[receiverIds]' => $document->getId()));
				$attributes['href'] = $contactFormLink;
			}
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		return f_util_HtmlUtils::buildLink($attributes, $content);
	}
	
	/**
	 * @param notification_persistentdocument_notification $notification
	 * @param contactcard_persistentdocument_contact $contact
	 */
	public function registerNotificationCallback($notification, $contact)
	{
		if ($notification === null)
		{
			if (Framework::isInfoEnabled())
			{
				Framework::info(__METHOD__ . ' No notification to send.');
			}
			return;
		}
		else if ($contact === null)
		{
			if (Framework::isInfoEnabled())
			{
				Framework::info(__METHOD__ . ' No contact to send notification.');
			}
			return;
		}
		$notification->registerCallback($this, 'getNotificationParameters', $contact);
	}
	
	/**
	 * @param contactcard_persistentdocument_contact $contact
	 * @return array
	 */
	public function getNotificationParameters($contact)
	{
		return array(
			'receiverFirstName' => $contact->getFirstnameAsHtml(),
			'receiverLastName' => $contact->getLastnameAsHtml(),
			'receiverFullName' => $contact->getFirstnameAsHtml() . ' ' . $contact->getLastnameAsHtml(),
			'receiverTitle' => ($contact->getTitle()) ? $contact->getTitle()->getLabelAsHtml() : ''
		);
	}
	
	/**
	 * @param contactcard_persistentdocument_contact $document
	 * @return string
	 */
	public function getTreeNodeLabel($document)
	{
		if ($document->getLastname() === null)
		{
			return $document->getName();
		}
		else
		{
			return $document->getLastname() . ' ' . $document->getFirstname();
		}
	}
	
	/**
	 * @param indexer_IndexedDocument $indexedDocument
	 * @param contactcard_persistentdocument_contact $document
	 * @param indexer_IndexService $indexService
	 */
	protected function updateIndexDocument($indexedDocument, $document, $indexService)
	{
		if (!$document->getIndexingstatus())
		{
			$indexedDocument->foIndexable(false);
		}
	}
}