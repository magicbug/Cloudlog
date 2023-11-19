$(document).ready(function(){
	$('#markExportedToLotw').click(function(e){
		let form = $(this).closest('form');
		let station = form.find('select[name=station_profile]');
		if (station.val() == 0) {
			station.addClass('is-invalid');
		}else{
			form.submit();
		}
	})
});