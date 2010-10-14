<?php
class contactcard_ListFunctionsService extends BaseService implements list_ListItemsService
{
	/**
	 * @var contactcard_ListFunctionsService
	 */
	private static $instance;


	/**
	 * @return contactcard_ListFunctionsService
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
	 * @return array<list_Item>
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