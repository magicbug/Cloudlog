$('.labeltable').on('click', 'input[type="checkbox"]', function() {
	var clickedlabelid = $(this).closest('tr').attr("class");
	clickedlabelid = clickedlabelid.match(/\d+/)[0];
	saveDefault(clickedlabelid);
    $('input:checkbox').not(this).prop('checked', false);  
});

function saveDefault(id) {
	$.ajax({
		url: base_url + 'index.php/labels/saveDefaultLabel',
		type: 'post',
		data: {'id': id},
		success: function (html) {
		}
	});
}