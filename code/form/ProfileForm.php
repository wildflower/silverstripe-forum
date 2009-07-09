<?php

/**
 * ProfileForm is a {@link Form} class used for
 * editing an instance of {@link Member},
 * specifically for the forum.
 * 
 * ForumProfileController uses this directly for
 * the "edit" template view, so a member can edit
 * their details.
 *
 * The form fields, and required fields are taken from
 * a decorator to Member, which is ForumRole by default.
 * This means that in order to customise the form fields,
 * and required fields for a {@link Member} specifically
 * for the forum, you need to define the functions
 * augmentForumFields() and augmentRequiredForumFields()
 * respectively.
 *
 * @todo Where do you call these augment functions? On a
 * new decorator class? On an existing class? How does the
 * extend() function work in relation to where you can define
 * augmentForumFields() and augmentRequiredForumFields()?
 *
 * @uses ForumRole->forumFields() for the form fields
 * @uses ForumRole->requiredForumFields() for the required fields
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class ProfileForm extends Form {
	
	/**
	 * Create a new instance of ProfileForm.
	 * 
	 * @param Controller $controller Controller for this form
	 * @param string $name Name of this form
	 * @param int $memberID The Member ID record to edit
	 */
	function __construct($controller, $name, $memberID) {
		$member = DataObject::get_by_id('Member', $memberID);
		if(!$member) $member = singleton('Member');
		
		$fields = $member->forumFields();
		
		// Add the member record ID to the form fields as a hidden field
		// so we know which Member record ID to manipulate in the DB.
		$fields->push(new HiddenField('MemberID', '', $memberID));
		
		$actions = new FieldSet(
			new FormAction('doForm', 'Save Details')
		);
		
		$validator = $member->requiredForumFields();
		
		parent::__construct($controller, $name, $fields, $actions, $validator);
		
		if($member) $this->loadDataFrom($member);
	}
	
	/**
	 * Submit action handler for ProfileForm.
	 * 
	 * This method is responsible for editing an
	 * existing instance of {@link Member} using
	 * the submitted form request data.
	 *
	 * @param array $data Request data from form
	 * @param Form $form The Form object for this action
	 */
	function doForm($data, $form) {
		$validationErrors = false;
		$memberID = (int) $data['MemberID'];
		$SQL_email = Convert::raw2sql($data['Email']);
		
		// Check if there was a member ID correctly set in the form
		if(!$memberID) {
			$form->sessionMessage(
				_t(
					'ProfileForm.VALIDMEMBERNOTFOUND',
					'A valid member to edit was not found. Please try again.'
				),
				'bad'
			);
			$validationErrors = true;
		}
				
		// Existing member may have the email the user requested. If so, show an error
		$existingMember = DataObject::get_one('Member', "Email = '$SQL_email'");
		if($existingMember && $existingMember->ID != $memberID) {
			$form->sessionMessage(
				_t(
					'ProfileForm.EMAILALREADYEXISTS',
					'A member with that email address already exists. Please choose another.'
				),
				'bad'
			);
			$validationErrors = true;
		}
		
		// If any of the above checks failed, redirect the user back
		if($validationErrors) {
			Director::redirectBack();
			return false;
		}
		
		// Get the member ID and get the member record from DB
		$member = DataObject::get_by_id('Member', $memberID);
		
		// Save the data into that record and write it
		$form->saveInto($member);
		$member->write();
		
		// Show a message at the top of the form (after the user is redirected back)
		$form->sessionMessage(
			_t(
				'ProfileForm.SUCCESSMESSAGE',
				'Your profile has been successfully updated!'
			),
			'good'
		);
		
		Director::redirectBack();
	}
	
}

?>