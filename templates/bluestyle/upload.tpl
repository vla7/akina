<form enctype="multipart/form-data" method="post">
<input type="hidden" name="action" value="upload" />
		<table id="uploadnew" width="690px" border=0>
		<tr >
			<td colspan="3" valign="top">
				<b>Загрузить с компьютера:</b>
				<div class="emulated_input">
					<input name="width" type="text" style="height:30px;width:400px;" placeholder="ничего не выбрано" >
					<button class="filebutton" onclick="return false;">ВЫБРАТЬ ФАЙЛЫ</button>
				</div>
				<input id="realfileinput" type="file" multiple="multiple" accept="image" name="local_uploadfile[]">
				<div><small>* мультизагрузка при помощи зажатия клавиши Ctrl и выделения изображений</small></div>
			</td>
		<tr>
			<td width="340px" valign="top">
				<br/><br/>
				<textarea cols="39" rows="12" name="web_uploadfile" placeholder="Загрузить по ссылке(ам)"></textarea><br />
		<fieldset class="fieldsets">
				<input type="checkbox" class="css-checkbox" id="resize" name="resize" value="true" {auto_resize}/><label for="resize" class="l-chb"> УМЕНЬШИТЬ ИЗОБРАЖЕНИЕ</label><br />
				<br/>
				<div align="right">
				<label>Ширина: <input name="width" size="6" type="text" class="resize_elements" {width_resize_elements}></label><br />
				<label>Высота: <input name="height" size="6" type="text" class="resize_elements" {height_resize_elements}></label><br />
				</div>
		</fieldset>
			</td>
			<td width="10px">&nbsp</td>
			<td width="340px" valign="bottom">

		<fieldset class="fieldsets">
				<input type="checkbox" class="css-checkbox" id="preview" name="thumb" value="true" {auto_preview}/><label for="preview" class="l-chb"> СДЕЛАТЬ ПРЕВЬЮ</label><br />
				<br />
				<div align="right">
				<label>Ширина: <input name="thumb_width" size="6" type="text" class="preview_elements" {width_preview_elements}></label><br />
				<label>Высота: <input name="thumb_height" size="6" type="text" class="preview_elements" {height_preview_elements}></label><br />
				</div>
				<br />
				&nbsp;ТЕКСТ НА ПРЕВЬЮ
				<br />
				<div align="center">
				<input type="text"	name="text"			class="preview_elements"	id="preview_text"	placeholder=""	size="35" /><label ></label><br />
				<input type="radio"	name="texttype"	class="preview_elements"	id="nothing"			value="nothing"							/><label class="l-pe" for="nothing">НИЧЕГО</label>&nbsp;
				<input type="radio"	name="texttype"	class="preview_elements"	id="dimensions"		value="dimensions"	checked	/><label class="l-pe" for="dimensions">РАЗМЕРЫ</label>&nbsp;
				<input type="radio"	name="texttype"	class="preview_elements"	id="your_text"		value="your_text"						/><label class="l-pe" for="your_text">ТЕКСТ</label>
				</div>
		</fieldset>
		</td>
		</table>
<div align="center">
		<button class="filebutton" type="submit" ><img src="{template}/images/downloadicon.png" style="margin: 0px 5px 0px 5px;"> ЗАГРУЗИТЬ</button>
</div>

</form>
<div id="limite" align="center">Разрешенные форматы: JPG, GIF, PNG; max: {max_size_mb} MB; {max_width}x{max_height} px; {max_quantity} одновременно.<br /></div>

