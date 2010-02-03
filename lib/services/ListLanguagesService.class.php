<?php
class contactcard_ListLanguagesService extends BaseService implements list_ListItemsService
{
	/**
	 * @var contactcard_ListLanguagesService
	 */
	private static $instance;


	/**
	 * @return contactcard_ListLanguagesService
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
	 * @return array
	 */
	public function getItems()
	{
		$items = array();

	    $languages = RequestContext::getInstance()->getUISupportedLanguages();

	    foreach ($languages as $language)
	    {
	    	$items[] = new list_Item(
                    f_Locale::translateUI('&modules.uixul.bo.languages.' . ucfirst($language) . ';'),
                    $language
                );
	    }

		return $items;
	}

}