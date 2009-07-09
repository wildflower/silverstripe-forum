<?php

/**
 * CreatePostForm is a {@link Form} class containing
 * fields, submit handler actions and validation
 * for creating an instance of {@link Post}.
 *
 * @uses Post->formFields() for the form fields
 * @uses Post->requiredFields() for required fields
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class CreatePostForm extends Form {
	
	/**
	 * Create a new instance of CreatePostForm.
	 * 
	 * @param Controller $controller Controller for this form
	 * @param string $name Name of this form
	 * @param int $threadID The ID of the Thread this post goes into
	 * @param int $memberID The ID of the Member posting this
	 */
	function __construct($controller, $name, $threadID, $memberID) {
		$SNG_post = singleton('Post');
		$thread = DataObject::get_by_id('Thread', $threadID);
		
		$fields = $SNG_post->formFields($thread);
		
		// These hidden fields determine the context (author and thread) so
		// the appropriate saving can be done. {@see CreatePostForm->doSubmit()}
		$fields->push(new HiddenField('MemberID', '', $memberID));
		$fields->push(new HiddenField('ThreadID', '', $threadID));
		
		$actions = new FieldSet(
			new FormAction('doSubmit', 'Submit Reply')
		);
		
		$validator = $SNG_post->requiredFields();
		
		parent::__construct($controller, $name, $fields, $actions, $validator);
	}
	
	/**
	 * Submit action handler for CreatePostForm.
	 * 
	 * This method is responsible for editing an
	 * existing instance of {@link Post} using
	 * the submitted form request data.
	 *
	 * @param array $data Request data from form
	 * @param Form $form The Form object for this action
	 */
	function doSubmit($data, $form) {
		$validationFailed = false;
		$threadID = (int) $data['ThreadID'];
		$authorID = (int) $data['MemberID'];

		// Check to ensure the form was submitted with the correct controller
		if(!$threadID) {
			$form->sessionMessage(
				_t(
					'CreatePostForm.PROBLEMPOSTING',
					'There was a problem posting. A valid thread could not be found.'
				),
				'bad'
			);
			$validationFailed = true;
		}
		
		// Check to ensure the member who submitted is the logged in user
		if($authorID != Member::currentUserID()) {
			$form->sessionMessage(
				_t(
					'CreatePostForm.MUSTLOGIN',
					'Please make sure you are logged in before posting.'
				),
				'bad'
			);
			$validationFailed = true;
		}
		
		// If any of the above checks failed, redirect the user back
		if($validationFailed) {
			Director::redirectBack();
			return false;
		}
		
		// Create a new Post, saving the form data and writing it to the DB
		$post = new Post();
		$form->saveInto($post);
		$post->write();

		// Redirect the user back to the thread from whence they came from
		Director::redirect($this->controller->Link() . 'showthread/' . $threadID);
	}
	
}

?>