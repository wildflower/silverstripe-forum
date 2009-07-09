<?php

/**
 * ThreadForm is a Form class used for adding or
 * editing an instance of {@link Thread}, which
 * makes up the discussions on a {@link ForumPage}.
 * 
 * In order for this class to operate correctly,
 * a valid {@link Member} must be logged in. It is
 * assumed that the controller of this form is of a
 * {@link ForumPage} type, or similar, in order to
 * post this {@link Thread} to that particular forum.
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class ThreadForm extends Form {
	
	/**
	 * Create a new instance of ThreadForm.
	 * 
	 * @param Controller $controller Controller for this form
	 * @param string $name Name of this form
	 * @param int $memberID The ID of the Member creating this thread
	 */
	function __construct($controller, $name, $memberID) {
		$SNG_post = singleton('Post');
		$fields = $SNG_post->formFields();
		
		// Current member is moderator, and can set additional thread settings
		if($controller->CurrentMemberIsModerator()) {
			$fields->push(new CheckboxField('IsSticky', 'Mark this thread as sticky'));
			$fields->push(new CheckboxField('CanPost', 'Users can post to this thread', 1));
		} else {
			// Current member is NOT a moderator, set the default settings (e.g. CanPost = 1)
			$fields->push(new HiddenField('CanPost', '', 1));
		}
		
		// These hidden fields determine the context (author and forum) so
		// the appropriate saving can be done. {@see ThreadForm->doSubmit()}		
		$fields->push(new HiddenField('MemberID', '', $memberID));
		$fields->push(new HiddenField('ForumID', '', $controller->ID));
		
		$actions = new FieldSet(
			new FormAction('doSubmit', 'Submit New Thread')
		);
		
		$validator = $SNG_post->requiredFields();
		
		parent::__construct($controller, $name, $fields, $actions, $validator);
	}
	
	/**
	 * Submit action handler for ThreadForm.
	 * 
	 * This method is responsible for editing an
	 * existing instance of {@link Thread} using
	 * the submitted form request data.
	 *
	 * @param array $data Request data from form
	 * @param Form $form The Form object for this action
	 */
	function doSubmit($data, $form) {
		$validationFailed = false;
		$forumID = (int) $data['ForumID'];
		$authorID = (int) $data['MemberID'];

		// Check to ensure the form was submitted with the correct controller
		if($forumID != $this->controller->ID) {
			$form->sessionMessage(
				_t(
					'ThreadForm.PROBLEMPOSTING',
					'There was a problem posting. A valid forum could not be found.'
				),
				'bad'
			);
			$validationFailed = true;
		}

		// Check to ensure the member who submitted is the logged in user
		if($authorID != Member::currentUserID()) {
			$form->sessionMessage(
				_t(
					'ThreadForm.MUSTLOGIN',
					'Please make sure you are logged in before posting.'
				),
				'required'
			);
			$validationFailed = true;
		}
		
		// If any of the above checks failed, redirect the user back
		if($validationFailed) {
			Director::redirectBack();
			return false;
		}
		
		// Create a new Thread, saving the form data and writing it to the DB
		$thread = new Thread();
		$form->saveInto($thread);
		$thread->write();
		
		// Create a new Post, saving the form data and writing it to the DB
		// We also have an ID for the Thread that we just wrote to the DB,
		// so we can connect the related objects together.
		$post = new Post();
		$form->saveInto($post);
		$post->ThreadID = $thread->ID;
		$post->write();

		// Redirect the user back to the ForumPage from whence they came from
		Director::redirect($this->controller->Link());
	}
	
}

?>