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
	el.setAttribute('size', '30');
	cellRight.appendChild(el);
}
function RemoveImages1() {
	var tbl = document.getElementById('imageup1');
	var lastRow = tbl.rows.length;
	if (lastRow > 1){
		tbl.deleteRow(lastRow - 1);
	}
}


// создаём плагин resizer
jQuery.fn.resizer = function() {
  // выполняем плагин для каждого объекта
  return this.each(function() {
    // определяем объект
    var me = jQuery(this);
    // вставляем в после объекта…
    me.after(
      // в нашем случае это наш "ресайзер" и производим обработку события mousedown
      jQuery('<div class="resizehandle"></div>').bind('mousedown', function(e) {
        // определяем высоту textarea
        var h = me.height();
        // определяем кординаты указателя мыши по высоте
        var y = e.clientY;
        // фнкция преобразовывает размеры textarea
        var moveHandler = function(e) { me.height(Math.max(20, e.clientY + h - y)); };
        // функци прекращает обработку событий
        var upHandler = function(e) { jQuery('html').unbind('mousemove',moveHandler).unbind('mouseup',upHandler); };
        // своего рода, инициализация, выше приведённых, функций
        jQuery('html').bind('mousemove', moveHandler).bind('mouseup', upHandler);
      })
    );
  });
}

$(document).ready(function(){
	$("#textarea").resizer();
	
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


