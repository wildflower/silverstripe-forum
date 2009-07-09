<table id="threads" class="threadsTable" summary="A listing of threads for $Title">
	<thead>
		<tr>
			<th>Thread</th>
			<th>Last Post</th>
			<th>Replies</th>
			<th>Views</th>
		</tr>
	</thead>
	<tbody>
	<% if Threads %>
		<% control Threads %>
			<tr class="$EvenOdd<% if FirstLast %> $FirstLast<% end_if %>">
				<td class="threadTitle"><% if IsSticky %>[Sticky] <% end_if %><a href="$Link">$Title</a></td>
				<td class="lastPost"><% include LastPost %></td>
				<td class="replyCount center">$ReplyCount</td>
				<td class="viewCount center">0</td>
			</tr>
		<% end_control %>
	<% else %>
		<tr>
			<td colspan="4">There are no threads.</td>
		</tr>
	<% end_if %>
	</tbody>
</table>