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
switch ($_GET['page']){
	case "home":
		include("home.php");
	break;
	case "step2":
		include("step2.php");
	break;
	case "tut":
		include("tut.php");
	break;
	default:
		include("home.php");
	break;
	}
$contentCode=$htmlCode;

include("links.php");
include("insp.php");
include("inkscapeEmbrTut.php");
include("myembroideries.php");
?>

<html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<head>
    <title>OFFLINE svg2embroidery - Generate embroidery-file from vector-file</title>
        <meta name="keywords" content="convert from svg to embroidery file" />
        <meta name="description" 
        content="Convert between embroidery-format and vector graphics" />
		
<style type="text/css">
	
	body{
	font-family:"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif;
	text-align: center;
	}
	p, h1, form, button{
	border:0; margin:0; padding:0;
	}
	h2{
	font-size: 14px;
	}
	hr { color: #ccc;}
	.spacer{
	clear:both; height:1px;
	}
	table td, table td * {
    vertical-align: top;
	}
	#centered{
	text-align: left;
	width: 850px;
	margin: 0 auto;
	}
	/* ---------- Footer links ----------- */
	#footerlinks{
		margin: 0 auto;
		padding: 10px;
	}
	#footerlinks a:link, a:visited{
		border: 0px;
	}
	#footerelem{
		padding: 5px;
		border: 1px;
		float: left;
	}
	#footerelem img{
		border: none;
		margin_left: 10px;
		height: 30px;
	}
	#footerelem label{
		color:#666666;
		display:block;
		font-size:11px;
		font-weight:normal;
		text-align:middle;
		padding-bottom: 20px;
	}
	div.img{
  margin: 2px;
  border: 1px solid #cccccc;
  height: 320px;
  width: 225px;
  float: left;
}
div.img img{
  display: inline;
  margin: 3px;
  border: 1px solid #ffffff;
  height: 215px;
}
div.imgtut{
  margin: 2px;
  border: 1px solid #cccccc;
  height: 200px;
  width: 225px;
  float: left;
}	
div.imgtut img{
  display: inline;
  margin: 3px;
  border: 1px solid #ffffff;
  width: 215px;
  height: 100px;
}
div.desc
{
	padding: 2px;
  font-weight: normal;
  font-size: 11px;
  width: 220px;
  margin: 2px;
}
div.descIntut
{
	padding: 2px;
  font-weight: normal;
  font-size: 12px;
  width: 650px;
  height:75px;
  margin: 2px;
}
	/* ----------- My Form ----------- */
	.myform{
	margin:0 auto;
	width:400px;
	padding:14px;
	}

	/* ----------- stylized ----------- */
	#stylized{
	border:solid 2px #aaaaff;
	background:#ddddff;
	}
	#stylized h1 {
	font-size:14px;
	font-weight:bold;
	margin-bottom:8px;
	}
	#stylized p{
	font-size:11px;
	color:#666666;
	margin-bottom:20px;
	border-bottom:solid 1px #b7ddf2;
	padding-bottom:10px;
	}
	#stylized label.first{
	display:block;
	font-weight:bold;
	text-align:right;
	width:140px;
	float:left;
	font-size:13px;
	}
	#stylized label.last{
	display:block;
	color:#666666;
	font-size:11px;
	text-align:left;
	width:100px;
	padding-left: 10px;
	vertical-align: middle;
	}
	#stylized .small{
	color:#666666;
	display:block;
	font-size:11px;
	font-weight:normal;
	text-align:right;
	width:140px;
	}
	#stylized input{
	float:left;
	font-size:12px;
	padding:4px 2px;
	border:solid 1px #aacfe4;
	}
	#stylized input.file{
	width:200px;
	margin:2px 0 20px 10px;
	}
	#stylized input.integ{
	width:50px;
	}
	#int{
	float:left;
	padding:4px 2px;
	margin:2px 0 20px 10px;
	}
	#buttons{
	margin-left:45px;
	clear:both;
	}
	#stylized button{
	width:100px;
	height:31px;
	background:#666666;
	text-align:center;
	line-height:31px;
	color:#FFFFFF;
	font-size:11px;
	font-weight:bold;
	}
	
	#validation {
	border: 2px solid #beab44;
	margin:10px auto;
	padding:15px 50px 15px 80px;
	background-repeat: no-repeat;
	background-position: 10px center;
	background-color: #fcec90;
	background-image: url('warning.png');
	width:200px
	}
fieldset {
    padding-top:10px;
    border:1px solid #666;
    border-radius:8px;
    box-shadow:0 0 10px #666;
}
legend {
    float:left;
    margin-top:-20px;
	background:#fff;
	font-weight:bold;
}
legend + * {
    clear:both;
}
	</style>
	
</head>

<body>
	<div id="centered" >
		
		<table border=0>
			<tr>
				<td width=700>
					<a href="index.php?page=home"><img src="svg2embr.bmp" width=700 /></a>
				</td>
				<td width=130 >	
					<div class="desc">
						Ellen Wasb&oslash;</a> &copy 2013</br>
						Any <a href="mailto:svg2embr@wasbo.net">feedback</a> apprechiated.
						</br></br>
						<a href="index.php?page=home"><img src="home.png" width=80 /></a>
					</div>
				</td>
			</tr>
			<tr>
				<td width=700>	
					<?=$contentCode?>
				</td>
				<td width=130 >	
					<div style="font-size:12px;">
					<fieldset>
						<legend>About svg2embroidery</legend>
						Convert vector graphics to embroidery files and vise versa. The <a href="index.php#tut">tutorials</a> will give you some 
						ideas on how to use Inkscape to design or edit your embroidery files.
					</fieldset>
					</div>
					<?=$footercode?>
				</td>
			</tr>
			<tr>
				<td width=700>
				<a name="tut"></a>
				<h2>Tutorials - How to design your own embroideries using Inkscape and svg2embroidery</h2><hr>
				</td>
				<td width=130 >	
				</td>
			</tr>
				<tr>
				<td width=700>
					<?=$tutcode?>
				</td>
				<td width=130 >
									
				</td>
			</tr>
				<tr>
				<td width=700>
				<a name="my"></a>
				<h2>My results</h2><hr>
				</td>
				<td width=130 >	
				</td>
			</tr>
				<tr>
				<td width=700>
				<?=$myembrcode?>
				</td>
				<td width=130 >	
				</td>
			</tr>
			</tr>
				<tr>
				<td width=700>
				<a name="insp"></a>
				<h2>Some inspiration?</h2><hr>
				</td>
				<td width=130 >	
				</td>
			</tr>
			</tr>
				<tr>
				<td width=700>
				<?=$inspcode?>
				</td>
				<td width=130 >	
				</td>
			</tr>
		</table>
					
	</div>
	
</body>

</html>


