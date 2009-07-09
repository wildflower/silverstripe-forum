<?php

/**
 * Rank is a type of "grade" that a forum user can achieve
 * after a set amount of posts.
 *
 * For example, you want a user to classified as a "Veteran"
 * after they've posted 500 times, this is a way of doing
 * just that. Their status would appear on their forum profile,
 * as well as next to any posts that they have made.
 * 
 * The way it works is that when a member posts, their post count
 * is checked and a determination is made on whether they can
 * upgrade from their current rank. This determination is done by
 * checking the minimum post count required of a rank, against the
 * total posts of the member. If the member does not have a rank,
 * they are given the lowest rank, which is an instance of Rank
 * with the lowest minimum post count.
 *
 * Rank instances are editable in the CMS, using the "Ranks" tab
 * on the ForumHolder page-type.
 *
 * To enable the ranking system, the following code should be added
 * to your project _config.php file, typically found at mysite/_config.php:
 *
 * <code>
 * Rank::$enabled = true;
 * </code>
 *
 * @see Post->onBeforeWrite() for where the rank check is done
 * 
 * @author Sean Harvey <sean at silverstripe dot com>
 * @package forum
 */
class Rank extends DataObject {
	
	static $db = array(
		'Name' => 'Varchar(100)',
		'MinimumPostCount' => 'Int'
	);
	
	/**
	 * Enable the ranking system by setting this variable
	 * to true, Typically done by calling "Rank::$enabled = true"
	 * in your project _config.php file, typically found in the
	 * mysite directory.
	 * 
	 * @var boolean
	 */
	static $enabled = false;
	
	/**
	 * Check the amount of posts that the author ({@link Member})
	 * has made, and see if their current rank can be advanced
	 * to a higher one (a rank with a higher minimum post count).
	 * 
	 * @param Member $author Member to check for rank update
	 * @return mixed Rank instance if updated, boolean false if no update
	 */
	static function update_member($member) {
		$posts = $member->Posts();
		$memberPostCount = $posts ? $posts->Count() : 0;
		$currentRank = $member->Rank();
		$createFirstRank = false;
		
		// Member has a current rank already set
		if($currentRank && $currentRank->ID) {
		
			// Member post count is greater than their current rank's minimum post count
			if($memberPostCount > $currentRank->MinimumPostCount) {
				$higherRank = DataObject::get_one('Rank', "MinimumPostCount >= $memberPostCount", true, 'MinimumPostCount ASC');

				// Higher ranks is available to the member
				if($higherRank) {
					
					// Member has a post count greater, or equal to, the higher rank's minimum post count
					if($memberPostCount >= $higherRank->MinimumPostCount) {
						$member->RankID = $higherRank->ID;
						$member->write();
						
						return $higherRank;
					}
				}
			}
		} else {	// Member does not have a rank yet, give the lowest rank available
			$lowestRank = DataObject::get_one('Rank', '', true, 'MinimumPostCount ASC');
			
			// At least one rank was found, which we can assign to the member
			if($lowestRank) {
				$member->RankID = $lowestRank->ID;
				$member->write();
				
				return $lowestRank;
			}
		}
		
		return false;
	}
	
	/**
	 * Return a list of fields, suitable for showing
	 * on the overview of a ComplexTableField or similar.
	 * 
	 * @return array
	 */
	function fieldList() {
		return array(
			'Name' => 'Name',
			'MinimumPostCount' => 'Minimum posts to achieve rank'
		);
	}
	
}

?>