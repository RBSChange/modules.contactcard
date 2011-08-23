<?php
class contactcard_ListCompanynameService extends BaseService implements list_ListItemsService
{
	/**
	 * @var contactcard_ListCompanynameService
	 */
	private static $instance;


	/**
	 * @return contactcard_ListCompanynameService
	 */

	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * @return array<list_Item>
	 */
	
	public function getItems()
	{
		$contactcardInstance = contactcard_ContactService::getInstance();
		$companynamesAvailable = $contactcardInstance->getAllcompanyNames();
		

		$items = array();
		foreach ($companynamesAvailable as $companyname)
		{
			$items[] = new list_Item($companyname,$companyname);
		}
		return $items;
	}
	
}