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
function create_svgPath($vector){

	$nStitch=count($vector['x']);

	//turn y-direction
	$y=array();
	$maxy=max($vector['y']);
	for($i=0;$i<$nStitch;$i++){
		$y[]=abs($vector['y'][$i]-$maxy)+100;
	}
	$vector['y']=$y;

	$filestr='';
	$pathstart='<path d="M';
	$pathmarker='" marker-start = "url(#StartMarker)" marker-mid = "url(#MidMarker)" marker-end = "url(#EndMarker)"';

	//$outp=' ';
	$pathno=0;
	$filestr=$filestr.$pathstart.$vector['x'][0].' '.$vector['y'][0].' ';
	for($i=1;$i<$nStitch;$i++){
		if(in_array($i,$vector['path'])){
			$pathend='" style="fill:none;stroke:#'.$vector['colors'][$pathno].';stroke-width:2"/>
			';
			$filestr=$filestr.$pathend.$pathstart;
			$pathno++;
		}
		$filestr=$filestr.$vector['x'][$i].' '.$vector['y'][$i].' ';
	}
	$filestr=$filestr.$pathend;

	return $filestr;
}

function svg_string($svgstr){
	
	$savestring='<?xml version="1.0" standalone="no"?>
	<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN"
	"http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">

	<svg width="100%" height="100%" version="1.1"
	xmlns="http://www.w3.org/2000/svg">
	';
	$savestring.=$svgstr.'</svg>';
	
	return $savestring;
}

function hex2decEXP($hex){
	$dec=hexdec($hex);
	if ($dec>128) {$dec=$dec-256;}
	
	return $dec;
}

function read_exp($filecontent){

	$prevx=0;
	$prevy=0;
	$nchar=strlen($filecontent);
	$ndupl=floor($nchar/2);

	//$colors=array();
	$x=array();
	$y=array();
	$newpath=array();
	
	$z=0; //stitch number
	$jumpnext=0; //1 if next stitch is a jumpstitch
	for($i=0;$i<$ndupl;$i++){ 
		$b=array();
		for($q=0;$q<2;$q++){ $b[]=bin2hex(substr($filecontent,2*$i+$q,1)); }

		if($b[0]=='80'){
			$lastpath=end($newpath);
			if($lastpath!=$z){
				$newpath[]=$z;
				$colors[]='000000';
			}
			$jumpnext=1;
		}else{
			$prevx=$prevx+hex2decEXP($b[0]);
			$prevy=$prevy+hex2decEXP($b[1]);

			if($jumpnext==1){
				$jumpnext=0;
			}else{
				$x[]=$prevx;
				$y[]=$prevy;
				if(count($newpath)==0) $newpath[]=0;
				$z++;
			}
		}
	}

	//min x/y to 0
	$minx=min($x);
	$miny=min($y);
	$nstitch=count($x);
	for($s=0;$s<$nstitch;$s++){
		$x[$s]=$x[$s]-$minx;
		$y[$s]=$y[$s]-$miny;
	}
	
	$vector=array('x'=>$x, 'y'=>$y, 'path'=>$newpath, 'colors'=>$colors);
	
	return $vector;

}
function read_pcs($filecontent){
	
	$colors=array();
	for($c=0;$c<16;$c++){ 
		$colors[]=bin2hex(substr($filecontent,4+$c*4,3));
		}

	$st2=bin2hex(substr($filecontent,68,1));
	$st1=bin2hex(substr($filecontent,69,1));
	$noStitches=hexdec($st1.$st2);

	$StitchesLeft=$noStitches;
	$x=array();
	$y=array();
	$colorshift=array();
	$colno=array();
	$z=0;
	$cc=70;
	while($StitchesLeft>0){
		$b=array();
		for($i=0;$i<9;$i++){
			$b[$i]=bin2hex(substr($filecontent,$cc,1));
			$cc++;
			}
		if($b[8]=='03'){
			$colorshift[]=$z;
			$colno[]=$colors[hexdec($b[0])];
		}else{
		$x[]=hexdec($b[3].$b[2].$b[1]);
		$y[]=hexdec($b[7].$b[6].$b[5]);
		if(count($colorshift)==0) $colorshift[]=0;
		$z++;
		$StitchesLeft=$StitchesLeft-1;
		}
	}
	
	$vector=array('x'=>$x, 'y'=>$y, 'path'=>$colorshift, 'colors'=>$colno);
	
	return $vector;

}
//extract file extension
function read_embr($filecontent){

	$first=bin2hex(substr($filecontent,0,1));
	$third=bin2hex(substr($filecontent,2,1));
	$forth=bin2hex(substr($filecontent,3,1));
	if($first=='32' & $third=='10' & $forth=='00'){
		$vector=read_pcs($filecontent);
	}else{$vector=read_exp($filecontent);}
	
	return $vector;
}

$v=read_embr(urldecode($_POST['sourcefile']));
$str=create_svgpath($v);
$content_file = svg_string($str);
$name=$_POST['sourcename'];

header('Pragma: public');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0');
header("Cache-Control: private",false);
header('Content-Transfer-Encoding: none');//binary?
header('Content-Type: image/svg+xml');
header('Content-Disposition: attachment; filename="'.$name.'.svg"');
echo $content_file;
exit();

?>