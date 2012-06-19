<?php
/**
 * @method contactcard_ListLanguagesService getInstance()
 */
class contactcard_ListLanguagesService extends change_BaseService implements list_ListItemsService
{
	/**
	 * @return list_Item[]
	 */
	public function getItems()
	{
		$items = array();
		$ls = LocaleService::getInstance();
		$languages = RequestContext::getInstance()->getUISupportedLanguages();

		foreach ($languages as $language)
		{
			$items[] = new list_Item($ls->trans('m.uixul.bo.languages.' . strtolower($language), array('ucf')),	$language);
		}

		return $items;
	}

}