<?php

class ThreadSubscription extends DataObject {
	
	static $db = array(
		'LastSent' => 'SSDatetime'
	);
	
	static $has_one = array(
		'Thread' => 'Thread',
		'Member' => 'Member'
	);
	
}

?>