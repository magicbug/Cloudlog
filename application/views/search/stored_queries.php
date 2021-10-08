<?php
$i = 1;
?>
<table class="table-sm table table-bordered table-hover table-striped table-condensed text-center">
	<thead>
		<tr>
			<th>#</th>
			<th>Description</th>
			<th>Query</th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($result as $q) {
			echo '<tr id="query_' . $q->id . '">';
			echo '<td>' . $i++ . '</td>';
			echo '<td contenteditable="false" id="description_' . $q->id . '">' . $q->description . '</td>';
			echo '<td>' . $q->query . '</td>';
			echo '<td id="edit_' . $q->id . '"><a class="btn btn-outline-primary btn-sm" href="javascript:edit_stored_query(' . $q->id . ');">Edit</a></>';
			echo '<td><a class="btn btn-danger btn-sm" href="javascript:delete_stored_query(' . $q->id . ');">Delete</a></td>';
			echo '</tr>';
		}
		?>
	</tbody>
</table>