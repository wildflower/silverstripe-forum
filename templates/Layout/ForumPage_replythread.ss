<div class="typography">
	<div class="breadcrumbs">
		<span><a href="$Parent.Link">$Parent.Title</a> &raquo; <a href="$Link">$Title</a> &raquo; <a href="{$Link}showthread/$Thread.ID">$Thread.Title</a> &raquo; Reply</span>
	</div>
	
	<h2>Reply to $Thread.Title</h2>
	
	<% if Thread.CanPost %>
		$CreatePostForm
	<% else %>
		<p class="cannotReply">Sorry, you cannot reply to this thread.</p>
	<% end_if %>
	
	<% include PostsTable %>
</div>