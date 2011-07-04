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
		$contact = $this->getDocumentParameter(K::COMPONENT_ID_ACCESSOR, "contactcard_persistentdocument_contact");
		if ($contact === null || !$contact->isPublished())
		{
			return website_BlockView::NONE;
		}
		$request->setAttribute('contact', $contact);
		return website_BlockView::SUCCESS;
	}
}