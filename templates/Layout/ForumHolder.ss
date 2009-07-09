<div class="typography">
	<h2>$Title</h2>
	
	$Content

	<% if CurrentMember %>
		<p>You are logged in as <b><% if CurrentMember.Username %>$CurrentMember.Username<% else %><% if CurrentMember.CanViewName %>$CurrentMember.FirstName<% else %>Anonymous<% end_if %><% end_if %></b>.</p>
	<% else %>
		<p><a href="Security/login">Log in</a> before posting to the forum.</p>
	<% end_if %>

	<% if ShowAsCategories %>	<!-- Show forums in category view -->
		<% if Categories %>
			<% control Categories %>
				<% if Forums %>
					<h3>$Name</h3>
					<% include ForumsTable %>
				<% end_if %>
			<% end_control %>
		<% end_if %>
	<% else %>	<!-- Normal forum listing view -->
		<% include ForumsTable %>
	<% end_if %>
</div>