<% if LastPost %>
	<a href="$LastPost.Link">$LastPost.Title</a> by
	<a href="forums/profile/show/$LastPost.Member.ID">
		<% if LastPost.Member.Username %>
			$LastPost.Member.Username
		<% else %>
			$LastPost.Member.FirstName
		<% end_if %>
	</a> on $LastPost.Created.Nice
<% else %>
	<span class="nopost">-</span>
<% end_if %>