<?php

/*
	[插件名称] 推荐下载 - 替换模版标签{#modDownRec#}
	[适用范围] 全站
	[文 件 名] DownRec.php
	[更新时间] 2006/7/30
*/


function DownRec(){

	global $fsql,$charset,$tbl_down_con,$CatchOpen,$DetailCatch;
	global $strMore,$NowMenuid,$MenuInfo;


		$PSET=PlusSet("modDownRec");

		$shownums=PlusDef($PSET["shownums"],"5");
		$ord=PlusDef($PSET["ord"],"id");
		$sc=PlusDef($PSET["sc"],"desc");
		$showtime=PlusDef($PSET["showtime"],"n/j");
		$cutword=PlusDef($PSET["cutword"],"0");
		$target=PlusDef($PSET["target"],"_self");
		$onlytj=PlusDef($PSET["onlytj"],"1");
		$catid=PlusDef($PSET["catid"],"0");
		$showmenuid=PlusDef($PSET["showmenuid"],MenuDef("down",$NowMenuid));
		$more=PlusDef($PSET["showmore"],$strMore);
		$tempname=PlusDef($PSET["tempname"],"tpl_list.htm");
		

		$scl=" iffb='1' ";

		if($showmenuid!="0" && $showmenuid!=""){
			$scl.=" and menuid='$showmenuid' ";

			$fold=MenuFold($showmenuid);
			$morelink=ROOTPATH.$fold."/class/?".$catid.".html&showtj=1";
		}else{
		
			$morelink=ROOTPATH."";
		}


		if($catid!=0 && $catid!=""){
			$catid=fmpath($catid);
			$scl.=" and catpath regexp '$catid' ";
		}



		if($onlytj=="1"){
			$scl.=" and tj='1' ";
		}

		//模版解释
		$Temp=LoadTemp(ROOTPATH."templates/".$MenuInfo["skin"]."/".$tempname);
		$TempArr=SplitTblTemp($Temp);

		$str=$TempArr["start"];

		$kk=0;
		$havepic=0;
		$fsql->query("select * from $tbl_down_con where $scl order by $ord $sc limit 0,$shownums");

		while($fsql->next_record()){
			
			$id=$fsql->f('id');
			$title=$fsql->f('title');
			$menuid=$fsql->f('menuid');
			$catpath=$fsql->f('catpath');
			$dtime=$fsql->f('dtime');
			$url=$fsql->f('url');
			$filesize=$fsql->f('filesize');
			$uptime=$fsql->f('uptime');
			$nowcatid=$fsql->f('catid');
			$ifnew=$fsql->f('ifnew');
			$ifred=$fsql->f('ifred');
			$author=$fsql->f('author');
			$source=$fsql->f('source');
			$cl=$fsql->f('cl');
			$src=$fsql->f('src');
			$type=$fsql->f('type');

			$fold=MenuFold($menuid);
			if($CatchOpen=="1"){
				$link=ROOTPATH.$fold."/html/".$id.".html";
			}else{
				$link=ROOTPATH.$fold."/html/?".$id.".html";
			}

			
			$dtime=date($showtime,$dtime);
			$uptime=date($showtime,$uptime);

			if($cutword!="0"){$title=csubstr($title,0,$cutword,$charset);}
			if(!strstr($url,"http://")){
				$url=ROOTPATH.$url;
			}


			$var=array (
			'title' => $title, 
			'dtime' => $dtime, 
			'uptime' => $uptime, 
			'filesize' => $filesize, 
			'url' => $url, 
			'author' => $author, 
			'source' => $source,
			'cl' => $cl, 
			'link' => $link,
			'target' => $target

			);
			$str.=ShowTplTemp($TempArr["list"],$var);




		$kk++;

		}

		$str.=$TempArr["end"];
		

		$morestr=str_replace("{#more#}",$more,$TempArr["more"]);
		$morestr=str_replace("{#morelink#}",$morelink,$morestr);
		
		$str.=$morestr;

		return $str;

}

?>