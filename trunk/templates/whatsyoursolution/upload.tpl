<form enctype="multipart/form-data" method="post">
<input type="hidden" name="action" value="upload" />
		<div class="right_left">
		  <h2>Загрузить с компьютера:</h2>
			 <input type="file" multiple="multiple" accept="image" size="30" name="local_uploadfile[]">
		</div>
		<div class="right_right">
		  <h2>Загрузить по ссылке(ам):</h2>
		  <textarea name="web_uploadfile" class="linksarea" id="textarea"></textarea>
		</div>
    <div style="clear:both;"></div>

<div align='center'>
		<fieldset class='fieldsets'>
		<legend><label> <input type="checkbox" id="resize" name="resize" value="true" /> Уменьшить изображение</label><br /></legend>
				<br/>

				<label>Ширина: <input name="width" size="10" type="text" class='resize_elements'></label>
				<label>Высота: <input name="height" size="10" type="text" class='resize_elements'></label><br /><br />
		</fieldset>

		<fieldset class='fieldsets'>
		<legend><label><input type="checkbox" id="preview" name="thumb" value="true" /> Создать превью</label><br /></legend>
				<br />
				<label>Ширина: <input name="thumb_width" size="10" type="text" value="180" class="preview_elements"></label>
				<label>Высота: <input name="thumb_height" size="10" type="text" class="preview_elements"></label><br /><br />

				Текст на превью:
				<br />
				<label><input type="radio" name="texttype" value="dimensions" checked class="preview_elements" id="dimensions">Размеры</label>&nbsp;&nbsp;
				<label><input type="radio" name="texttype" value="your_text" class="preview_elements" id="your_text">Ваш текст</label>
				<label><input type="radio" name="texttype" value="nothing" class="preview_elements" id="nothing">Ничего</label>&nbsp;&nbsp;<br />
				<label><input type="text" name="text" size="25" class="preview_elements" id="preview_text" /><br /><br />
		</fieldset>
		<br /><br/ >
		<input type="submit" value="Загрузить">
</div>

</form>

