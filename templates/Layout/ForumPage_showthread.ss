<div class="typography">
	<div class="breadcrumbs">
		<span><a href="$Parent.Link">$Parent.Title</a> &raquo; <a href="$Link">$Title</a> &raquo; $Thread.Title </span>
	</div>
	
	<h2>$Thread.Title</h2>
	
	<% include PostsTable %>
	
	<% if Thread.CanPost %>
		<p class="replyThread"><a href="{$Link}replythread/$Thread.ID">Reply to this thread</a></p>
	<% else %>
		<p class="cannotReply">Sorry, you cannot reply to this thread.</p>
	<% end_if %>
</div>