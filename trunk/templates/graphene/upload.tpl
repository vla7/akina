<form enctype="multipart/form-data" method="post">
<input type="hidden" name="action" value="upload" />
		<table id="uploadnew">
			<td width="50%" valign="top">
				Загрузка с компьютера:
				<br />
				<div class="emulated_input">
					<input name="width" type="text" style="width:170px;" value="ничего не выбрано">
					<button onclick="return false;">Выбрать файлы</button>
				</div>
				<input id="realfileinput" type="file" multiple="multiple" accept="image" name="local_uploadfile[]">
				<div><small>* используйте Ctrl для выбора нескольких картинок</small></div>
			<td width="50%" valign="top">
				Загрузка по ссылкам:
				<br />
				<textarea cols="50" rows="4" name="web_uploadfile"></textarea><br />

			</td>
		</table>

<div align="center"> 

<fieldset class="fieldsets">
		<legend><label> <input type="checkbox" id="resize" name="resize" value="true" {auto_resize}/> Уменьшить изображение</label><br /></legend>
				<br/>

				<label>Ширина: <input name="width" size="10" type="text" class="resize_elements" {width_resize_elements}></label>
				<label>Высота: <input name="height" size="10" type="text" class="resize_elements" {height_resize_elements}></label><br /><br />
		</fieldset>

		<fieldset class="fieldsets">
		<legend><label><input type="checkbox" id="preview" name="thumb" value="true" {auto_preview}/> Создать превью</label><br /></legend>
				<br />
				<label>Ширина: <input name="thumb_width" size="10" type="text" class="preview_elements" {width_preview_elements}></label>
				<label>Высота: <input name="thumb_height" size="10" type="text" class="preview_elements" {height_preview_elements}></label><br /><br />

				Текст на превью:
				<br /><br />
				<label><input type="radio" name="texttype" value="dimensions" checked class="preview_elements" id="dimensions">Размеры</label>&nbsp;&nbsp;
				<label><input type="radio" name="texttype" value="your_text" class="preview_elements" id="your_text">Ваш текст</label>&nbsp;&nbsp;
				<label><input type="radio" name="texttype" value="nothing" class="preview_elements" id="nothing">Ничего</label><br /><br />
				<label><input type="text" name="text" size="25" class="preview_elements" id="preview_text" /><br /><br />
		</fieldset>

		<br />
		<input type="submit" value="Загрузить">

</div>

</form>

