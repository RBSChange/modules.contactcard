<?php
/**
 * contactcard_patch_0300
 * @package modules.contactcard
 */
class contactcard_patch_0300 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$this->executeModuleScript('lists.xml', 'contactcard');
	}

	/**
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'contactcard';
	}

	/**
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0300';
	}
}