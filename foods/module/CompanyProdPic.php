<?php

/*
	[插件名称] 餐馆主页最新美食图片 - 替换模版标签{#modCompanyProdPic#}
	[适用范围] 全站
	[文 件 名] CompanyProdPic.php
	[更新时间] 2006/10/15
*/


function CompanyProdPic(){

	global $fsql,$charset,$tbl_products,$CatchOpen,$MenuInfo;
	global $strMore,$NowMenuid,$NowCompanyId;

	$PSET=PlusSet("modCompanyProdPic");

	$shownums=PlusDef($PSET["shownums"],"4");
	$hangnums=PlusDef($PSET["hangnums"],"4");
	$smallw=PlusDef($PSET["smallw"],"122");
	$smallh=PlusDef($PSET["smallh"],"85");
	$ord=PlusDef($PSET["ord"],"uptime");
	$sc=PlusDef($PSET["sc"],"desc");
	$cutword=PlusDef($PSET["cutword"],"10");
	$target=PlusDef($PSET["target"],"_self");
	$more=PlusDef($PSET["showmore"],$strMore);
	$tempname=PlusDef($PSET["tempname"],"tpl_prodpic.htm");

	$morelink=ROOTPATH."products/class/?".$catid.".html";

	$scl=" iffb='1' and picsrc!='' and pictype!='swf'  and memberid='$NowCompanyId' ";





		//模版解释
		$Temp=LoadTemp(ROOTPATH."templates/".$MenuInfo["skin"]."/".$tempname);
		$TempArr=SplitTblTemp($Temp);

		
		$str=$TempArr["start"];


		$kk=1;
		$havepic=0;
		$fsql->query("select * from $tbl_products where $scl order by $ord $sc limit 0,$shownums");
		
		while($fsql->next_record()){
			
			$id=$fsql->f('id');
			$title=$fsql->f('title');
			$catpath=$fsql->f('catpath');
			$dtime=$fsql->f('dtime');
			$memberid=$fsql->f('memberid');
			$nowcatid=$fsql->f('catid');
			$author=$fsql->f('author');
			$source=$fsql->f('source');
			$cl=$fsql->f('cl');
			$src=$fsql->f('picsrc');
			$type=$fsql->f('pictype');

			if($src==""){
					$src="images/nophoto.gif";
			}
			$src=ROOTPATH.$src;
			
			
			$comdir=$memberid+100000;

			if($CatchOpen=="1" && file_exists(ROOTPATH."com/".$comdir."/products/html/".$id.".html")){
				$link=ROOTPATH."com/".$comdir."/products/html/".$id.".html";
			}else{
				$link=ROOTPATH."com/".$comdir."/products/html/?".$id.".html";
			}


			if($cutword!="0"){$title=csubstr($title,0,$cutword,$charset);}

			if($kk==1){
				$str.=$TempArr["rowstrat"];
			}
			if($smallw=="-1"){$xsmallw="";}else{$xsmallw="width='$smallw' ";}
			if($smallh=="-1"){$xsmallh="";}else{$xsmallh="height='$smallh' ";}

			$var=array (
			'title' => $title, 
			'src' => $src, 
			'smallw' => $xsmallw, 
			'smallh' => $xsmallh, 
			'link' => $link,
			'target' => $target,
			);
			$str.=ShowTplTemp($TempArr["menu"],$var);

			if($kk==$hangnums){
				$str.=$TempArr["rowend"];
				$kk=0;
			}

			$kk++;
			$havepic++;
		}
		
		//补充空的td

		if($kk!=1){
			$needtd=$hangnums-$kk+1;
			for($u=1;$u<=$needtd;$u++){
				$str.=$TempArr["blank"];
			}
				$str.=$TempArr["rowend"];
		}
		
        $str.=$TempArr["end"];


		
		$morestr=str_replace("{#more#}",$more,$TempArr["more"]);
		$morestr=str_replace("{#morelink#}",$morelink,$morestr);
		
		$str.=$morestr;
		

		return $str;


}

?>