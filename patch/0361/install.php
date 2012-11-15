<?php
/**
 * contactcard_patch_0361
 * @package modules.contactcard
 */
class contactcard_patch_0361 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		// Fix f_document: 
		foreach (RequestContext::getInstance()->getSupportedLanguages() as $lang)
		{
			$sql = "UPDATE `f_document` SET `label_".$lang."` = 'contact-card' WHERE `document_model` = 'modules_contactcard/contact' AND `lang_vo` = '".$lang."' AND `label_".$lang."` IS NULL";
			$this->executeSQLQuery($sql);
		}
		
		// Fix m_contactcard_doc_contact:
		$sql = "UPDATE `m_contactcard_doc_contact` SET `document_label` = 'contact-card' WHERE `document_label` IS NULL";
		$this->executeSQLQuery($sql);
		
		// Fix m_contactcard_doc_contact_i18n:
		$sql = "SELECT d.document_id AS 'id', d.document_lang AS 'lang', d.document_publicationstatus AS 'status', name, function, phone, post, fax FROM `m_contactcard_doc_contact` AS d LEFT OUTER JOIN `m_contactcard_doc_contact_i18n` AS i ON d.document_id = i.document_id AND d.document_lang = i.lang_i18n WHERE i.lang_i18n IS NULL";
		$stmt = $this->executeSQLSelect($sql);
		foreach ($stmt->fetchAll() as $row)
		{
			$name = $row['name'] ? "'".$row['name']."'" : 'NULL';
			$function = $row['function'] ? "'".$row['function']."'" : 'NULL';
			$phone = $row['phone'] ? "'".$row['phone']."'" : 'NULL';
			$post = $row['post'] ? "'".$row['post']."'" : 'NULL';
			$fax = $row['fax'] ? "'".$row['fax']."'" : 'NULL';
			$sql = "INSERT INTO `m_contactcard_doc_contact_i18n` SET `document_id` = ".$row['id'].", `lang_i18n` = '".$row['lang']."', `document_publicationstatus_i18n` = '".$row['status']."', `document_label_i18n` = 'contact-card', `name_i18n` = $name, `function_i18n` = $function, `phone_i18n` = $phone, `post_i18n` = $post, `fax_i18n` = $fax";
			$this->executeSQLQuery($sql);
		}
	}
}