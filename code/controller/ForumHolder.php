<?php

/**
 * ForumHolder is a page that contains many
 * {@link ForumPage} pages as children in
 * the CMS.
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class ForumHolder extends Page {
	
	static $db = array(
		'ShowAsCategories' => 'Boolean'
	);
	
	static $default_child = 'ForumPage';
	
	/**
	 * Set up the fields that will show in the CMS
	 * for this page type.
	 * 
	 * @return FieldSet
	 */
	function getCMSFields() {
		$fields = parent::getCMSFields();
		
		$fields->addFieldsToTab(
			'Root.Content.Categories',
			array(
				new CheckboxField('ShowAsCategories', 'Show forums as categorised?'),
				$this->categoryTableField()
			)
		);
		
		$fields->addFieldToTab(
			'Root.Content.Ranks',
			$this->rankTableField()
		);
		
		return $fields;
	}
	
	/**
	 * Return a ComplexTableField, suitable for editing
	 * {@link ForumCategory} instances in the database.
	 * 
	 * @return ComplexTableField.
	 */
	function categoryTableField() {
		$SNG_category = singleton('ForumCategory');
		
		$field = new ComplexTableField(
			$this,
			'Categories',
			'ForumCategory',
			$SNG_category->fieldList(),
			'getCMSFields'
		);
		
		return $field;
	}
	
	/**
	 * Return a ComplexTableField, suitable for editing
	 * {@link Rank} instances in the database.
	 *
	 * @return ComplexTableField
	 */
	function rankTableField() {
		$SNG_rank = singleton('Rank');
		
		$field = new ComplexTableField(
			$this,
			'Ranks',
			'Rank',
			$SNG_rank->fieldList(),
			'getCMSFields'
		);
		
		return $field;
	}
	
	/**
	 * Return all {@link ForumCategory} records available
	 * from the database.
	 * 
	 * @return DataObjectSet
	 */
	function Categories() {
		return DataObject::get('ForumCategory');
	}
	
	/**
	 * Return all {@link ForumPage} instances that
	 * are children of this holder page.
	 * 
	 * @return DataObjectSet
	 */
	function Forums() {
		return DataObject::get('ForumPage', "ParentID = $this->ID && ShowInMenus = 1");
	}
	
}

class ForumHolder_Controller extends Page_Controller {
	
}

?>