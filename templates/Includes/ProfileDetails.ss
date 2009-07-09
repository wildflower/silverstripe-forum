<table class="profileTable" summary="Forum member profile details">
	<tbody>
		<% control Member %>
		<tr>
			<td colspan="2" align="center">
				<img src="$AvatarURLByWidth(150)">
			</td>
		</tr>
		<% if Description %>
		<tr>
			<td colspan="2">
				$Description.XML
			</td>
		</tr>
		<% end_if %>
		<% if CanViewName %>
		<tr>
			<td class="label">Name</td>
			<td class="value">$FirstName</td>
		</tr>
		<% end_if %>
		<tr>
			<td class="label">Username</td>
			<td class="value">$Username</td>
		</tr>
		<tr>
			<td class="label">Country</td>
			<td class="value">$CountryLookup</td>
		</tr>
		<% end_control %>
	</tbody>
</table>