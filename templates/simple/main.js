 function AddImages1() {
	var tbl = document.getElementById("imageup1");
	var lastRow = tbl.rows.length;
	var iteration = lastRow+1;
	var row = tbl.insertRow(lastRow);
	
	var cellRight = row.insertCell(0);
	cellRight.innerHTML = '<span">'+iteration+': <'+'/'+'span>';
	
	cellRight = row.insertCell(1);
	
	var el = document.createElement('input');
	el.setAttribute('type', 'file');
	el.setAttribute('name', 'local_uploadfile[' + iteration + ']');
	el.setAttribute('size', '35');
	cellRight.appendChild(el);
}
function RemoveImages1() {
	var tbl = document.getElementById('imageup1');
	var lastRow = tbl.rows.length;
	if (lastRow > 1){
		tbl.deleteRow(lastRow - 1);
	}
}
/*
function AddImages2() {
	var tbl = document.getElementById("imageup2");
	var lastRow = tbl.rows.length;
	var iteration = lastRow+1;
	var row = tbl.insertRow(lastRow);
	
	var cellRight = row.insertCell(0);
	cellRight.innerHTML = '<span">'+iteration+': <'+'/'+'span>';
	
	cellRight = row.insertCell(1);
	
	var el = document.createElement('input');
	el.setAttribute('type', 'text');
	el.setAttribute('name', 'web_uploadfile[' + iteration + ']');
	el.setAttribute('size', '50');
	cellRight.appendChild(el);
}
function RemoveImages2() {
	var tbl = document.getElementById('imageup2');
	var lastRow = tbl.rows.length;
	if (lastRow > 1){
		tbl.deleteRow(lastRow - 1);
	}
}
*/
function select_field (field)
{
	field.focus();
	field.select();
}

$(document).ready(function(){
	//$("#textarea").resizer();
	
	//дисейблим
	$(".resize_elements").attr("disabled","disabled");
	$(".preview_elements").attr("disabled", "disabled");

	
	$('#resize').click(function() {
		if($('#resize').attr('checked'))
			$(".resize_elements").attr("disabled", "");
		else
			$(".resize_elements").attr("disabled", "disabled");
	});

	$('#preview').click(function() {
		if($('#preview').attr('checked'))
			$(".preview_elements").attr("disabled", "");
		else
			$(".preview_elements").attr("disabled", "disabled");
	});
	
	$('.code_fields').click(function() {
		$(this).select()
	});
	
	$('.code_fields').mousedown(function(e) {
		if (e.which === 3) {
			$(this).select()
		}
	});
	
	$('.preview_elements').click(function() {
		if($('#dimensions').attr('checked') || $('#nothing').attr('checked'))
			$("#preview_text").val("");
		else if($('#your_text').attr('checked'))
			$("#preview_text").val("Увеличить");
	});

});
