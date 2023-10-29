function reassign(call,target_profile_id) {
	let qsoids = [];
	let elements = document.getElementsByName("cBox[]");
	elements.forEach((item) => {
		if (item.checked) {
			qsoids.push(item.value);
		}
	});
	$.ajax({
		url: base_url + 'index.php/maintenance/reassign',
		type: 'post',
		data: {'call': call, 'station_id': target_profile_id, 'qsoids' : qsoids},
		success: function (resu) {
			if (resu.status) {
				location.reload();
			}
		}
	});
}

function toggleAll(source) {
	console.log('test');
	if (source.checked) {
		let elements = document.getElementsByName("cBox[]");
		elements.forEach((item) => {
			item.checked = true;
		})
		source.checked = true;
	}
	if (!source.checked) {
		let elements = document.getElementsByName("cBox[]");
		elements.forEach((item) => {
			item.checked = false;
		})
		source.checked = false;
	}
}

function updateCallsign(item) {
	let text = item.options[item.selectedIndex].text
	let call = text.substr(text.lastIndexOf('(')+1,(text.lastIndexOf(')')-text.lastIndexOf('(')-1));
	document.getElementById("station_call").innerHTML = call;
}
