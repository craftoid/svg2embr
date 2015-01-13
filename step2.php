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

//extract file extension
function findext ($filename)
{
$filename = strtolower($filename) ;
$exts = split("[/\\.]", $filename) ;
$n = count($exts)-1;
$ext = $exts[$n];
return $ext;
}

function findname ($filename){

$ext = pathinfo($filename, PATHINFO_EXTENSION);
$name = basename($filename, ".".$ext);

return $name;
}


if(isset($_FILES['srcfile']['name'])){

	$err='';
	$sourcename=$_FILES['srcfile']['name'];
	if(strlen($sourcename)==0){
		$err=$err.'-No source file specified.</br>';
	}
	
	$ext=findext($sourcename);
	if(!($ext=='svg' || $ext=='pcs' || $ext='exp')){
		$err=$err.'-Expected source file format: svg, pcs or exp.</br>';
	}

	$sourcefile=$_FILES['srcfile']['tmp_name'];
	if(strlen($err)==0){
		$fo=fopen($sourcefile, 'r');
		if($fo==false){
			$err=$err.'-Error on opening source file.</br>';
		} else {
			$content=fread($fo,filesize($sourcefile));
			fclose($fo);

			if($ext=='svg'){
				$htmlCode='
					<div id="stylized" class="myform">
					<form id="form" name="form" action="save_pcs_exp.php" method="post">
					
					<h1>Convert from svg to embroidery file (exp)</h1>
					<p>Keep path-nodes as stitches or specify maximum stichlength.</p>
					
					<label class="first">Stitchlength
						<span class="small">leave blank if original nodes</span>
					</label>
					<div id="int">
						<Input type="text" class="integ" name="stlength" />
						<label class="last">mm</label>
					</div>
					
					<p>Image width defined by svg page 1px = 1/10mm</p>
					
					<label class="first">Combine 
						<span class="small">all paths to one path</span>
					</label>
					<div id="int">
						<input type="checkbox" name="chkCombine" />
					</div>
					
					<div id="buttons">
					<button type="submit" name="convert" onClick="this.form.submit();" >Convert</button>
					</div>
					
					<input type="hidden" name="sourcefile" value="'.urlencode($content).'">
					<input type="hidden" name="sourcename" value="'.findname($sourcename).'">
					
					<div class="spacer"></div>
					
					</form>
					
					</div>
			';
			//<button type="submit" name="preview" onClick="this.form.action=/"preview.php/";this.form.target=/"_blank/";this.form.submit()">Preview</button>
			} else {
				$htmlCode='
					<div id="stylized" class="myform">
					<form id="form" name="form" action="save_svg.php" method="post">
					
					<h1>Convert from '.$ext.' to svg</h1>
					<p>Stitches will be converted to path-nodes.</p>
					
					<div id="buttons">
					<button type="submit" name="convert" onClick="this.form.submit();" >Convert</button>
					</div>
					
					<input type="hidden" name="sourcefile" value="'.urlencode($content).'">
					<input type="hidden" name="sourcename" value="'.findname($sourcename).'">
					
					<div class="spacer"></div>
					
					</form>
					
					</div>
			';
			}
		}
	}

	if(strlen($err)>0){
		$htmlCode='<div id="stylized" class="myform">
<h1>Convert between svg and embroidery files</h1>
		<div id="validation">
			'.$err.'
			</div>
			<a href="index.php?page=home"><button>Back</Button></a>
			</div>';
	}
}else{

$htmlCode='<div id="stylized" class="myform">
<h1>Convert between svg and embroidery files</h1>
<p>No source file uploaded. Try <a href="index.php?page=home">startpage</a></p>
<a href="index.php?page=home"><button>Home</Button></a>
</div>';

}

?>