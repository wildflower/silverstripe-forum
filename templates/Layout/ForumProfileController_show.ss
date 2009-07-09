<div class="typography">
	<div class="breadcrumbs">
		<span><a href="forums">Forums</a> &raquo; View Profile</span>
	</div>

	<h2><% if Member.Username %>$Member.Username<% else %>$Member.FirstName<% end_if %>'s Profile</h2>

	<% if IsCurrentMemberProfile %>
		<p>Since you are viewing your own profile, you may want to <a href="forums/profile/edit/$Member.ID">edit it</a>.</p>
	<% end_if %>

	<% include ProfileDetails %>	
</div>