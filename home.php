<?php
/*
Copyright: Ellen Wasbø 2013 http://svg2embr.wasbo.net

    This file is part of svg2embroidery.

    svg2embroidery is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    svg2embroidery is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with svg2embroidery.  If not, see <http://www.gnu.org/licenses/>.
*/
$htmlCode='
		<div id="stylized" class="myform">
		<form id="form" name="form" action="index.php?page=step2" method="post" enctype="multipart/form-data">
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
		<h1>Convert between svg and embroidery files </h1>
		<p>Upload .svg (vector graphics file) or .pcs (emboridery file) and 
		convert to .svg or .pcs/.exp format. Go to the <a href="index.php#tut">tutorial</a> for help.
		</p>
		
		<label class="first">Source file
			<span class="small">svg, pcs or exp format</span>
		</label>
		<Input type="file" class="file" name="srcfile" />
		
		<div id="buttons">
		<button type="submit" name="preview" onClick="this.form.submit()">Continue</button>
		</div>
		
		<div class="spacer"></div>
		
		</form>
		
		</div>
';

	?>