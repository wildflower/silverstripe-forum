<?php

/**
 * ForumProfileController is a controller class that
 * provides an interface for viewing an editing
 * {@link Member} records on the front end of a
 * website, specific to the forum.
 * 
 * URL routing:
 * 
 * Routing to this controller is done using
 * {@link Director::addRules()} in the forum _config.php
 * file (forums/_config.php). To add a new route, you need
 * to add some code to your project _config.php file 
 * (typically found at mysite/_config.php).
 * 
 * The following code is an example on how to add a route to
 * this controller, so the URL "my-forums/profile" becomes
 * available:
 * 
 * <code>
 * Director::addRules(50, array(
 *    'my-forums/profile/$Action/$ID' => 'ForumProfileController'
 * ));
 * </code>
 * 
 * @uses ProfileForm - an {@link Form} class allowing
 * a user to edit their own profile
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class ForumProfileController extends Page_Controller {
	
	/**
	 * This is the URL segment, used by templates
	 * and the {@link ForumProfileController->Link()}
	 * method for linking to this profile controller.
	 * 
	 * If the URL routes to this controller are changed
	 * from the default, this URLSegment needs to be
	 * changed as well.
	 * 
	 * @var string
	 */
	public $URLSegment = 'forums/profile';
	
	/**
	 * Define the show template view for a {@link Member} record.
	 * 
	 * @param HTTPRequest $request HTTPRequest object generated for this action call
	 * @return array
	 */
	function show($request) {
		$output = array();
		$memberID = (int) $request->param('ID');
		$currentMemberID = Member::currentUserID();
		
		$output['Member'] = DataObject::get_by_id('Member', $memberID);
		$output['IsCurrentMemberProfile'] = ($currentMemberID == $memberID) ? true : false;
		
		return $output;
	}
	
	/**
	 * Define the edit template view for a {@link Member} record.
	 * 
	 * Security is applied here, so that unauthorized requests
	 * to edit a member are denied. In order to edit a given
	 * member you have to be logged in as that member to do it.
	 * 
	 * @param HTTPRequest $request HTTPRequest object generated for this action call
	 * @return array
	 */
	function edit($request) {
		$output = array();
		$memberID = (int) $request->param('ID');
		$currentMemberID = Member::currentUserID();
		
		if($currentMemberID && ($currentMemberID == $memberID)) {
			$output['Member'] = DataObject::get_by_id('Member', $memberID);
		} else {
			Security::permissionFailure(
				$this,
				_t(
					'ForumProfilePage.MUSTBELOGGEDIN',
					'You must be logged in, and have permission to edit that profile.'
				)
			);
		}
		
		return $output;
	}
	
	/**
	 * Create and return a new instance of ProfileForm,
	 * allowing a member to edit their membership details.
	 * 
	 * @return ProfileForm
	 */
	function ProfileForm() {
		$memberID = (int) Director::urlParam('ID');
		
		return new ProfileForm($this, 'ProfileForm', $memberID);
	}
	
}

?>