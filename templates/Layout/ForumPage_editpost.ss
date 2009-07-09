<div class="typography">
	<div class="breadcrumbs">
		<span><a href="$Parent.Link">$Parent.Title</a> &raquo; <a href="$Link">$Title</a> &raquo; <a href="{$Link}showthread/$Thread.ID">$Thread.Title</a> &raquo; Edit</span>
	</div>
	
	<h2>Edit $Post.Title</h2>
	
	$EditPostForm
	
	<% include PostsTable %>
</div>