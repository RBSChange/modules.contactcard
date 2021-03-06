<?php
/**
 * @date Thu, 14 Jun 2007 15:53:06 +0200
 * @author intessit
 */
class contactcard_ContactService extends f_persistentdocument_DocumentService
{
	/**
	 * @var contactcard_ContactService
	 */
	private static $instance;

	/**
	 * @return contactcard_ContactService
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
	 * @param contactcard_persistentdocument_contact $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal).
	 */
	protected function preSave($document, $parentNodeId)
	{
		// Warning: label property won't contain relevant data, use getTreeNodeLabel (for BO) and getNavigationLabel (for FO) instead.
		if (!$document->getLabel())
		{
			$document->setLabel('contact-card');
		}
	}
	
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
		return $this->pp->createQuery('modules_contactcard/contact');
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
				$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
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
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */
	public function addTreeAttributes($document, $moduleName, $treeType, &$nodeAttributes)
	{
		$nodeAttributes['label'] = $document->getTreeNodeLabel();
		if ($treeType == 'wmultilist')
		{
			$content = f_Locale::translate('&modules.contactcard.frontoffice.Contactnamed;', array("name" => $nodeAttributes['label']));
			$nodeAttributes['htmllink'] = '<a class="link" href="#" rel="cmpref:' . $document->getId() . '" lang="' . $document->getLang() . '">' . htmlspecialchars($content, ENT_NOQUOTES, 'UTF-8') . '</a>';
		}
		else if ($treeType == 'wlist')
		{
			$picture = $document->getPhoto();
			if ($picture !== null)
			{
				$nodeAttributes['thumbnailsrc'] = MediaHelper::getPublicFormatedUrl($picture, "modules.uixul.backoffice/thumbnaillistitem");
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
			$ws = website_WebsiteModuleService::getInstance();
			$page = $ws->getDocumentByContextualTag('contextual_website_website_modules_contactcard_page-contact', $ws->getCurrentWebsite());
			if ($page->isPublished())
			{
				$contactFormLink = LinkHelper::getUrl($page, $lang, array('formParam[receiverIds]' => $document->getId()));
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
	protected function constructLabel($document)
	{
		$label = '';
		if ($document->getFirstname() !== null)
		{
			$label .= $document->getFirstname() . ' ';
		}
		if ($document->getLastname() !== null)
		{
			$label .= $document->getLastName() . ' ';
		}
		if ($document->getName() !== null)
		{
			if (!f_util_StringUtils::isEmpty($label))
			{
				$label .= '- ';
			}
			$label .= $document->getName();
		}
		if (f_util_StringUtils::isEmpty($label))
		{
			$label = LocaleService::getInstance()->transFO('m.contactcard.bo.general.defaultlabel');
		}
		return $label;
	}
	
	/**
	 * @param contactcard_persistentdocument_contact $document
	 * @return string
	 */
	public function getNavigationLabel($document)
	{
		return $this->constructLabel($document);
	}
	
	/**
	 * @param contactcard_persistentdocument_contact $document
	 * @return string
	 */
	public function getTreeNodeLabel($document)
	{
		return $this->constructLabel($document);
	}
	
	// Deprecated.

	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function sendNotification($contacts, $notification, $replacements)
	{
		foreach ($contacts as $contact)
		{
			// Get emails addresses of contact
			$emails = $contact->getEmailAddresses();

			// Get the communication language of contact
			$lang = $contact->getCommunicationlanguage();

			// Begin i18n work
			RequestContext::getInstance()->beginI18nWork($lang);

			// Send the notification
			$notificationService = notification_NotificationService::getInstance();
			$notificationService->sendMail($notification, $emails, $replacements);

			// Close the i18n work
			RequestContext::getInstance()->endI18nWork();
		}
	}
}