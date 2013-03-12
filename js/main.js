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
	$('#realfileinput').change(function(){
		var f=$(this)[0].files.length;

		if(f>1)
			$('.emulated_input input').val("Выбрано "+f+" файлов");
		else if(f==1)
		{
			var pieces=$(this).val().split("\\");
			$('.emulated_input input').val(pieces[pieces.length-1]);

		}
	});

	$('.emulated_input').click(function(){
		$('#realfileinput').trigger('click');
	});

	//akina-lightbox
	$(".prev a").click(function(){
		var img = $(this).children('img');
		var src = img.attr("src");

		var w = parseInt(img.attr("rw"));
		var h = parseInt(img.attr("rh"));
		var k = h/w; //коэффициент пропорций изображения
		var wh = $(window).height();
		var ww = $(window).width();
		var scrollTop = $(window).scrollTop();

		if((w+30)>=ww)//ширина имейджа больше экрана
		{
			var left = "0px";
			var sw = ww-32;
			var marginLeft = "5px";
		}
		else
		{
			var w2 = w/2;
			var marginLeft = "-"+w2+"px";
			var left = "50%";
			var sw = w;
		}
		//определяем высоту блока
		var sh=sw*k;

		//if((h+20)>=wh)//высота имейджа больше экрана
		if(sh+30>=wh)
			var top = scrollTop+"px";
		else
		{
			var h2 = sh/2;
			var margintop = "-"+h2+"px";
			var top = wh/2-sh/2+scrollTop;
		}

		if($("div.hbox").length == 0)
		{
			$('body').append('<div class="modal-backdrop fade in" onclick="$(\'div.hbox\').fadeOut(300, function() { $(\'div.hbox\').remove();$(\'div.modal-backdrop\').remove();})"></div>');
			$('body').append('<div class="hbox" onclick="$(this).fadeOut(300, function() { $(this).remove();$(\'div.modal-backdrop\').remove();})"><img src="'+src+'"></div>');
			$("div.hbox").css("top",wh/2+scrollTop+"px");
		}
		else
			$("div.hbox").children('img').attr('src',src);

		$("div.hbox").animate({
				top: top,
				left: left,
				marginLeft: marginLeft,
				height: sh+"px",
				width: sw+"px"
				}, 300, function() {
				//$("div.hbox").attr('src',src)// Animation complete.
			});
		$(this).blur();
		return false;
	})

	$("#textarea").resizer();

	//дисейблим
	if(!$('#resize').attr('checked')){
		$(".resize_elements").attr("disabled", "disabled");
	}
	if(!$('#preview').attr('checked')){
		$(".preview_elements").attr("disabled", "disabled");
	}


	$('#resize').click(function() {
		if($('#resize').attr('checked')){
			$(".resize_elements").removeAttr("disabled");
			$('input:text[name=width]').focus();
		}
		else
			$(".resize_elements").attr("disabled", "disabled");
	});

	$('#preview').click(function() {
		if($('#preview').attr('checked')){
			$(".preview_elements").removeAttr("disabled");
			$('input:text[name=thumb_width]').focus();
	//		$('#dimensions').attr('checked','checked');
	//		$('.preview_elements').trigger('click');
			preview_init();
		}
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
	preview_init();
	});

	function preview_init(){
		if($('#dimensions').attr('checked'))
			$("#preview_text").val("").attr("disabled", "disabled");
		else if ($('#nothing').attr('checked'))
			$("#preview_text").val("").attr("disabled","disabled");
		else if($('#your_text').attr('checked'))
			$("#preview_text").val("Увеличить").removeAttr("disabled").focus();
	};

});