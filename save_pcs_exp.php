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
include("convert2stitch.php");

function dec2hexEXP($dec){
	if ($dec<0) {$dec=$dec+256;}
	$hexstr=dechex($dec);
	if (strlen($hexstr)==1) {$hexstr='0'.$hexstr;}
	
	return $hexstr;
}

function splitJump($dx,$dy){
	$jump=array('x'=>array(), 'y'=>array());
	$integ=$dx;
	$integ2=$dy;
	if (abs($dx) < abs ($dy)){
		$integ=$dy;
		$integ2=$dx;
		}
	$neg=$integ/abs($integ);
	$integ=abs($integ);
	$nJumps=ceil($integ/127);
	$jumpArr=array();
	$jumpArr2=array();
	$rest=$integ;
	$rest2=$integ2;
	$firstJump=floor($integ/$nJumps);
	$firstJump2=round($integ2/$nJumps);
	$next=true;
	while($next==true){
		if($rest>$firstJump){
			$jumpArr[]=$neg*$firstJump;
			$rest=$rest-$firstJump;
			$jumpArr2[]=$firstJump2;
			$rest2=$rest2-$firstJump2;
		}else{
			$jumpArr[]=$neg*$rest;
			$jumpArr2[]=$rest2;
			$next=false;
		}
	}
	if (abs($dx) > abs ($dy)){
		$jump['x']=$jumpArr;
		$jump['y']=$jumpArr2;
	}else{
		$jump['y']=$jumpArr;
		$jump['x']=$jumpArr2;	
	}
	
	return $jump;
}

function hex2string($hex){
	$str='';
    for ($i=0; $i < strlen($hex)-1; $i+=2){
        $str .= chr(hexdec($hex[$i].$hex[$i+1]));
    }
	return $str;	
}

function exp_string($vector){

	$savestring='';
	$noStitch=count($vector['x']);
	
	$z=0;

	for($s=0;$s<$noStitch;$s++){
		if(in_array($s,$vector['path'])){ // path start / jumpstitch
			$str='';
			if($s==0){
				$dx=$vector['x'][0];
				$dy=$vector['y'][0];
			}elseif(in_array($s,$vector['path'])){//jumpstitch new path
				$dx=$vector['x'][$s]-$vector['x'][$s-1];
				$dy=$vector['y'][$s]-$vector['y'][$s-1];
				$str=hex2string('80010000');
			}
			//echo $dx.' '.$dy."\r\n";
			if(abs($dx)>127 || abs($dy)>127){
				//echo 'split'."\r\n";
				$split=splitJump($dx,$dy);
				
				for($dd=0;$dd<count($split['x']);$dd++){
					if($dd<count($split['x'])-1){$str.=hex2string('8004');}
					$str.=hex2string(dec2hexEXP($split['x'][$dd])).hex2string(dec2hexEXP($split['y'][$dd]));
					//echo $split['x'][$dd].' '.$split['y'][$dd]."\r\n";
				}
				//echo "\r\n";
			}else{
				$str.=hex2string('8004').hex2string(dec2hexEXP(round($dx/2))).hex2string(dec2hexEXP(round($dy/2)));
				$str.=hex2string(dec2hexEXP($dx-round($dx/2))).hex2string(dec2hexEXP($dy-round($dy/2)));
				}
			$savestring.= $str;
			$z++;
		}else{
			$dx=$vector['x'][$s]-$vector['x'][$s-1];
			$dy=$vector['y'][$s]-$vector['y'][$s-1];
			$str='';
			//echo $dx.' '.$dy."\r\n";
			if(abs($dx)>127 || abs($dy)>127){
				$split=splitJump($dx,$dy);
				for($dd=0;$dd<count($split);$dd++){
					$str.=hex2string(dec2hexEXP($split['x'][$dd])).hex2string(dec2hexEXP($split['y'][$dd]));
					}
			}else{
				$str=hex2string(dec2hexEXP($dx));
				$str.=hex2string(dec2hexEXP($dy));
			}
			$savestring.= $str;
		}
	}

	return $savestring;
}

$stlen=$_POST['stlength'];
if(strlen($stlen)==0) $stlen=-1;
if(isset($_POST['chkCombine'])){$combine=1;}else{$combine=0;}

$unitsPrCm=100;

$v=svg2vector(urldecode($_POST['sourcefile']),$stlen,$combine,$unitsPrCm);

$content_file = exp_string($v);//}
$name=$_POST['sourcename'];

header('Pragma: public');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0');
header("Cache-Control: private",false);
header('Content-Transfer-Encoding: none');//binary?
header('Content-Type: application/octet-stream');
//if ($format=='exp'){
header('Content-Disposition: attachment; filename="'.$name.'.exp"');//}else{header('Content-Disposition: attachment; filename="myembroidery.pcs"');}
echo $content_file;
exit();


?>