<?php

// Add the ForumRole decorator to Member
DataObject::add_extension('Member', 'ForumRole');

// Set up URL routes for forum
Director::addRules(50, array(
	'forums/profile/$Action/$ID' => 'ForumProfileController'
));

?>