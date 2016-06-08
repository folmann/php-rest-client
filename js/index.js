var path = window.location.href;

if (path.indexOf("index.html") > -1) {

	$('#passagens').on('click', function(event) {
		$('#hoteis-principal').hide();
  		$('#passagens-principal').show();
	});
	$('#hoteis').on('click', function(event) {
		$('#hoteis-principal').show();
  		$('#passagens-principal').hide();
	});

	var picker1 = create_picker('passagem-ida');
	var picker2 = create_picker('passagem-volta');
	var picker3 = create_picker('hotel-ida');
	var picker4 = create_picker('hotel-volta');
}
else if (path.indexOf("compra.php") > -1) {

	$('td input').on('click', function(event){
		var tr = $(this).parent().parent().parent();	
		tr.find('select').each(function(index){
			$(this).prop('disabled', false);
		});
		tr.siblings().find('select').each(function(index){
			$(this).prop('disabled', true);
		});
	});

	$("input:checkbox").on('click', function() {
		var $box = $(this);
  		if ($box.is(":checked")) {
    		var group = "input:checkbox[name='" + $box.attr("name") + "']";
    		$(group).prop("checked", false);
    		$box.prop("checked", true);
  		} else {
    		$box.prop("checked", false);
  		}
	});		
}

function create_picker(id) {
	return new Pikaday({
        field: document.getElementById(id),
        firstDay: 1,
        minDate: new Date(2000, 0, 1),
        maxDate: new Date(2020, 12, 31),
        yearRange: [2000,2020]
	});
}