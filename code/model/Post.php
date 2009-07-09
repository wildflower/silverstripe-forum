<?php

/**
 * Post instances make up a {@link Thread}.
 * 
 * It is an individual reply from a forum
 * member to a thread.
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class Post extends DataObject {

	static $db = array(
		'Title' => 'Varchar(100)',
		'Message' => 'HTMLText'
	);
	
	static $has_one = array(
		'Thread' => 'Thread',
		'Member' => 'Member'
	);
	
	/**
	 * Determines whether the current user logged in is
	 * able to edit this post.
	 * 
	 * @return boolean
	 */
	function canEdit() {
		$currentMemberIsAuthor = (Member::currentUserID() == $this->MemberID) ? true : false;
		$currentMemberIsModerator = $this->Thread()->CurrentMemberIsModerator();
		
		if($currentMemberIsAuthor || $currentMemberIsModerator) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Determines whether the current user logged in is
	 * able to delete this post.
	 * 
	 * @return boolean
	 */
	function canDelete() {
		if($this->Thread()->CurrentMemberIsModerator()) return true;
		else return false;
	}
	
	/**
	 * Determines whether this post has been edited by
	 * checking the LastEdited field to see if it differs
	 * from the Created field.
	 * 
	 * @return boolean
	 */
	function HasBeenEdited() {
		$lastEditedDate = $this->LastEdited;
		$createdDate = $this->Created;
		
		if($lastEditedDate != $createdDate) return true;
		else return false;
	}
	
	/**
	 * Return a URL which points to
	 * this post in order to display it.
	 * 
	 * @return string
	 */
	function Link() {
		$forumLink = $this->Thread()->Forum()->Link();
		
		return $forumLink . "showthread/{$this->ThreadID}";
	}
	
	/**
	 * Return a set of fields used in a form
	 * for adding/editing this post.
	 *
	 * @param Thread $thread Thread instance posting to
	 * @return FieldSet
	 */
	function formFields($thread = null) {
		$fields = new FieldSet(
			new TextField('Title', 'Title', $thread ? 'Re: ' . $thread->Title : ''),
			new TextareaField('Message')
		);
		
		$this->extend('updateFormFields', $fields);
		
		return $fields;
	}
	
	/**
	 * Return a set of fields names that are
	 * required to be filled out. Used in conjunction
	 * with {@link formFields()}.
	 *
	 * @return RequiredFields
	 */
	function requiredFields() {
		$fields = new RequiredFields(
			'Title',
			'Message'
		);
		
		$this->extend('updateRequiredFields', $fields);
		
		return $fields;
	}
	
	/**
	 * After writing an instance of Post, check
	 * the amount of posts that the author ({@link Member})
	 * has created and see if they can advance to a
	 * new forum {@link Rank}.
	 * 
	 * PRECONDITION: An MemberID property has been set
	 * on the Post object before write() is called on it.
	 */
	function onAfterWrite() {
		parent::onAfterWrite();
		
		if(Rank::$enabled) Rank::update_member($this->Member());
	}
	
}

?>