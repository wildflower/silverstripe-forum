<?php

/**
 * ForumPage is a CMS page-type that stores the
 * information about a particular topic of
 * discussion, as such "General Discussion" or
 * "Off topic".
 * 
 * It contains many {@link Thread} instances which
 * are the topics of discussion, posted by logged
 * in members.
 * 
 * @uses CreatePostForm for creating {@link Post} instances
 * @uses EditPostForm for editing {@link Post} instances
 * @uses ThreadForm for creating new {@link Thread} instances
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class ForumPage extends Page {
	
	static $has_one = array(
		'Category' => 'ForumCategory'
	);
	
	static $has_many = array(
		'Threads' => 'Thread'
	);
	
	static $belongs_many_many = array(
		'Moderators' => 'Member'
	);
	
	static $default_child = 'ForumPage';
	
	/**
	 * Set up the fields that will show in the CMS
	 * for this page-type.
	 * 
	 * @return FieldSet
	 */
	function getCMSFields() {
		$fields = parent::getCMSFields();
		
		if($this->categoriesExist()) {
			$fields->addFieldToTab(
				'Root.Content.Main',
				$this->categoryDropdownField(),
				'Content'
			);
		}
		
		$fields->addFieldToTab(
			'Root.Content.Threads',
			$this->threadTableField()
		);
		
		return $fields;
	}
	
	/**
	 * Make a determination if any instances of {@link ForumCategory}
	 * exist in the system (records in the database).
	 * 
	 * @return boolean
	 */
	function categoriesExist() {
		$categories = DataObject::get('ForumCategory');
		return ($categories && $categories->Count() > 0) ? true : false;
	}
	
	/**
	 * Return a DropdownField, suitable for choosing a
	 * {@link ForumCategory} instance from the database.
	 * 
	 * @return DropdownField
	 */
	function categoryDropdownField() {
		$records = DataObject::get('ForumCategory');
		$source = $records ? $records->map('ID', 'Name') : array();
		
		$field = new DropdownField(
			'CategoryID',
			'ForumCategory',
			$source,
			$this->CategoryID,
			null,
			'(Select one)'
		);
		
		return $field;
	}

	/**
	 * Return a ComplexTableField, suitable for editing
	 * {@link Thread} instances in the database.
	 * 
	 * @return ComplexTableField.
	 */
	function threadTableField() {
		$SNG_thread = singleton('Thread');
		
		$field = new ComplexTableField(
			$this,
			'Threads',
			'Thread',
			$SNG_thread->fieldList(),
			'getCMSFields',
			"ForumID = $this->ID"
		);
		
		// This field is created as standalone
		$field->setParentClass(false);
		
		return $field;
	}
	
	/**
	 * Determines whether the user has permission to post
	 * on this forum.
	 * 
	 * @todo use the built-in CMS permission system with
	 * the "Access" tab in the CMS to apply permissions.
	 * In other words, canPost() can be a bit smarter about
	 * when or when not to permit a user to post.
	 * 
	 * @return boolean
	 */
	function canPost() {
		if(Member::currentUserID()) return true;
		else return false;
	}

	/**
	 * Determines whether the user has permission to view
	 * this forum.
	 * 
	 * @todo use the built-in CMS permission system with
	 * the "Access" tab in the CMS to apply permissions.
	 * In other words, canView() can be a bit smarter about
	 * when or when not to permit a user to post.
	 * 
	 * @return boolean
	 */
	function canView() {
		return true;
	}
	
	/**
	 * Determines if the current user logged in is
	 * a moderator to this forum, or has ADMIN
	 * privileges through the {@link Permission}
	 * system in sapphire.
	 * 
	 * @return boolean
	 */
	function CurrentMemberIsModerator() {
		$memberID = Member::currentUserID();
		$moderatorIDs = $this->Moderators()->getIdList();
		$isAdmin = Permission::check('ADMIN');
		
		if(in_array($memberID, $moderatorIDs) || $isAdmin) return true;
		else return false;
	}
	
	/**
	 * Return the total number of {@link Posts}
	 * records in the database for all
	 * {@link Thread} records of this forum.
	 * 
	 * @return int
	 */
	function TotalPosts() {
		$threads = $this->Threads();
		$count = 0;
		
		if($threads) {
			foreach($threads as $thread) {
				$posts = $thread->Posts();
				$count += $posts ? $posts->Count() : 0;
			}
		}
		
		return $count;
	}
	
	/**
	 * Return the latest {@link Post} from the
	 * {@link Thread} records of this forum.
	 *
	 * @return mixed Post|null
	 */
	function LastPost() {
		$threadIDs = implode(',', $this->Threads()->getIDList());
		$posts = false;
		
		if($threadIDs) {
			$posts = DataObject::get(
				'Post',
				"ThreadID IN ($threadIDs)",
				'Created DESC'
			);
		}

		return ($posts) ? $posts->First() : null;
	}
	
	/**
	 * Return all {@link Thread} instances for this forum,
	 * sorted by IsSticky, and then Created, so that sticky
	 * posts appear at the top in the threads set.
	 *
	 * @todo SQL join Post table so we can sort by the last
	 * post on a thread.
	 *
	 * @return ComponentSet
	 */
	function Threads() {
		$threads = $this->getComponents(
			'Threads',
			'',
			'IsSticky DESC, Created DESC'
		);

		return $threads;
	}

}

class ForumPage_Controller extends Page_Controller {

	/**
	 * Handle controller based security for viewing
	 * a forum, this function gets called when someone
	 * views a page with the ForumPage page-type.
	 *
	 * @uses ForumPage->canView() for checking permission
	 */
	function init() {
		parent::init();
		
		if(!$this->canView()) {
			Security::permissionFailure(
				$this,
				_t(
					'ForumPage.VIEWPERMISSION',
					'You do not have permission to view that forum.'
				)
			);
		}
		
		return array();
	}
	
	/**
	 * Set up the template variables for the show
	 * thread view.
	 * 
	 * @param HTTPRequest $request HTTPRequest object generated for this action call
	 * @return array
	 */
	function showthread($request) {
		$output = array();
		$threadID = (int) $request->param('ID');
		
		$output['Thread'] = DataObject::get_by_id('Thread', $threadID);
		
		return $output;
	}

	/**
	 * Set up the template variables for the reply to
	 * thread view. Also check if the user can reply
	 * to the given thread.
	 * 
	 * @todo perform the canPost() for a given thread,
	 * instead of on a per-forum basis. This would imply
	 * that certain threads can be locked down, so only
	 * certain members can view it.
	 * 
	 * @param HTTPRequest $request HTTPRequest object generated for this action call
	 * @return array
	 */
	function replythread($request) {
		$output = array();
		$threadID = (int) $request->param('ID');
		
		if($this->canPost()) {
			$output['Thread'] = DataObject::get_by_id('Thread', $threadID);
		} else {
			Security::permissionFailure(
				$this,
				_t(
					'ForumPage.NOREPLYPERMISSION',
					'You do not have permission to reply to that thread.'
				)
			);
		}
		
		return $output;
	}
	
	/**
	 * Before a user can see the newthread view, check if
	 * they can post to the forum.
	 * 
	 * @param HTTPRequest $request HTTPRequest object generated for this action call
	 * @return array
	 */
	function newthread($request) {
		$output = array();
		
		if(!$this->canPost()) {
			Security::permissionFailure(
				$this,
				_t(
					'ForumPage.NONEWTHREADPERMISSION',
					'You do not have permission to post a new thread.'
				)
			);
		}
		
		return $output;
	}
	
	/**
	 * Edit an instance of {@link Post}.
	 *
	 * In order to do so, the current {@link Member}
	 * logged into the site must be a moderator, or
	 * be the author of the post to edit.
	 *
	 * @return array
	 */
	function editpost($request) {
		$output = array();
		$postID = (int) $request->param('ID');
		$post = DataObject::get_by_id('Post', $postID);

		// Post can't be found, just return false (don't render the template)
		if(!$post) return false;
		
		if($post->canEdit()) {
			$output['Post'] = $post;
			$output['Thread'] = $post->Thread();	// Convenience
		} else {
			Security::permissionFailure(
				$this,
				_t(
					'ForumPage.NOEDITPERMISSION',
					'You do not have permission to edit that post.'
				)
			);
		}
		
		return $output;
	}
	
	/**
	 * Delete an instance of {@link Post}.
	 *
	 * In order to do so, the current {@link Member}
	 * logged into the site must be a moderator
	 * {@link ForumPage->CurrentMemberIsModerator()}
	 *
	 * @return boolean
	 */
	function deletepost($request) {
		$postID = (int) $request->param('ID');
		$post = DataObject::get_by_id('Post', $postID);
		
		// Post can't be found, just return false (don't render the template)
		if(!$post) return false;
		
		if($post->canDelete()) {
			$post->delete();
			$post->destroy();

			Director::redirectBack();
			return true;
		}

		return false;
	}
	
	/**
	 * Create and return a new instance of
	 * {@link ThreadForm}, allowing a {@link Member}
	 * to submit a new {@link Thread} instance for
	 * this forum.
	 * 
	 * @return ThreadForm
	 */
	function ThreadForm() {
		$memberID = (int) Member::currentUserID();
		
		return new ThreadForm($this, 'ThreadForm', $memberID);
	}
	
	/**
	 * Create and return a new instance of
	 * {@link CreatePostForm}, allowing a {@link Member}
	 * to submit a new {@link Post} instance to an
	 * existing {@link Thread} of this forum.
	 * 
	 * @return CreatePostForm
	 */
	function CreatePostForm() {
		$threadID = (int) $this->urlParams['ID'];
		$memberID = (int) Member::currentUserID();
		
		return new CreatePostForm($this, 'CreatePostForm', $threadID, $memberID);
	}
	
	/**
	 * Create and return a new instance of
	 * {@link EditPostForm}, allowing a {@link Member}
	 * to edit an existing {@link Post} instance.
	 * 
	 * @return EditPostForm
	 */
	function EditPostForm() {
		$postID = (int) $this->urlParams['ID'];
		
		return new EditPostForm($this, 'EditPostForm', $postID);
	}
	
}

?>