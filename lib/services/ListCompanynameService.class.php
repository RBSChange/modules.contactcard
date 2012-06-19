<?php
/**
 * @method contactcard_ListCompanynameService getInstance()
 */
class contactcard_ListCompanynameService extends change_BaseService implements list_ListItemsService
{
	/**
	 * @return list_Item[]
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