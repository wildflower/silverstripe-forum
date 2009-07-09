<?php

/**
 * A thread is a topic started by a forum member,
 * and contains a many number of {@link Post}
 * instances which make up the thread.
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class Thread extends DataObject {
	
	static $db = array(
		'Title' => 'Varchar(100)',
		'IsSticky' => 'Boolean',
		'CanPost' => 'Boolean'
	);
	
	static $has_one = array(
		'Forum' => 'ForumPage',
		'Member' => 'Member'
	);
	
	static $has_many = array(
		'Posts' => 'Post',
		'Subscriptions' => 'ThreadSubscription'
	);
	
	/**
	 * Overload the Posts relationship accessor
	 * so we can return a set of {@link Post} instances,
	 * sorted by the "Created" date in the database.
	 *
	 * @todo Defining getPosts() on this class doesn't
	 * appear to overload the default relationship accessor
	 * for the "Posts" property, at least not when looping over
	 * "Posts" in the templates. Is this the correct behaviour,
	 * or a bug?
	 *
	 * @return ComponentSet
	 */
	function Posts() {
		return $this->getComponents('Posts', '', 'Created DESC');
	}
	
	/**
	 * Return a list of fields, suitable for showing
	 * on the overview of a ComplexTableField or
	 * similar.
	 * 
	 * @return array
	 */
	function fieldList() {
		return array(
			'ID' => 'ID',
			'IsSticky' => 'Sticky?',
			'CanPost' => 'Can post?',
			'Title' => 'Title',
			'Member.FirstName' => 'Author'
		);
	}	
	
	/**
	 * Return a URL which points to
	 * this thread in order to
	 * display it.
	 * 
	 * @return string
	 */
	function Link() {
		$forumLink = $this->Forum()->Link();
		
		return $forumLink . "showthread/{$this->ID}";
	}
	
	/**
	 * Before deleting this thread, delete all the
	 * posts linking to it through the has many
	 * relation.
	 */
	function onBeforeDelete() {
		parent::onBeforeDelete();
		
		$posts = $this->Posts();
		if($posts) {
			foreach($posts as $post) {
				$post->delete();
			}
		}
	}
	
	/**
	 * Determines if the current user logged in is
	 * a moderator of the forum, by checking if the
	 * user is a moderator to the {@link ForumPage}
	 * parent to this Thread instance.
	 * 
	 * For example, this directly determines if the
	 * user can create a thread as a "sticky", in that
	 * it appears first in thread listing, and is always
	 * at the top. If they do not have these rights, as
	 * outlined in this method, then they cannot create
	 * a sticky.
	 * 
	 * @return boolean
	 */
	function CurrentMemberIsModerator() {
		return $this->Forum()->CurrentMemberIsModerator();
	}
	
	/**
	 * Return the number of posts which
	 * are replies to this thread.
	 * 
	 * Since the first post to a thread is
	 * from the author of it, 1 is deducted
	 * from the total post count.
	 * 
	 * @return int
	 */
	function ReplyCount() {
		return $this->Posts()->Count() - 1;
	}
	
	/**
	 * Return the latest {@link Post} for this
	 * thread, which is the first record in the
	 * Posts() {@link ComponentSet} sorted by
	 * the created date in the database.
	 *
	 * @return Post
	 */
	function LastPost() {
		$posts = $this->getComponents('Posts', '', 'Created DESC');
		
		return ($posts) ? $posts->First() : false;
	}
	
}

?>