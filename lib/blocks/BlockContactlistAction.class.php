<?php
/**
 * contactcard_BlockContactlistAction
 * @package modules.contactcard.lib.blocks
 */
class contactcard_BlockContactlistAction extends website_BlockAction
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
		$restrictions = array();
		$orders = array();
				
		if ($request->hasNonEmptyParameter('companyname'))
		{
			$restrictions[] = Restrictions::eq('name', $request->getParameter('companyname'));
		}
		if ($request->hasNonEmptyParameter('functions'))
		{
			$restrictions[] = Restrictions::eq('function', $request->getParameter('functions'));
		}
		
		$orderProperty = $this->getParameterOrSetDefault($request, 'orderBy', 'label');
		$orderDirection = $this->getParameterOrSetDefault($request, 'orderByDirection', 'asc');	
		$orders[] = Order::byString($orderProperty, $orderDirection);
		$contacts = contactcard_ContactService::getInstance()->getPublishedFiltered($restrictions, $orders);
		$function = $request->getParameter('functions');
		$companyname = $request->getParameter('companyname');
		$this->preparePaginator($request, $contacts);
		return website_BlockView::SUCCESS;
	}
	
	/**
	 * @param request
	 * @param contacts
	 */
	private function preparePaginator($request, $contacts) 
	{
		$pageIndex = $request->getParameter("page", 1);
		$itemPerPage = $this->getConfigurationParameter("itemPerPage", 10);
		$paginator = new paginator_Paginator("contactcard", $pageIndex, $contacts, $itemPerPage);
		$request->setAttribute("contacts", $paginator);
	}
	
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param f_mvc_Request $request
	 * @param f_mvc_Response $response
	 * @return String
	 */
	function executeFilter($request, $response)
	{
		return $this->execute($request, $response);
	}
	
	/**
	 * @param f_mvc_Request $request
	 * @param string $paramName
	 * @param string $defaultValue
	 * @return string
	 */
	private function getParameterOrSetDefault($request, $paramName, $defaultValue)
	{
		if ($request->hasParameter($paramName))
		{
			return $request->getParameter($paramName);
		}		
		$request->setAttribute($paramName, $defaultValue);
		return $defaultValue;
	}
}