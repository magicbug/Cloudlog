function reassign(call,target_profile_id) {
	$.ajax({
		url: base_url + 'index.php/maintenance/reassign',
		type: 'post',
		data: {'call': call, 'station_id': target_profile_id},
		success: function (resu) {
			if (resu.status) {
				location.reload();
			}
		}
	});
}
