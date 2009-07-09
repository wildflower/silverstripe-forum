<table id="forums-$ID" class="forumTable" summary="Show a list of forums">
	<thead>
		<tr>
			<th>Name</th>
			<th>Last Post</th>
			<th>Threads</th>
			<th>Posts</th>
		</tr>
	</thead>
	<tbody>
		<% control Forums %>
		<tr class="$EvenOdd<% if FirstLast %> $FirstLast<% end_if %>">
			<td class="forumName"><a href="$Link">$Title</a></td>
			<td class="lastPost"><% include LastPost %></td>
			<td class="threadCount center">$Threads.Count</td>
			<td class="postCount center">$TotalPosts</td>
		</tr>
		<% end_control %>
	</tbody>
</table>