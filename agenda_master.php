<?
 /**
 * @comment áºº¿ÍÃìÁà¾ÔèÁËÑÇ¢éÍ¡ÒÃ¨Ñ´¨Ñ´ÃÐàºÕÂºÇÒÃÐ
 * @projectCode 57CMSS10
 * @tor 10.4.1 ÃÐººÂèÍÂ¡ÒÃºÑ¹·Ö¡ÇÒÃÐ¡ÒÃ»ÃÐªØÁ Í.¡.¤.È.
 * @package core
 * @author Sathianphong Sukin
 * @access public
 * Test by finance
 * @created 23/01/2015
 */
###################################################################
## AGENDA  : CMSS áºº¿ÍÃìÁà¾ÔèÁËÑÇ¢éÍ¡ÒÃ¨Ñ´¨Ñ´ÃÐàºÕÂºÇÒÃÐ
###################################################################
## Version :			20100630.001 (Created/Modified; Date.RunNumber)
## Created Date :	2010-06-30
## Created By :		Mr.PUDIS PROMSRI (PAAK)
## E-mail :				pudis@sapphire.co.th
## Tel. :				086-1860538
## Company :		Sappire Research and Development Co.,Ltd. (C)All Right Reserved
###################################################################
header("content-type: text/html; charset=tis-620");  
session_start();
include ("config.inc.php");
//require_once("../../config/cmss_var.php");
include("../../config/conndb_nonsession.inc.php");
include("../../common/common_competency.inc.php");
include("function.php"); 

$xsiteid=($_SESSION[session_agenda] != 3)?$_SESSION[siteid]:$xsiteid;
$user_id=$_SESSION[siteid];

$where_site = "";
if($_GET[area] != ""){
	$where_site .= " AND siteid = '".$_GET[area]."'";
}

$calendar_size = 440 ;
$sql="select secname from eduarea where secid='$xsiteid'";
$result_edu=mysql_db_query($cmss_master, $sql);
$row=mysql_fetch_array($result_edu);
$caption= htmlspecialchars($row[secname]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html><head>
<link rel='stylesheet' type='text/css' href='fullcalendar/redmond/theme.css' />
<link rel='stylesheet' type='text/css' href='fullcalendar/fullcalendar.css' />
<script type='text/javascript' src='fullcalendar/jquery/jquery.js'></script>
<!--<script type="text/javascript" src="../../common/js/jquery-1.8.2.js"></script>-->
<!--<script type='text/javascript' src='js/chosen/chosen.min.css'></script>-->
<script type='text/javascript' src='fullcalendar/jquery/jquery-ui-custom.js'></script>
<script type='text/javascript' src='fullcalendar/fullcalendar.min_icon.js'></script>
<link href="../../common/css/zebra_datepicker.css" type="text/css" rel="stylesheet">

<script type="text/javascript" src="../../common/js/ZebraDatepickerTh.min.js"></script>
<!--<script language="javascript" src="js/daily_popcalendar2.js"></script>-->
<script type='text/javascript'>
	$(document).ready(function() {
		 $('.datepicker').Zebra_DatePicker({
            format: 'd/m/Y'
        });
		
		$('#list_meeting').hide();
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		thai_year = y+543;
		getBoxNumber(thai_year);
		
				$('#graph_mode').click(function(){
					$('#garph_pie').show('fast');
					$('#list_meeting').hide('slow');
				});
				
				$('#list_mode').click(function(){
					$('#garph_pie').hide('fast');
					$('#list_meeting').show('slow');
				});
				
		
		$('#calendar').fullCalendar({
			theme: true,
			header: {
				left: 'title',
				center: '',
				right: 'year,month,agendaWeek,agendaDay prev,next'
			},
			eventClick: function(calEvent, jsEvent, view) {
					$('#garph_pie').hide('slow');
					$('#list_meeting').show('slow');
			},
			
			editable: <?=$_SESSION[session_agenda] == 2 ?"false":"false"?>,
			events: [
				<?php

//						$strSQL=" /**/SELECT  *,DATE_ADD(date_start, INTERVAL -1 DAY) AS before_date 
//						FROM  agenda_profile_main WHERE confirm!='Y' ORDER BY date_start DESC ";
						$strSQL = " 
						SELECT count(profile_id) AS cn,date_start,time_start,DATE_ADD(date_start, INTERVAL -1 DAY) AS before_date 
						FROM  agenda_profile_main WHERE 1=1
						$where_site
						GROUP BY date_start
						ORDER BY date_start DESC
";	
//						echo "<pre>".$strSQL;
//						exit;
						$result_time=mysql_db_query($dbmaster, $strSQL);
						
						while($Row_Info_time = mysql_fetch_assoc($result_time)){
						
							$date_pick['Y'] = substr($Row_Info_time[date_start],0,4);
							$date_pick['m'] = intval(substr($Row_Info_time[date_start],-5,-3)-1);
							$date_pick['d'] = substr($Row_Info_time[date_start],-2);
							$date_pick['H'] = $Row_Info_time[time_start] ? substr($Row_Info_time[time_start],0,2) : "8";
							$date_pick['i']  = $Row_Info_time[time_start] ? substr($Row_Info_time[time_start],3,2) : "00";
							
							$date_pick_bf['Y'] = substr($Row_Info_time[before_date],0,4);
							$date_pick_bf['m'] = intval(substr($Row_Info_time[before_date],-5,-3)-1);
							$date_pick_bf['d'] = substr($Row_Info_time[before_date],-2);				
						
							$sql="select secname from eduarea where secid='$Row_Info[siteid]'";
							$result_edu=mysql_db_query($dbmaster,$sql);
							$row=mysql_fetch_array($result_edu);
							$caption_site= str_replace("ÊÓ¹Ñ¡§Ò¹à¢µ¾×é¹·Õè¡ÒÃÈÖ¡ÉÒ","",htmlspecialchars($row[secname]));
							
							//$sitename = ($_SESSION[session_agenda] == "3" ) ? $caption_site." " : "";
							//$title = //$Row_Info_time[profile_name];
							
							$ic = array();
							$ic[] = "images/promo_green.png";
							$ic[] = "images/promo_green_light.png";
							$ic[] = "images/promo_orange.png";
							$ic[] = "images/promo_red.png";
							
							if($Row_Info_time[cn] > 0 && $Row_Info_time[cn] <= 10){
								$ic_id = 0;
							}			

							if($Row_Info_time[cn] > 10 && $Row_Info_time[cn] <= 20){
								$ic_id = 1;
							}			
							
							if($Row_Info_time[cn] > 20 && $Row_Info_time[cn] <= 30){
								$ic_id = 2;
							}			
							
							if($Row_Info_time[cn] > 30 ){
								$ic_id = 3;
							}						
																										
							$text_script.= "\n{
								title: '".$Row_Info_time[cn]."',
								icon: '".$ic[$ic_id]."',
								textColor: 'white',
								color:'white',
								start: new Date($date_pick[Y], $date_pick[m], $date_pick[d], $date_pick[H], $date_pick[i]),
								allDay: false,
								popup:'".$Row_Info_time[profile_name]."',
								url: 'agenda_profile_date.php?date_start=$Row_Info_time[date_start]',
								url_target: 'show_adenda_list'
								
							},";
							
						}
				echo substr($text_script,0,-1);
				?>
			]
		});
/*$('.fc-other-month .fc-day-number').hide();		
if($('.fc-day35 .fc-day-number').is(':hidden')){
			$('.fc-week5').hide();
		}
if($('.fc-day28 .fc-day-number').is(':hidden')){
	$('.fc-week4').hide();
}		
*/
var d = new Date();
var year_regis = d.getFullYear();
	$('.fc-button-next,.fc-button-prev').click(function() {
		
		/* modify by panupong 
		['comment'] => à¾×èÍ·Ó¡ÒÃ«èÍ¹á¶Ç»¯Ô·Ô¹ºÃÃ·Ñ´ÅèÒ§ÊØ´ ·ÕèáÊ´§à´×Í¹¶Ñ´ä»
		[23/3/2015] */
		/*$('.fc-day-number, .fc-week5, .fc-week4').show();
		$('.fc-other-month .fc-day-number').hide();
		if($('.fc-day28 .fc-day-number').is(':hidden')){
			$('.fc-week4').hide();
		}
		if($('.fc-day35 .fc-day-number').is(':hidden')){
			$('.fc-week5').hide();
		}*/
		
		/* END */
		var moment = $('#calendar').fullCalendar('getDate').toString();
		
		var str_date = moment.split(" ");
		var arr_month = ["Oct","Nov","Dec"];
		var mount_val = ['','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];			
		console.log(str_date[1]);
		var showmount =  mount_val.indexOf(str_date[1]);	
		mount_length = showmount.toString().length;
		if(mount_length<2){
			var strmount =  "0"+showmount;
		}
		
		console.log(str_date[3]+"-"+strmount);		   
		$('#calendar a').not('[href*="'+str_date[3]+"-"+strmount+'"]').not('.fc-header-right a').css('background-color','red').parent().hide();
		if(str_date[1] == "Oct" || str_date[1]=="Nov" || str_date[1]=="Dec" ){
			var year_cur = parseInt(str_date[3])+1;
		}else{
			var year_cur = parseInt(str_date[3]);
		}
		//if(year_cur !== year_regis){
			var th_year = year_cur+543;
			$('#budget_year').html(th_year);
			getBoxNumber(th_year);
			var iframe = document.getElementById('garph_pie');
			iframe.src = "../reportbuilder_cmss_master/report/agenda/filter.php?id=202&year="+th_year+"&graph&width=700&height=300&title_agenda_master="+strmount+"";
			year_regis = year_cur;
		//}
	});
		
});

    function marquee_stop(x){
        x.stop();
    }
    function marquee_start(x){
        x.start();
    }
//	
	function getBoxNumber(budget_year){
		$.get( "ajax_dashbox.php?budget_year="+budget_year, function( data ) {
		  $( "#dashbox" ).html( data );
		});
	}
	
</script>
<style type='text/css'>
body {
	margin-top: 0px;
	text-align: center;

}
  .bg_th{
  background-image:url(images/horiz-bg.png);
  background-repeat:repeat-x;
  background-color:#305086;
  font-weight:bold;
  color:#FFFFFF;
  text-align:center;
  }
#calendar {
	width: <?=$calendar_size?$calendar_size:"440"?>px;
	margin: 0 auto;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<link href="body_style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style4 {font-size:12px; padding:3px; height: 25px; background: #D9D9D9; background: -moz-linear-gradient(top, #D9D9D9, #AFAFAF); background: -webkit-gradient(linear, left top, left bottom, from(#D9D9D9), to(#AFAFAF)); filter: progid:DXImageTransform.Microsoft.Gradient(StartColorStr='#D9D9D9', EndColorStr='#AFAFAF', GradientType=0);font-weight: bold;}
-->
</style>
<!-- <style>
  .bg_th{
  background-image:url(images/horiz-bg.png);
  background-repeat:repeat-x;
  background-color:#305086;
  font-weight:bold;
  color:#FFFFFF;
  text-align:center;
  }
  a {text-decoration:none}

  </style>
-->
  <style type="text/css">
.container .pusher{
	cursor:pointer;
	padding:3px 10px 3px 7px;
	font-weight:900;
	font-size:12px;
	margin:0;
	width: 125px;
}

.container .mover{
	padding:3px 10px 3px 7px;
	margin: 0;
}

.container {
  /*border:1px solid #3399CC;*/
  padding:0px;
  margin-top:0px;
  /*font-size:11px;*/
}
</style> 
<!-- jquery for this page 
<script type="text/javascript" charset="utf-8" src="js/jquery-1.2.3.pack.js"></script>  
-->
<script type="text/javascript">
// initialize the jquery code
 $(document).ready(function(){
//close all the content divs on page load
$('.mover').hide();

// toggle slide
$('#slideToggle').click(function(){
// by calling sibling, we can use same div for all demos
$(this).siblings('.mover').slideToggle();
});

});
</script>

 <meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<!-- 	<script type="text/javascript" src="js/jquery-1.3.2.js"></script>-->
 	<script src="../../common/gs_sortable.js"></script>
	<link href="../../common/gs_sortable.css" type="text/css" rel="stylesheet"/>
    <link href="body_style.css" rel="stylesheet" type="text/css" />
<!-- jquery create tab -->
<link href="jquery_tab/css_sptab.css" rel="stylesheet" type="text/css" />
<!--<script src="jquery_tab/jquery-1.3.2.min" type="text/javascript"></script>-->
<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
	$(".tab_content").hide(); 
	<?php
		$li = $_GET['li'] == "" ? "1" : $_GET['li'];
	?>
	$("#tab<?=$li?>").show(); //Show first tab content
});
</script>

</head>
<body style="margin-top:0">
<?php if(!empty($_GET['li'])){?>
<br />
<div id='title' align="center" class="table_head_text" style="font-size:14px;"><strong>ÃÒÂ§Ò¹¡ÒÃ»ÃÐªØÁ¤³ÐÍ¹Ø¡ÃÃÁ¡ÒÃ¢éÒÃÒª¡ÒÃ¤ÃÙáÅÐºØ¤ÅÒ¡Ã·Ò§¡ÒÃÈÖ¡ÉÒ (Í.¡.¤.È.) <br /><?=$caption?></strong></div>
<br />
<?php
 if($_GET['li'] !=='3'){  /* ?>
    <select name="area" id="area" onchange="location.href='?area='+this.options[this.options.selectedIndex].value+'&li=<?php echo $_GET['li'];?>'">
        <option value="">--- ·Ø¡à¢µ¾×é¹·Õè¡ÒÃÈÖ¡ÉÒ ---</option>
        <?php
            $sql_edu = " SELECT * FROM `eduarea` WHERE status=1 ORDER BY orderby  " ; 
            $result_edu = mysql_db_query($dbmaster,$sql_edu) ; 
            $sltd = "";
            while($rs_edu  = mysql_fetch_assoc($result_edu)){
                $sltd = "";
                if($rs_edu[secid] == trim($_GET[area])) {
                    $sltd = "selected";
                }
                echo "<option $sltd value='".$rs_edu[secid]."'>".$rs_edu[secname_short]."</option>";
            }
            
            //$areaname = str_replace("ÊÓ¹Ñ¡§Ò¹à¢µ¾×é¹·Õè¡ÒÃÈÖ¡ÉÒ","Ê¾·.",$rs_edu[secname]);
        ?>
    </select>
<?php */ }
 } ?>     
<table align="center" width="99%">
<tr><td align="left"> 
<?php 
	$arr[0] = array('#677fa4','#556b8d','#42536d');
	$arr[1] = array('#99d063','#85c744','#6ca632');
	$arr[2] = array('#f2b257','#efa131','#dc8911');
	$arr[3] = array('#e85647','#e74c3c','#d62c1a');
?>       
</td></tr>
<tr><td align="center">
<div class="tab_container" style="border:none !important;">
    <div id="tab1" class="tab_content">
<?php if(empty($_GET['li'])){?>
<center>
<div id="dashbox" style="width:1100px; margin-top: -30px;">

</div>
</center>
<br />
<? } ?>
<div>
		<div id='calendar'  style="width:360px; float:left; margin-top:30px; margin-left:40px;" ></div>
        <div style="width:700px; float:left; margin-left:40px; margin-top:40px; height:500px">
        	<div id="buttonChangeMode" style="border:1px solid #ccc; border-radius:3px; width:100px; height:34px; position:absolute; right:85px; background-color:#f7f7f8;">
            	<div id="graph_mode" style="float:left; padding:5px; cursor:pointer;">¡ÃÒ¿</div>
            	<div id="list_mode" style="float:left; border-left:1px solid #ccc; padding:5px; cursor:pointer;">ÃÒÂ¡ÒÃ</div>
            </div>
        	<iframe id="garph_pie"  src="../reportbuilder_cmss_master/report/agenda/filter.php?id=202&year=<?php echo date('Y')+543;?>&graph&width=700&height=300" width="100%" height="350" style="border:none; overflow:hidden;"></iframe>
        	<iframe id="list_meeting" name="show_adenda_list" src="agenda_profile_date.php" width="100%" frameborder="0" height="300" style="margin-top:30px;"></iframe>
        </div>		
</div>		
<br />
		<div align="center" style="font-size:12px;  position:absolute; top:630px; ">
		<?php
			echo ("<img src='images/promo_green.png' align='absmiddle' title='¨Ó¹Ç¹¡ÒÃ»ÃÐªØÁ 1-10 ¤ÃÑé§' />&nbsp;¨Ó¹Ç¹ ¡ÒÃ»ÃÐªØÁ 1-10 ¤ÃÑé§&nbsp;&nbsp;");
			echo ("<img src='images/promo_green_light.png' align='absmiddle' title='¨Ó¹Ç¹¡ÒÃ»ÃÐªØÁ 11-20 ¤ÃÑé§' />&nbsp;¨Ó¹Ç¹ ¡ÒÃ»ÃÐªØÁ 11-20 ¤ÃÑé§&nbsp;&nbsp;");
			echo ("<img src='images/promo_orange.png' align='absmiddle' title='¨Ó¹Ç¹¡ÒÃ»ÃÐªØÁ  21-30 ¤ÃÑé§' />&nbsp;¨Ó¹Ç¹ ¡ÒÃ»ÃÐªØÁ  21-30 ¤ÃÑé§&nbsp;&nbsp;");
			echo ("<img src='images/promo_red.png' align='absmiddle' title='¨Ó¹Ç¹ ¡ÒÃ»ÃÐªØÁ ÁÒ¡¡ÇèÒ 30 ¤ÃÑé§' />&nbsp;¨Ó¹Ç¹ ¡ÒÃ»ÃÐªØÁ  ÁÒ¡¡ÇèÒ 30 ¤ÃÑé§&nbsp;&nbsp;");		
		?>
       </div>
		<!--<div style="height:37px"><strong>ËÁÒÂàËµØ : </strong>äÍ¤Í¹&nbsp;<img src='images/icon-meeting.png' align="absmiddle" width="35px"/>&nbsp;·Õè»ÃÒ¡¯ã¹»¯Ô·Ô¹ ÊÒÁÒÃ¶¤ÅÔ¡à¾×èÍáÊ´§ÃÒÂÅÐàÍÕÂ´·ÕèµÒÃÒ§´éÒ¹ÅèÒ§</div>-->
		        
	</div>
    <div id="tab2" class="tab_content">
<table align="center" width="99%" border="0" cellpadding="3" cellspacing="1">
			  <tr>
				<td width="58%" rowspan="2" valign="bottom" align="left">
				<?php
				$sql = "/**/SELECT * FROM  agenda_profile_main 
							WHERE 1=1
							".$where."
							".$where_site."
							ORDER BY date_start DESC";
					//$strList = $get;
//					echo "<pre>";
//					echo $sql;
					$rsConn = mysql_db_query($cmss_master,$sql);
					$all_row = mysql_num_rows($rsConn);
					$board_row_per_page = 10;
					$total_page = ceil($all_row/$board_row_per_page);
					$page = ($_GET['page2'])?$_GET['page2']:1;
					$page = ($page > $total_page)?$total_page:$page;
					$page = ($page <= 0)?1:$page;
					$limit_start = ($page==1)?0:(($page*$board_row_per_page)-$board_row_per_page);
					$limit_end = $board_row_per_page;
					if($_GET['View']==''){
						$sql .= " LIMIT ".$limit_start.", ".$limit_end;
						$num = $limit_start ;
					}
					
					if(isset($cols)&&isset($sort)){
						$solink="&cols=$cols&sort=$sort";
					}
					
					$strList = "&li=2&area=".$_GET[area];
					
					$text_search =  "";
					$prev_page = $page - 1; $prev_page = ($prev_page <= 1)?1:$prev_page;
					$prev = "self.location='".$PHP_SELF."?search=$search$strList&page2=$prev_page$link$solink'";
					$next_page = $page + 1; $next_page = ($next_page >= $total_page)?$total_page:$next_page;
					$next = "self.location='".$PHP_SELF."?search=$search$strList&page2=$next_page$link$solink'";
					
					$prev_Tenpage=$page-$board_row_per_page;$prev_Tenpage=($prev_Tenpage<= 1)?1:$prev_Tenpage;
					$prevTen = "self.location='".$PHP_SELF."?search=$search$strList&page2=$prev_Tenpage$link$solink'";
					$nextTenPage=$page+$board_row_per_page;$next_TenPage=($next_TenPage >= $total_page)?$total_page:$next_TenPage;
					$nextTen = "self.location='".$PHP_SELF."?search=$search$strList&page2=$nextTenPage$link$solink'";
				?>
				<? if($View==""){$Klink="?View=All$strList"; $CSh="¢éÍÁÙÅ·Ñé§ËÁ´"; $Klink2="?View=$strList";}else{$Klink="?View=$strList";$CSh="¢éÍÁÙÅ ".$board_row_per_page." ÃÒÂ¡ÒÃÅèÒÊØ´";$Klink2="?&View=All$strList";}?>
				·Ñé§ËÁ´ <b> <font color="#CC0000">
				<?=number_format($all_row);?>
				</font> </b> ÃÒÂ¡ÒÃ
				<? if($View==""){?>
				áºè§à»ç¹ <b><font color="#0033CC">
				<?=number_format($total_page);?>
				</font></b> Ë¹éÒ
				<? }?>
				&nbsp;&nbsp;<a href="<?=$Klink?>"><img src="../../images/Search-Add.gif" alt="<?=$CSh?>" width="16" height="16" border="0" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
					  <? if($View==''){?>
						<?  if($page==1){?>
						<img src="../../images/page_div_icon/xFirst.gif" />
						<? }else{?>
						<img src="../../images/page_div_icon/First.gif" align="absmiddle" border="0" width="10" height="12"  onclick="<?=$prevTen;?>"  style="cursor:pointer;">
						<? }?>
				&nbsp;
				<?  if($page==1){?>
				<img src="../../images/page_div_icon/xPrevious.gif" />
				<? }else{?>
				<img src="../../images/page_div_icon/Previous.gif" align="absmiddle" border="0" width="7" height="12"  onclick="<?=$prev;?>"   style="cursor:pointer;"/>
				<? }?>
				<?php
				$board_link_num = $board_row_per_page;
				$ii = 1;
				if ( $board_link_num > $total_page ){
					$loop_page = $total_page;
				
				} else {
					$bx = ceil($board_link_num / 2);
					$pp = $page - $bx;
					$pn = $page + $bx;
					$loop_page = $pn;
					$ii = $pp;
					if ( $total_page <= $loop_page ) {
						$loop_page = $total_page;
						$ii = $loop_page - ($board_link_num -1);
					}
					if ( $ii < 1 ) {
						$ii = 1;
						$loop_page = $ii + ($board_link_num -1);
					}
				}
				
				for($i=$ii;$i<=$loop_page;$i++){
					if ( $i==$page || !$page ) {
						$txt = "<b>$i</b>";
					} else {
						$txt = $i;
				
					}
				?>
				<a href="<?=$PHP_SELF;?>?Sort=<?=$Sort;?>&search=<?=$search.$strList?>&page2=<?=$i;?>"><?=$txt;?>
				</a>
				<?
				} # for
				?>
				<? if($page==$loop_page || $loop_page==0){?>
				<img src="../../images/page_div_icon/xNext.gif" />
				<? }else{?>
				<img src="../../images/page_div_icon/Next.gif" align="absmiddle" border="0" width="7" height="12" onClick="<?=$next;?>"   style="cursor:pointer;"/>
				<? }?>
				&nbsp;
				<? if($page==$loop_page || $loop_page==0){?>
				<img src="../../images/page_div_icon/xLast.gif" />
			
				<? }else{?>
				<img src="../../images/page_div_icon/Last.gif" align="absmiddle" border="0" width="10" height="12" onClick="<?=$nextTen;?>"   style="cursor:pointer;"/>
				<? 
					} 
				}#End View=
				?>	</td>
				<td width="26%" align="right" valign="top"><span id="msg_search_result">&nbsp;</td>
				<?php
				if(trim($search_result) != ""){
					$style = "style=\"border:dotted 1px #CCCCCC\"";
				}
				?>
				<td width="16%" align="right" <?=style?>>&nbsp;</td>
			  </tr>
			</table>
			
			 <table id="my_table" width="99%" border="0" align="center" bgcolor="#CCCCCC" cellpadding="5" cellspacing="1">
			 <thead>
			  <tr  align="center">
				<th width="45"  class="head_bar_small">ÅÓ´Ñº·Õè</th>
				<th width="180"  class="head_bar_small">Ë¹èÇÂ§Ò¹</th>
				<th  class="head_bar_small">â»Ãä¿Åì¡ÒÃ»ÃÐªØÁ</th>
			    <th width="90"  class="head_bar_small">ÇÑ¹·Õè»ÃÐªØÁ</th>
			    <th width="65"  class="head_bar_small">àÇÅÒ»ÃÐªØÁ</th>
			  </tr>
			  </thead>
			  <?php
				
				$res = mysql_db_query($cmss_master,$sql);
				//$int_num = ($_GET['page']>1)?$_GET['page']*$board_row_per_page :0;
				$int_num=0;
					
				while($row = mysql_fetch_assoc($res)){
							$int_num++;
							$rowNumb = ($int_num+($board_link_num*$page)-$board_link_num);				
			
			  ?>
			  <tr bgcolor="#FFFFFF"  onmouseover="this.style.backgroundColor='#dbf2ae'" onmouseout="this.style.backgroundColor='#FFFFFF'">
				<td <?=$ALIGNMENT['ORDER']?>><?=$rowNumb?></td>
				<td <?=$ALIGNMENT['TEXT']?>><?=getSecName($row[siteid])?></td>
				<td <?=$ALIGNMENT['TEXT']?>>
				<?php if ($row[meeting_id] != "") { ?>
				<a href='meeting_paper.php?meeting_id=<?=$row[meeting_id]?>&siteid=<?=$row['siteid']?>' target='_blank'><?=$row[profile_name]." ¤ÃÑé§·Õè ".$row[profile_no]."/".$row[profile_year];?></a>
				<?php 	} else { 
				echo $row[profile_name]." ¤ÃÑé§·Õè ".$row[profile_no]."/".$row[profile_year];
						}	
				?>	</td>
			    <td <?=$ALIGNMENT['DATE']?>><?=dateFormat($row[date_start],'thaidot')?></td>
			    <td <?=$ALIGNMENT['DATE']?>><?=substr($row[time_start],0,5)?></td>
			  </tr>
			  <?php 
			  }
			  ?>
			</table>
    </div>
    
    <div id="tab3" class="tab_content">
		<!--<div align="right"><img src="images/filter-icon.png" title="µÑÇ¡ÃÍ§"></div>-->
<table id="my_table" width="99%" border="0" align="center" bgcolor="#CCCCCC" cellpadding="5" cellspacing="1">
	<thead>
    <tr class="head_bar_small">
    	<td align="center" rowspan="2">ÅÓ´Ñº</td>
        <td align="center" rowspan="2">ÊÓ¹Ñ¡§Ò¹à¢µ¾×é¹·Õè¡ÒÃÈÖ¡ÉÒ</td>
        <td align="center" colspan="4">¨Ó¹Ç¹ÃÒÂ§Ò¹¡ÒÃ»ÃÐªØÁ</td>
    </tr>
    <tr class="head_bar_small"  align="center">
		<td>¨Ó¹Ç¹·Ñé§ËÁ´</td>
		<td>ÃÑº·ÃÒº</td>
		<td>äÁèÃÑº·ÃÒº</td>
		<td>ÍÂÙèÃÐËÇèÒ§´Óà¹Ô¹¡ÒÃ</td>
    </tr>
    <?php
	$sql_area = "SELECT
					eduarea.secid,
					eduarea.secname_short
				FROM
					eduarea
					where eduarea.secid not like '99%'   ORDER BY substr(eduarea.secname_short,3,1) DESC ,eduarea.secname_short
					";
	$query_area = 	mysql_db_query($cmss_master,$sql_area);
	while($row_area = mysql_fetch_assoc($query_area)){		
		$count_no+=1;	
		
		$sql_count_profileId="SELECT
			sum(if(confirm = 'Y' and approve = 'Y',1,0))as val_cen2,
			sum(if(confirm = 'Y' and approve != 'Y',1,0)) as val_cen3,
			sum(if(confirm != 'Y',1,0)) as val_cen4,
			sum(if(confirm != 'Y',1,1)) as total_val_cen
		FROM 
			agenda_profile_main 
		WHERE 
			siteid = '".$row_area['secid']."' ";
		
		$query_count_profileId = 	mysql_db_query($cmss_master,$sql_count_profileId);
		$row_count_profileId = 	mysql_fetch_assoc($query_count_profileId);
		$val_cen2=$row_count_profileId[val_cen2];
		$val_cen3=$row_count_profileId[val_cen3];
		$val_cen4=$row_count_profileId[val_cen4];
		$total_val_cen=$row_count_profileId[total_val_cen];
		if(!$val_cen2){
			$val_cen2=0;
		}if(!$val_cen3){
			$val_cen3=0;
		}if(!$val_cen4){
			$val_cen4=0;
		}if(!$total_val_cen){
			$total_val_cen=0;
		}
	 ?>
     <tr style="background-color:#ffffff">
     	<td align="center"><?php echo $count_no; ?></td>
        <td>
			<?php //echo $row_area['secname_short']; ?>
			<?php
				if($total_val_cen > 0){ 
					echo "<a href='agenda_master.php?li=5&area=".$row_area['secid']." '>";
				}
				
				echo $row_area['secname_short'];
				
				if($row_count_profileId>0){ echo "</a>"; }
			?>
		</td>
        <td align="center">
		<?php 
			echo $total_val_cen;
		/*
		$sql_count_profileId =  "SELECT count(agenda_profile_main.profile_id) as count_number FROM  agenda_profile_main 
							WHERE confirm='Y'
							and siteid = '".$row_area['secid']."'
							ORDER BY date_start DESC";
		$query_count_profileId = 	mysql_db_query($cmss_master,$sql_count_profileId);
		$row_count_profileId = 	mysql_fetch_assoc($query_count_profileId);
		if($row_count_profileId['count_number'] > 0){ echo "<a href='agenda_master.php?li=5&area=".$row_area['secid']." '>";}
		?><?php echo $row_count_profileId['count_number'];
		if($row_count_profileId>0){ echo "</a>"; }
		*/
		?> 
		 </td>
		 <td align="center"><?php echo $val_cen2;?></td>
		 <td align="center"><?php echo $val_cen3;?></td>
		 <td align="center"><?php echo $val_cen4;?></td>
     </tr>
     <?php } ?>
    </thead>
</table>
    </div>
    	
    <div id="tab5" class="tab_content">
    	<a href="agenda_master.php?li=3" style="position: absolute;left: 36px;top: 96px;"><img src="images/back-st.png"  /></a>
			<?php
			#Begin Search
				$dbsite = "cmss_".$_GET[area];
				$where = "";
				$search_result="";
					if($_GET['search_key'] != "" && $_GET[CL_title]){
						$search_key = /*iconv('utf-8','tis620', $_GET['search_key']);*/ $_GET['search_key'];
						
						$strSet = str_replace(' ','@',rtrim(ltrim($search_key)));
						$arrSet = explode('@',$strSet);
						if( count($arrSet) > 1 ){
							$search_prename_attach = isset($arrSet[0])?'%'.$arrSet[0].'%':'';
							$search_name_attach = isset($arrSet[1])?'%'.$arrSet[1].'%':'';
							$search_surname_attach = isset($arrSet[2])?'%'.$arrSet[2].'%':'';
						}else{
							$search_prename_attach = $search_name_attach = $search_surname_attach =isset($arrSet[0])?'%'.$arrSet[0].'%':'';
						}
						$where .= " AND (profile_no LIKE('%$search_key%') 
										OR profile_year LIKE('%$search_key%') 
										OR profile_name LIKE('%$search_key%') 
										OR (
												agenda_command_letter_attach.prename LIKE '".$search_prename_attach."'
												OR
												agenda_command_letter_attach.firstname LIKE '".$search_name_attach."'
												OR
												agenda_command_letter_attach.surname LIKE '".$search_surname_attach."'
											))
										
						";
						$get .= "&search_key=$search_key";
						$search_result .= "¤Ó¤é¹ËÒ: ".$search_key."<br>";
					}
					
					if($_GET[profile_no] != "" && $_GET[CL_no]){
						$where .= " AND profile_no = '$profile_no'";
						$get .= "&profile_no=$profile_no";
						$search_result .= "¤ÃÑé§·Õè: ".$profile_no."<br>";
					}
					
					if($_GET[profile_year] != "" && $_GET[CL_yy]){
						$where .= " AND profile_year = '$profile_year'";
						$get .= "&profile_year=$profile_year";
						$search_result .= "»Õ ¾.È.: ".$profile_year."<br>";
					}
					
					if($_GET[place_id] != "" && $_GET[CL_location]){
						$where .= " AND place_id = '$place_id' ";
						$get .= "&place_id=$place_id";
						$sql="SELECT place_name FROM agenda_place WHERE place_id='$place_id' ";
							$rs = mysql_db_query('cmss_master',$sql);
								$row = mysql_fetch_assoc($rs);
								$place_name = $row[place_name];
						$search_result .= "Ê¶Ò¹·Õè: ".$place_name."<br>";
					}
					
					if($_GET[date_start] != "" && $_GET[CL_date]){
						
						$arr_d = explode("/",$_GET[date_start]);
						$d_s = ($arr_d[2]-543)."-".$arr_d[1]."-".$arr_d[0];
						$where .= " AND date_start >= '$d_s'";
						$get .= "&date_start=$date_start";
						$search_result .= "ÇÑ¹·Õè»ÃÐªØÁ: ".$date_start."<br>";	
					}
					if($_GET[date_end] != "" && $_GET[CL_date]){
						$arr_d = explode("/",$_GET[date_end]);
						$d_e = ($arr_d[2]-543)."-".$arr_d[1]."-".$arr_d[0];
						$where .= " AND date_start <= '$d_e'";
						$get .= "&date_end=$date_end";
						$search_result .= "¶Ö§ÇÑ¹·Õè»ÃÐªØÁ: ".$date_end."<br>";
					}if($_GET[date_end] == "" && $_GET[CL_date]){
						
						$arr_d = explode("/",$_GET[date_start]);
						$d_s = ($arr_d[2]-543)."-".$arr_d[1]."-".$arr_d[0];
						$where .= " AND date_start <= '".$d_s."' ";	
					}
					
					
					
					//»ÃÐàÀ·¡ÒÃºÃÔËÒÃ§Ò¹ºØ¤¤Å
					if($_GET[CL_type_sel] != "" && $_GET[CL_type]){
						$sql_human_manage ="SELECT
												$dbsite.agenda_meeting_subject.meeting_id
											FROM
												$dbsite.agenda_meeting_subject
											INNER JOIN $dbsite.agenda_command ON $dbsite.agenda_meeting_subject.letter_id = $dbsite.agenda_command.letter_id
											INNER JOIN command_verification.command_category ON $dbsite.agenda_command.template_id = command_verification.command_category.cat_id
											INNER JOIN command_verification.command_category_match_type ON command_verification.command_category.cat_id = command_verification.command_category_match_type.id
											INNER JOIN command_verification.command_category_merge ON command_verification.command_category_match_type.gid = command_verification.command_category_merge.id 
											WHERE command_verification.command_category_merge.parent_id = '".$_GET[CL_type_sel]."'";
											//echo $sql_human_manage;
						$query_human_manage = mysql_db_query($dbsite,$sql_human_manage) or die(mysql_error());
						while($row_human_manage = mysql_fetch_assoc($query_human_manage)){
							$arr_h_in .= $row_human_manage['meeting_id'].",";
						}
						$arr_h_in = rtrim($arr_h_in, ",");
						$where .= " AND meeting_id in ($arr_h_in)";
						$get .= "&CL_type_sel=$CL_type_sel";
						$sql="SELECT cat_name FROM command_category_merge  WHERE id='$CL_type_sel' ";
							$rs = mysql_db_query('command_verification',$sql);
								$row = mysql_fetch_assoc($rs);
								$CL_type_sel = $row[cat_name];
						$search_result .= "»ÃÐàÀ·¡ÒÃºÃÔËÒÃ§Ò¹ºØ¤¤Å : ".$CL_type_sel."<br>";

						
					}
					
					// ÇÒÃÐ¡ÒÃ»ÃÐªØÁ
					if($_GET[CL_agenda_sel] != "" && $_GET[CL_agenda]){
						$sql_agenda_type = "SELECT meeting_id FROM `agenda_meeting_subject` WHERE subject_ini_id = '".$_GET[CL_agenda_sel]."' GROUP BY meeting_id;";
						//echo $sql_agenda_type."<hr>";die;
						$query_agenda_type = mysql_db_query($dbsite,$sql_agenda_type) or die(mysql_error());
						while($row_agenda_type = mysql_fetch_assoc($query_agenda_type)){
							$arr_in .= $row_agenda_type['meeting_id'].",";
						}
						$arr_in = rtrim($arr_in, ",");
						$where .= " AND meeting_id in ($arr_in)";
						$get .= "&CL_agenda_sel=$CL_agenda_sel";
						$sql="SELECT initial_subject FROM agenda_subject_initial WHERE initial_id='$CL_agenda_sel' ";
							$rs = mysql_db_query('cmss_master',$sql);
								$row = mysql_fetch_assoc($rs);
								$CL_agenda_sel = $row[initial_subject];
						$search_result .= "ÇÒÃÐ¡ÒÃ»ÃÐªØÁ : ".$CL_agenda_sel."<br>";
					}
					
					
						
					$search = $get;
					//echo $get."<hr>";
					//li=5&area=5001
				#End Search Data
				?>
			<table width="98%" border="0">
			  <tr>
				<td align="right" colspan="2">
				<!--<span id="msg_search_result"><strong style="font-size:14px"><a href='<?=$PHP_SELF?>?li=3&area=<?=$_GET[area]?>'>áÊ´§·Ñé§ËÁ´</a></strong>&nbsp;&nbsp;</span>  <label onclick="window.open('search_master.php?li=3&area=<?php echo $_GET['area']; ?>');" onmouseover="this.style.cursor='pointer'" style="font-size:14px; color:#4A79BD"><img src="images/search_64.png" width="18" align="absmiddle"  title="¤é¹ËÒÃÒÂ§Ò¹¡ÒÃ»ÃÐªØÁ" /><strong>¤é¹ËÒÃÒÂ§Ò¹¡ÒÃ»ÃÐªØÁ</strong></label> 				
				-->
					<!--<div class="pusher" id="slideToggle"><span onmouseover="this.style.backgroundColor='#dbf2ae' " onmouseout="this.style.backgroundColor='#FFFFFF'"><strong>¤é¹ËÒÃÒÂ§Ò¹¡ÒÃ»ÃÐªØÁ<img src="images/search_64.png" width="16" align="absmiddle" title="¤é¹ËÒÃÒÂ§Ò¹¡ÒÃ»ÃÐªØÁ" /></strong></span></div>
				-->
				<div class="container"  style="width:100%">
				 <div class="pusher" id="slideToggle"><span onmouseover="this.style.backgroundColor='#dbf2ae' " onmouseout="this.style.backgroundColor='#FFFFFF'"><strong>¤é¹ËÒÃÒÂ§Ò¹¡ÒÃ»ÃÐªØÁ<img src="images/search_64.png" width="16" align="absmiddle" title="¤é¹ËÒÃÒÂ§Ò¹¡ÒÃ»ÃÐªØÁ" /></strong></span></div>
					<div style="display: none;" class="mover">
					  <?php
						include "search_master3.php";
						//include "search2.php";
					  ?>
					</div>
				</div>
				</td>
              </tr>
			
			</table>
			<?php
			if($search_result!=""){
			?>
			<table width="100%" border="0" style="background-color:#EFF2F5; border:#D2E6F0 1px solid;">
			  <tr>
				<td><strong>à§×èÍ¹ä¢¡ÒÃ¤é¹ËÒ</strong></td>
			  </tr>
			  <tr>
				<td>
				<DIV style="margin-left:20px;">
				<?php echo $search_result;?>				</DIV>				</td>
			  </tr>
			</table>
			<br/>
			<?php } ?>
<table align="center" width="98%" border="0" cellpadding="3" cellspacing="1">
			  <tr>
				<td width="58%" rowspan="2" valign="bottom" align="left">
				<?php
				
				$sql = "/**/SELECT * FROM  agenda_profile_main 
							LEFT JOIN ".STR_PREFIX_DB.$_GET[area].".agenda_meeting_subject AS agenda_meeting_subject ON agenda_profile_main.meeting_id = agenda_meeting_subject.meeting_id
							LEFT JOIN ".STR_PREFIX_DB.$_GET[area].".agenda_command_letter_attach AS agenda_command_letter_attach ON agenda_meeting_subject.letter_id = agenda_command_letter_attach.letter_id
							WHERE confirm='Y'	
							".$where."
							".$where_site."
							GROUP BY agenda_profile_main.profile_id
							ORDER BY date_start DESC";
					//$strList = $get;
					if($_GET['debug'] == 'on'){
						echo $sql;
					}
					//echo "<br><br>".$sql;
						
					$rsConn = mysql_db_query($cmss_master,$sql);
					$all_row = mysql_num_rows($rsConn);
					$board_row_per_page = 10;
					$total_page = ceil($all_row/$board_row_per_page);
					$page = ($_GET['page'])?$_GET['page']:1;
					$page = ($page > $total_page)?$total_page:$page;
					$page = ($page <= 0)?1:$page;
					$limit_start = ($page==1)?0:(($page*$board_row_per_page)-$board_row_per_page);
					$limit_end = $board_row_per_page;
					if($View==''){
						$sql .= " LIMIT ".$limit_start.", ".$limit_end;
						$num = $limit_start ;
					}
					
					if(isset($cols)&&isset($sort)){
						$solink="&cols=$cols&sort=$sort";
					}
					
					$strList = "&li=5&area=".$_GET[area];
					$getData="&CL_title=$CL_title&search_key=$search_key&CL_no=$CL_no&profile_no=$profile_no&CL_yy=$CL_yy&profile_year=$profile_year&CL_location=$CL_location&place_id=$place_id&CL_type=$CL_type&CL_type_sel=$CL_type_sel&CL_agenda=$CL_agenda&CL_agenda_sel=$CL_agenda_sel&CL_date=$CL_date&date_start=$date_start&date_end=$date_end&b_search=$b_search";
					$text_search =  "";
					$prev_page = $page - 1; $prev_page = ($prev_page <= 1)?1:$prev_page;
					$prev = "self.location='".$PHP_SELF."?search=$search$strList&page=$prev_page$link$solink$getData'";
					$next_page = $page + 1; $next_page = ($next_page >= $total_page)?$total_page:$next_page;
					$next = "self.location='".$PHP_SELF."?search=$search$strList&page=$next_page$link$solink$getData'";
					
					$prev_Tenpage=$page-$board_row_per_page;$prev_Tenpage=($prev_Tenpage<= 1)?1:$prev_Tenpage;
					$prevTen = "self.location='".$PHP_SELF."?search=$search$strList&page=$prev_Tenpage$link$solink$getData'";
					$nextTenPage=$page+$board_row_per_page;$next_TenPage=($next_TenPage >= $total_page)?$total_page:$next_TenPage;
					$nextTen = "self.location='".$PHP_SELF."?search=$search$strList&page=$nextTenPage$link$solink$getData'";
				?>
				<? if($View==""){$Klink="?View=All$strList"; $CSh="¢éÍÁÙÅ·Ñé§ËÁ´"; $Klink2="?View=$strList";}else{$Klink="?View=$strList";$CSh="¢éÍÁÙÅ ".$board_row_per_page." ÃÒÂ¡ÒÃÅèÒÊØ´";$Klink2="?&View=All$strList";}?>
				·Ñé§ËÁ´ <b> <font color="#CC0000">
				<?=number_format($all_row);?>
				</font> </b> ÃÒÂ¡ÒÃ
				<? if($View==""){?>
				áºè§à»ç¹ <b><font color="#0033CC">
				<?=number_format($total_page);?>
				</font></b> Ë¹éÒ
				<? }?>
				&nbsp;&nbsp;<a href="<?=$Klink?>"><img src="../../images/Search-Add.gif" alt="<?=$CSh?>" width="16" height="16" border="0" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
					  <? if($View==''){?>
						<?  if($page==1){?>
						<img src="../../images/page_div_icon/xFirst.gif" />
						<? }else{?>
						<img src="../../images/page_div_icon/First.gif" align="absmiddle" border="0" width="10" height="12"  onclick="<?=$prevTen;?>"  style="cursor:pointer;">
						<? }?>
				&nbsp;
				<?  if($page==1){?>
				<img src="../../images/page_div_icon/xPrevious.gif" />
				<? }else{?>
				<img src="../../images/page_div_icon/Previous.gif" align="absmiddle" border="0" width="7" height="12"  onclick="<?=$prev;?>"   style="cursor:pointer;"/>
				<? }?>
				<?php
				$board_link_num = $board_row_per_page;
				$ii = 1;
				if ( $board_link_num > $total_page ){
					$loop_page = $total_page;
				
				} else {
					$bx = ceil($board_link_num / 2);
					$pp = $page - $bx;
					$pn = $page + $bx;
					$loop_page = $pn;
					$ii = $pp;
					if ( $total_page <= $loop_page ) {
						$loop_page = $total_page;
						$ii = $loop_page - ($board_link_num -1);
					}
					if ( $ii < 1 ) {
						$ii = 1;
						$loop_page = $ii + ($board_link_num -1);
					}
				}
				
				for($i=$ii;$i<=$loop_page;$i++){
					if ( $i==$page || !$page ) {
						$txt = "<b>$i</b>";
					} else {
						$txt = $i;
				
					}
				?>
				<a href="<?=$PHP_SELF;?>?Sort=<?=$Sort;?>&search=<?=$search.$strList?>&page=<?=$i.$getData;?>"><?=$txt;?>
				</a>
				<?
				} # for
				//echo $page."==".$loop_page."<hr>";
				?>
				
				<? if($page==$loop_page || $loop_page==0){?>
				<img src="../../images/page_div_icon/xNext.gif" />
				<? }else{?>
				<img src="../../images/page_div_icon/Next.gif" align="absmiddle" border="0" width="7" height="12" onClick="<?=$next;?>"   style="cursor:pointer;"/>
				<? }?>
				&nbsp;
				<? if($page==$loop_page || $loop_page==0){?>
				<img src="../../images/page_div_icon/xLast.gif" />
			
				<? }else{?>
				<img src="../../images/page_div_icon/Last.gif" align="absmiddle" border="0" width="10" height="12" onClick="<?=$nextTen;?>"   style="cursor:pointer;"/>
				<? 
					} 
				}#End View=
				?>	</td>
				<td width="26%" align="right" valign="top"><span id="msg_search_result">&nbsp;</td>
				<td width="16%" align="right" <?=style?>>&nbsp;</td>
			  </tr>
		  </table>
			
			 <table id="my_table" width="99%" border="0" align="center" bgcolor="#CCCCCC" cellpadding="5" cellspacing="1">
			 <thead>
			  <tr  align="center">
				<th width="45"  class="head_bar_small">ÅÓ´Ñº·Õè</th>
				<th  class="head_bar_small">â»Ãä¿Åì¡ÒÃ»ÃÐªØÁ</th>
				<th width="90"  class="head_bar_small">ÇÑ¹·Õè»ÃÐªØÁ</th>
				<th width="65"  class="head_bar_small">àÇÅÒ»ÃÐªØÁ</th>
				<th width="85"  class="head_bar_small">ÃÑº·ÃÒºÃÒÂ§Ò¹<br/>
			    ¡ÒÃ»ÃÐªØÁ</th>
				<th width="55"  class="head_bar_small">¡ÒÃ¨Ñ´¡ÒÃ</th>
			  </tr>
			  </thead>
			  <?php
				### áÊ´§ÃÒÂ§Ò¹µÒÁ ÊÔ·¸Ôì
			//	$where_staffgroup="";
			//	if($role_executive || $role_user)
			//	{
			//		$where_staffgroup = " AND da_rotation.staff_group='".$_SESSION[session_group]."'";
			//	}
			//	###	  
			//	$sql_da_list .= $where_staffgroup;
			//echo $sql;
				$res = mysql_db_query($cmss_master,$sql);
				//$int_num = ($_GET['page']>1)?$_GET['page']*$board_row_per_page :0;
				
					$int_num=0;
				while($row = mysql_fetch_assoc($res)){
							//$int_num++;
							$int_num++;
							$rowNumb = ($int_num+($board_link_num*$page)-$board_link_num);				
							
			  ?>
			  <tr bgcolor="#FFFFFF"  onmouseover="this.style.backgroundColor='#dbf2ae'" onmouseout="this.style.backgroundColor='#FFFFFF'">
				<td <?=$ALIGNMENT['ORDER']?>><?=$int_num?></td>
				<td <?=$ALIGNMENT['TEXT']?>><?php if ($row[meeting_id] != "") { ?>
                    <a href='meeting_report_paper.php?meeting_id=<?=$row[meeting_id]?>&amp;siteid=<?=$row['siteid']?>' target='_blank'>
                      <?=$row[profile_name]." ¤ÃÑé§·Õè ".$row[profile_no]."/".$row[profile_year];?>
                  </a>
                    <?php 	} else { 
				echo $row[profile_name]." ¤ÃÑé§·Õè ".$row[profile_no]."/".$row[profile_year];
						}	
				?>
                </td>
				<td <?=$ALIGNMENT['DATE']?>><?=dateFormat($row[date_start],'thaidot')?></td>
				<td <?=$ALIGNMENT['DATE']?>><?=$row[time_start] != "" ? substr($row[time_start],0,5)." ¹." : "-" ?></td>
				<td <?=$ALIGNMENT['DATE']?>><?=$row[approve]=="Y" ? "<img src='images/tick_16.png' title='ÃÒÂÅÐàÍÕÂ´ : ".$row[approve_detail]."' align='absmiddle'/>" : "-" ?></td>
				<?
					### µÃÇ¨ÊÍº¤ÃØÀÑ³±ì ·Õèã¡Åé áÅÐ ¤Ãº¡ÓË¹´¤×¹ ÊèÇ¹¡ÅÒ§
					if($row_da_list['rotation_status']!='o'){
						if($Result_Return['return_date']!=''){
							$strImage='';			
							if(chkDateReturn($Result_Return['return_date'],date('Y-m-d'))=='1' && $Result_Return[status]=='b'){
									$strImage='<img src="img/icon-warning.png"  align="absmiddle" width="16" height="16" border="0" title="ã¡Åé¤Ãº¡ÓË¹´¤×¹"/>';
							}else if(chkDateReturn($Result_Return['return_date'],date('Y-m-d'))=='2' && $Result_Return[status]=='b'){
									$strImage='<img src="img/WarningLogoRed.png" align="absmiddle" width="16" height="16" border="0" title="à¡Ô¹¡ÓË¹´¤×¹"/>';
							}
						}
					}
				?>
				<td align="center">
				<!--<img src="images/16x16/next.png" align="absmiddle" onmouseover="this.style.cursor='pointer'" title="á¨é§ÃÑº·ÃÒºÃÒÂ§Ò¹" onclick="window.open('meeting_report_executive2.php?meeting_id=<?=$row[meeting_id]?>&siteid=<?=$row['siteid']?>')"></img>-->
				<img src="images/16x16/next.png" align="absmiddle" onmouseover="this.style.cursor='pointer'" title="á¨é§ÃÑº·ÃÒºÃÒÂ§Ò¹" onclick="window.location='meeting_report_executive2.php?meeting_id=<?=$row[meeting_id]?>&siteid=<?=$row['siteid']?>'"></img></td>
			  </tr>
			  <?php 
			  }
			  ?>
			</table>
			
			<table width="99%" align="center" border="0" cellspacing="1" cellpadding="3">
			  <tr>
				<td align="left"><img src="images/tick_16.png" title="ÃÑº·ÃÒºÃÒÂ§Ò¹¡ÒÃ»ÃÐªØÁ" align="absmiddle" />&nbsp;: &nbsp;ÃÑº·ÃÒºÃÒÂ§Ò¹¡ÒÃ»ÃÐªØÁ</td>
			  </tr>
			</table>			

			  
			<form name="search_data" id="search_data" action="" method="GET">
			<input type="hidden" name="CL_title" id="CL_title" />
			<input type="hidden" name="CL_no" id="CL_no" />
			<input type="hidden" name="CL_yy" id="CL_yy" />
			<input type="hidden" name="CL_location" id="CL_location" />
			<input type="hidden" name="CL_date" id="CL_date" />
			<input type="hidden" name="CL_type" id="CL_type" />
			<input type="hidden" name="CL_agenda" id="CL_agenda" />
			<input type="hidden" name="search_key" id="search_key" />
			<input type="hidden" name="profile_no" id="profile_no" />
			<input type="hidden" name="profile_year" id="profile_year" />
			<input type="hidden" name="place_id" id="place_id" />
			<input type="hidden" name="date_start" id="date_start" />
			<input type="hidden" name="date_end" id="date_end" />
			<input type="hidden" name="siteid" id="siteid" />
			<input type="hidden" name="li" id="li" />
            <input type="hidden" name="CL_agenda_sel" id="CL_agenda_sel" />
            <input type="hidden" name="CL_type_sel" id="CL_type_sel" />
            <input type="hidden" name="area" id="area" value="<?php echo $_GET['area']; ?>" />
			</form>
			
</div><!-- END li5 -->
    
    
	<!--¡ÃÒ¿-->
	 <div id="tab4" class="tab_content">
              
<?
						$this_mount =  intval(date('m'));
						if($this_mount>9){
							$bugget_year =  intval(date('Y'))+1;
						}else{
							$bugget_year =  intval(date('Y'));
						}
						$last_year = $bugget_year+542;
						$this_year = $bugget_year+543;
                        $w1 = 570;
                        $h1 = 295;
                        $report_list = "µØÅÒ¤Á »Õ ".$last_year.";¾ÄÉ¨Ô¡ÒÂ¹ »Õ ".$last_year.";¸Ñ¹ÇÒ¤Á »Õ ".$last_year.";Á¡ÃÒ¤Á »Õ ".$this_year.";¡ØÁÀÒ¾Ñ¹¸ì »Õ ".$this_year.";ÁÕ¹Ò¤Á »Õ ".$this_year.";àÁÉÒÂ¹ »Õ ".$this_year.";¾ÄÉÀÒ¤Á »Õ ".$this_year.";ÁÔ¶Ø¹ÒÂ¹ »Õ ".$this_year.";¡Ã¡¯Ò¤Á »Õ ".$this_year.";ÊÔ§ËÒ¤Á »Õ ".$this_year.";¡Ñ¹ÂÒÂ¹ »Õ ".$this_year."";
                        $data1 = "10;11;12;1;2;3;4;5;6;7;8;9";
						
						$month = date(m);
						for($i=0;$i<12;$i++){
							$month_list[$val]=$shortmonth[$val];
							$data_list[$val] = 0;
						}
						//echo "<pre>";print_r($month_list);die;
						
						//$xsiteid = $secid != "cmss_master" ? $siteid : $xsiteid;
						//$site_condition = $xsiteid ? "AND agenda_meeting_schedule.siteid = '$xsiteid' " : "" ;
						
							
						$strSQL = "
						SELECT
						MONTH(agenda_profile_main.date_start) as month ,YEAR(agenda_profile_main.date_start) as year , COUNT(agenda_profile_main.meeting_id) as num
						FROM 
							agenda_profile_main
						WHERE 1=1
						".$where_site."
						AND date_start BETWEEN  '".($bugget_year-1)."-10-01' AND '".$bugget_year."-09-30'
						GROUP BY
						MONTH(agenda_profile_main.date_start),
						YEAR(agenda_profile_main.date_start)
						";
						if($debug=="ON"){ echo "<pre>$strSQL</pre>";}
						$result_edu = mysql_db_query($dbmaster,$strSQL);
						if(@mysql_num_rows($result_edu)>0){
							while($row = mysql_fetch_assoc($result_edu)){
								$data_list[$row[month]] = $row[num];
							}
						}
						//$report_list = implode(";",$month_list);
						$data1 = implode(";",$data_list);
						$xname = " ¡ÃÒ¿Ê¶ÔµÔ¡ÒÃ»ÃÐªØÁ¢Í§Ê¾·. »Õ§º»ÃÐÁÒ³ (".$this_year.") ".str_replace("ÊÓ¹Ñ¡§Ò¹à¢µ¾×é¹·Õè¡ÒÃÈÖ¡ÉÒ","",$caption);
                        ?>
                       <iframe src="../agenda/graphservice.php?category=<?php echo $report_list ?>&data1=<?php echo $data1 ?>&outputstyle=&numseries=1&seriesname=;&graphtype=line&title=&title=<?php echo $xname ?>&yname=¨Ó¹Ç¹¤ÃÑé§&subtitle=&graphstyle=srd_allvisible_sf_18" scrolling="no" width="100%" height="393px"></iframe>
                        
	 </div>
</div
></td></tr></table>
</body>
</html>

