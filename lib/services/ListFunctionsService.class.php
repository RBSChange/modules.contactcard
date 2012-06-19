<?php
/**
 * @method contactcard_ListFunctionsService getInstance()
 */
class contactcard_ListFunctionsService extends change_BaseService implements list_ListItemsService
{
	/**
	 * @return list_Item[]
	 */
	public function getItems()
	{
		$contactcardInstance = contactcard_ContactService::getInstance();
		$functionsAvailable = $contactcardInstance->getAllFunctions();
		

		$items = array();
		foreach ($functionsAvailable as $function)
		{
			$items[] = new list_Item($function,$function);
		}
		return $items;
	}
	
}