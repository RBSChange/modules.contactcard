<?php
/**
 * contactcard_BlockContactAction
 * @package modules.contactcard.lib.blocks
 */
class contactcard_BlockContactAction extends website_BlockAction
{
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function execute($request, $response)
	{
		if ($this->isInBackoffice())
		{
			return website_BlockView::NONE;
		}

		$contact = $this->getDocumentParameter(K::COMPONENT_ID_ACCESSOR, "contactcard_persistentdocument_contact");
		if ($contact === null)
		{
			return website_BlockView::NONE;
		}

		$request->setAttribute('contact', $contact);
		return website_BlockView::SUCCESS;
	}
}