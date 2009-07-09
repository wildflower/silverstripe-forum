<?php

/**
 * A forum can be grouped into a category, and
 * as such a category has many {@link ForumPage}
 * instances associated with it.
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class ForumCategory extends DataObject {
	
	static $db = array(
		'Name' => 'Varchar(100)'
	);
	
	static $has_many = array(
		'Forums' => 'ForumPage'
	);
	
	/**
	 * Return a list of fields, suitable for showing
	 * on the overview of a ComplexTableField or similar.
	 * 
	 * @return array
	 */
	function fieldList() {
		return array(
			'Name' => 'Name'
		);
	}
	
}

?>