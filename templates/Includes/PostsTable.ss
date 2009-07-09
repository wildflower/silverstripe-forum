<table id="thread-{$Thread.ID}" class="postsTable" summary="Showing posts for thread '{$Thread.Title}'">
	<% if Thread.Posts %>
	<tbody>
		<% control Thread.Posts %>
		<tr>
			<td class="author">
				<% if Member.Avatar %>
					<img src="$Member.AvatarURLByWidth(80)">
				<% else %>
					<span>No avatar</span>
				<% end_if %>
				
				<a href="forums/profile/show/{$Member.ID}">				
					<% if Member.Username %>
						$Member.Username
					<% else %>
						<% if Member.CanViewName %>$Member.FirstName<% else %>Anonymous<% end_if %>
					<% end_if %>
				</a>
				
				<% if Member.IsRankingEnabled %>
					<% if Member.Rank %>
						$Member.Rank.Name
					<% end_if %>
				<% end_if %>
			</td>
			<td class="content">
				$Message.XML
				
				<ul class="postOptions">
					<% if canEdit %><li><a href="{$Top.Link}editpost/{$ID}">Edit this post</a></li><% end_if %>
					<% if canDelete %><li><a href="{$Top.Link}deletepost/{$ID}">Delete this post</a></li><% end_if %>
				</ul>
				
				<% if HasBeenEdited %>
					<p>Last edited at: $LastEdited.Nice</p>
				<% end_if %>
			</td>
		</tr>
		<% end_control %>
	</tbody>
	<% end_if %>
</table>