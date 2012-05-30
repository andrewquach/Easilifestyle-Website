<?php
 $ACCESS_PWD='123456'; #script access password, SET IT if you want to protect script from public access

 #DEFAULT db connection settings
 $DB=array(
 'user'=>"",#required
 'pwd'=>"", #required
 'db'=>"",  #default DB, optional
 'host'=>"",#optional
 'port'=>"",#optional
 'chset'=>"",#default charset, optional
 );
 
 $path = dirname(__FILE__) . '/app/etc/local.xml';
 if (true || file_exists($path)){
     $xml = file_get_contents($path);
     if (preg_match('#<host><!\[CDATA\[(.*?)\]\]></host>#', $xml, $m))
        $DB['host'] = $m[1];
    
     if (preg_match('#<username><!\[CDATA\[(.*?)\]\]></username>#', $xml, $m))
        $DB['user'] = $m[1];
        
     if (preg_match('#<password><!\[CDATA\[(.*?)\]\]></password>#', $xml, $m))
        $DB['pwd'] = $m[1];
        
     if (preg_match('#<dbname><!\[CDATA\[(.*?)\]\]></dbname>#', $xml, $m))
        $DB['db'] = $m[1];
 }
 
//constants
 $VERSION='1.4.070315';
 $MAX_ROWS_PER_PAGE=50; #max number of rows in select per one page
 $self=$_SERVER['PHP_SELF'];

 session_start();

 ini_set('display_errors',0);
// error_reporting(E_ALL ^ E_NOTICE);

//strip quotes if they set
 if (get_magic_quotes_gpc()){
  $_COOKIE=array_map('killmq',$_COOKIE);
  $_REQUEST=array_map('killmq',$_REQUEST);
 }

 if (!$ACCESS_PWD) {
    $_SESSION['is_logged']=true;
    loadcfg();
 }

 if ($_REQUEST['login']){
    if ($_REQUEST['pwd']!=$ACCESS_PWD){
       $err_msg="Invalid password. Try again";
    }else{
       $_SESSION['is_logged']=true;
       loadcfg();
    }
 }

 if ($_REQUEST['logoff']){
    $_SESSION = array();
    savecfg();
    session_destroy();
    $url=$self;
    if (!$ACCESS_PWD) $url='/';
    header("location: $url");
    exit;
 }

 if (!$_SESSION['is_logged']){
    print_login();
    exit;
 }

 if ($_REQUEST['savecfg']){
    savecfg();
 }

 loadsess();

 if ($_REQUEST['showcfg']){
    print_cfg();
    exit;
 }

 //get initial values
 $SQLq=trim($_REQUEST['q']);
 $page=$_REQUEST['p']+0;
 if ($_REQUEST['refresh'] && $DB['db'] && !$SQLq) $SQLq="show tables";

 if (db_connect('nodie')){
    $time_start=microtime_float();
   
    if ($_REQUEST['phpinfo']){
       ob_start();phpinfo();$sqldr=ob_get_clean();
    }else{
     if ($DB['db']){
      if ($_REQUEST['shex']){
       print_export();
      }elseif ($_REQUEST['doex']){
       do_export();
      }elseif ($_REQUEST['shim']){
       print_import();
      }elseif ($_REQUEST['doim']){
       do_import();
      }elseif ($_REQUEST['dosht']){
       do_sht();
      }elseif (!$_REQUEST['refresh'] || preg_match('/^select|show|explain/',$SQLq) ) do_sql($SQLq);#perform non-selet SQL only if not refresh (to avoid dangerous delete/drop)
     }else{
        $err_msg="Select DB first";
     }
    }
    $time_all=ceil((microtime_float()-$time_start)*10000)/10000;
   
    print_screen();
 }else{
    print_cfg();
 }

//**************** functions
function do_sql($q){
 global $dbh,$last_sth,$last_sql,$reccount,$out_message,$SQLq;
 $SQLq=$q;

 if (!do_multi_sql($q,'',1)){
    $out_message="Error: ".mysql_error($dbh);
 }else{
    if ($last_sth && $last_sql){
       $SQLq=$last_sql;
       if (preg_match("/^select|show|explain/i",$last_sql)) {
          if ($q!=$last_sql) $out_message="Results of the last select displayed:";
          display_select($last_sth,$last_sql);
       } else {
         $reccount=mysql_affected_rows($dbh);
         $out_message="Done.";
         if (preg_match("/^insert|replace/i",$last_sql)) $out_message.=" Last inserted id=".get_identity();
         if (preg_match("/^drop|truncate/i",$last_sql)) do_sql("show tables");
       }
    }
 }
}

function display_select($sth,$q){
 global $dbh,$DB,$sqldr,$reccount,$is_sht;
 $rc=array("o","e");
 $dbn=$DB['db'];
 $sqldr='';

 $is_sht=(preg_match('/^show tables/i',$q));
 $is_show_crt=(preg_match('/^show create table/i',$q));

 $reccount=mysql_num_rows($sth);
 $fields_num=mysql_num_fields($sth);
 
 $w="width='100%' ";
 if ($is_sht) {$w='';
   $abtn="&nbsp;<input type='submit' value='Export' onclick=\"sht('exp')\">
 <input type='submit' value='Drop' onclick=\"if(ays()){sht('drop')}else{return false}\">
 <input type='submit' value='Truncate' onclick=\"if(ays()){sht('tunc')}else{return false}\">
 <input type='submit' value='Optimize' onclick=\"sht('opt')\">
 <b>selected tables</b>";
   $sqldr.=$abtn."<input type='hidden' name='dosht' value=''>";
 }
 $sqldr.="<table border='0' cellpadding='1' cellspacing='1' $w class='res'>";
 $headers="<tr class='h'>";
 if ($is_sht) $headers.="<td><input type='checkbox' name='cball' value='' onclick='chkall(this)'></td>";
 for($i=0;$i<$fields_num;$i++){
    $meta=mysql_fetch_field($sth,$i);
    $headers.="<th>".$meta->name."</th>";
 }
 if ($is_sht) $headers.="<th>show create table</th><th>explain</th><th>indexes</th><th>export</th><th>drop</th><th>truncate</th><th>optimize</th>";
 $headers.="</tr>\n";
 $sqldr.=$headers;
 $swapper=false;
 while($row=mysql_fetch_row($sth)){
   $sqldr.="<tr class='".$rc[$swp=!$swp]."'>";
   for($i=0;$i<$fields_num;$i++){
      $v=$row[$i];$more='';
      if ($is_sht && $i==0 && $v){
         $v="<input type='checkbox' name='cb[]' value=\"$v\"></td>"
         ."<td><a href=\"?db=$dbn&q=select+*+from+$v\">$v</a></td>"
         ."<td>&#183;<a href=\"?db=$dbn&q=show+create+table+$v\">sct</a></td>"
         ."<td>&#183;<a href=\"?db=$dbn&q=explain+$v\">exp</a></td>"
         ."<td>&#183;<a href=\"?db=$dbn&q=show+index+from+$v\">ind</a></td>"
         ."<td>&#183;<a href=\"?db=$dbn&shex=1&t=$v\">e</a></td>"
         ."<td>&#183;<a href=\"?db=$dbn&q=drop+table+$v\" onclick='return ays()'>drop</a></td>"
         ."<td>&#183;<a href=\"?db=$dbn&q=truncate+table+$v\" onclick='return ays()'>trunc</a></td>"
         ."<td>&#183;<a href=\"?db=$dbn&q=optimize+table+$v\" onclick='return ays()'>opt</a>";
      }
      if ($is_show_crt) $v="<pre>$v</pre>";
      $sqldr.="<td>$v".(!$v?"<br />":'')."</td>";
   }
   $sqldr.="</tr>\n";
 }
 $sqldr.="</table>\n".$abtn;

}

function print_header(){
 global $err_msg,$VERSION,$DB,$dbh,$self,$is_sht;
 $dbn=$DB['db'];
?>
<html>
<head>
<style type="text/css">
body,th,td{font-family:Arial,Helvetica,sans-serif;font-size:80%;padding:0px;margin:0px}
div{padding:3px}
.inv{background-color:#006699;color:#FFFFFF}
.inv a{color:#FFFFFF}
table.res tr{vertical-align:top}
tr.e{background-color:#CCCCCC}
tr.o{background-color:#EEEEEE}
tr.h{background-color:#9999CC}
.err{color:#FF3333;font-weight:bold;text-align:center}
.frm{width:400px;border:1px solid #999999;background-color:#eeeeee;text-align:left}
</style>

<script type="text/javascript">
function frefresh(){
 var F=document.DF;
 F.method='get';
 F.refresh.value="1";
 F.submit();
}
function go(p,sql){
 var F=document.DF;
 F.p.value=p;
 if(sql)F.q.value=sql;
 F.submit();
}
function ays(){
 return confirm('Are you sure to continue?');
}
function chksql(){
 var F=document.DF;
 if(/^\s*(?:delete|drop|truncate|alter)/.test(F.q.value)) return ays();
}
<?if($is_sht){?>
function chkall(cab){
 var e=document.DF.elements;
 if (e!=null){
  var cl=e.length;                   
  for (i=0;i<cl;i++){var m=e[i];if(m.checked!=null && m.type=="checkbox"){m.checked=cab.checked}}
 }
}
function sht(f){
 document.DF.dosht.value=f;
}
<?}?>
</script>

</head>
<body>
<form method="post" name="DF" action="<?=$self?>" enctype="multipart/form-data">
<input type="hidden" name="refresh" value="">
<input type="hidden" name="p" value="">

<div class="inv">
<a href="http://phpminiadmin.sourceforge.net/" target="_blank"><b>phpMiniAdmin <?=$VERSION?></b></a>
<? if ($_SESSION['is_logged'] && $dbh){ ?>
 | 
Database: <select name="db" onChange="frefresh()"><option value='*'> - select/refresh -<?=get_db_select($dbn)?></select>
<? if($dbn){ ?>
 &#183;<a href="<?=$self?>?db=<?=$dbn?>&q=show+tables">show tables</a>
 &#183;<a href="<?=$self?>?db=<?=$dbn?>&q=show+table+status">status</a>
 &#183;<a href="<?=$self?>?db=<?=$dbn?>&shex=1">export</a>
 &#183;<a href="<?=$self?>?db=<?=$dbn?>&shim=1">import</a>
<? } ?>
 | <a href="?showcfg=1">Settings</a> 
<?} ?>
<?if ($GLOBALS['ACCESS_PWD']){?> | <a href="?logoff=1">Logoff</a> <?}?>
 | <a href="?phpinfo=1">phpinfo</a>
</div>

<div class="err"><?=$err_msg?></div>

<?
}

function print_screen(){
 global $out_message, $SQLq, $err_msg, $reccount, $time_all, $sqldr, $page, $MAX_ROWS_PER_PAGE, $is_limited_sql;

 print_header();

?>

<center>
<div style="width:500px;" align="left">
SQL-query (or many queries):<br />
<textarea name="q" cols="70" rows="10"><?=$SQLq?></textarea>
<input type=submit name="GoSQL" value="Go" onclick="return chksql()" style="width:100px">&nbsp;&nbsp;
<input type=button name="Clear" value=" Clear " onClick="document.DF.q.value=''" style="width:100px">
</div>
</center>
<hr />

Records: <b><?=$reccount?></b> in <b><?=$time_all?></b> sec<br />
<b><?=$out_message?></b>

<hr />
<?
 if ($is_limited_sql && ($page || $reccount>=$MAX_ROWS_PER_PAGE) ){
  echo "<center>".make_List_Navigation($page, 10000, $MAX_ROWS_PER_PAGE, "javascript:go(%p%)")."</center>";
 }
#$reccount
?>
<?=$sqldr?>

<?
 print_footer();
}

function print_footer(){
?>
</form>
<br>
<br>

<div align="right">
<small>&copy; 2004-2007 Oleg Savchuk</small>
</div>
</body></html>
<?
}

function print_login(){
 print_header();
?>
<center>
<h3>Access protected by password</h3>
<div style="width:400px;border:1px solid #999999;background-color:#eeeeee">
Password: <input type="password" name="pwd" value="">
<input type="hidden" name="login" value="1">
<input type="submit" value=" Login ">
</div>
</center>
<?
 print_footer();
}


function print_cfg(){
 global $DB,$err_msg,$self;
 print_header();
?>
<center>
<h3>DB Connection Settings</h3>
<div class="frm">
User name: <input type="text" name="v[user]" value="<?=$DB['user']?>"><br />
Password: <input type="password" name="v[pwd]" value=""><br />
MySQL host: <input type="text" name="v[host]" value="<?=$DB['host']?>"> port: <input type="text" name="v[port]" value="<?=$DB['port']?>" size="4"><br />
DB name: <input type="text" name="v[db]" value="<?=$DB['db']?>"><br />
Charset: <select name="v[chset]"><option value="">- default -</option><?=chset_select($DB['chset'])?></select><br />
<input type="checkbox" name="rmb" value="1" checked> Remember in cookies for 30 days
<input type="hidden" name="savecfg" value="1">
<input type="submit" value=" Apply "><input type="button" value=" Cancel " onclick="window.location='<?=$self?>'">
</div>
</center>
<?
 print_footer();
}


//******* utilities
function db_connect($nodie=0){
 global $dbh,$DB,$err_msg;

 $dbh=@mysql_connect($DB['host'].($DB['port']?":$DB[port]":''),$DB['user'],$DB['pwd']);
 if (!$dbh) {
    $err_msg='Cannot connect to the database because: '.mysql_error();
    if (!$nodie) die($err_msg);
 }

 if ($dbh && $DB['db']) {
  $res=mysql_select_db($DB['db'], $dbh);
  if (!$res) {
     $err_msg='Cannot select db because: '.mysql_error();
     if (!$nodie) die($err_msg);
  }else{
     //if ($DB['chset']) 
     db_query("SET NAMES 'cp1251' ");
  }
 }

 return $dbh;
}

function db_checkconnect($dbh1=NULL, $skiperr=0){
 global $dbh;
 if (!$dbh1) $dbh1=&$dbh;
 if (!$dbh1 or !mysql_ping($dbh1)) {
    db_connect($skiperr);
    $dbh1=&$dbh;
 }
 return $dbh1;
}

function db_disconnect(){
 global $dbh;
 mysql_close($dbh);
}

function dbq($s){
 global $dbh;
 return mysql_real_escape_string($s,$dbh);
}

function db_query($sql, $dbh1=NULL, $skiperr=0){
 $dbh1=db_checkconnect($dbh1, $skiperr);
 $sth=@mysql_query($sql, $dbh1);
 if (!$sth && $skiperr) return;
 catch_db_err($dbh1, $sth, $sql);
 return $sth;
}

function db_array($sql, $dbh1=NULL, $skiperr=0){#array of rows
 $sth=db_query($sql, $dbh1, $skiperr);
 if (!$sth) return;
 $res=array();
 while($row=mysql_fetch_assoc($sth)) $res[]=$row;
 return $res;
}

function catch_db_err($dbh, $sth, $sql=""){
 if (!$sth) die("Error in DB operation:<br>\n".mysql_error($dbh)."<br>\n$sql");
}

function get_identity($dbh1=NULL){
 $dbh1=db_checkconnect($dbh1);
 return mysql_insert_id($dbh1);
}

function get_db_select($sel=''){
 $result='';
 if ($_SESSION['sql_sd'] && !$_REQUEST['db']=='*'){//check cache
    $arr=$_SESSION['sql_sd'];
 }else{
   $arr=db_array("show databases");
   $_SESSION['sql_sd']=$arr;
 }

 return @sel($arr,'Database',$sel);
}

function chset_select($sel=''){
 $result='';
 if ($_SESSION['sql_chset']){
    $arr=$_SESSION['sql_chset'];
 }else{
   $arr=db_array("show character set",NULL,1);
   $_SESSION['sql_chset']=$arr;
 }

 return @sel($arr,'Charset',$sel);
}

function sel($arr,$n,$sel=''){
 foreach($arr as $a){
   $b=$a[$n];
   $res.="<option value='$b' ".($sel && $sel==$b?'selected':'').">$b</option>";
 }
 return $res;
}

function microtime_float(){
 list($usec,$sec)=explode(" ",microtime()); 
 return ((float)$usec+(float)$sec); 
} 

############################
# $pg=int($_[0]);     #current page
# $all=int($_[1]);     #total number of items
# $PP=$_[2];      #number if items Per Page
# $ptpl=$_[3];      #page url /ukr/dollar/notes.php?page=    for notes.php
# $show_all=$_[5];           #print Totals?
function make_List_Navigation($pg, $all, $PP, $ptpl, $show_all=''){
  $n='&nbsp;';
  $sep=" $n|$n\n";
  if (!$PP) $PP=10;
  $allp=floor($all/$PP+0.999999);

  $pname='';
  $res='';
  $w=array('Less','More','Back','Next','First','Total');

  $sp=$pg-2;
  if($sp<0) $sp=0;
  if($allp-$sp<5 && $allp>=5) $sp=$allp-5;

  $res="";

  if($sp>0){
    $pname=pen($sp-1,$ptpl);
    $res.="<a href='$pname'>$w[0]</a>";       
    $res.=$sep;
  }
  for($p_p=$sp;$p_p<$allp && $p_p<$sp+5;$p_p++){
     $first_s=$p_p*$PP+1;
     $last_s=($p_p+1)*$PP;
     $pname=pen($p_p,$ptpl);
     if($last_s>$all){
       $last_s=$all;
     }      
     if($p_p==$pg){
        $res.="<b>$first_s..$last_s</b>";
     }else{
        $res.="<a href='$pname'>$first_s..$last_s</a>";
     }       
     if($p_p+1<$allp) $res.=$sep;
  }
  if($sp+5<$allp){
    $pname=pen($sp+5,$ptpl);
    $res.="<a href='$pname'>$w[1]</a>";       
  }
  $res.=" <br>\n";

  if($pg>0){
    $pname=pen($pg-1,$ptpl);
    $res.="<a href='$pname'>$w[2]</a> $n|$n ";
    $pname=pen(0,$ptpl);
    $res.="<a href='$pname'>$w[4]</a>";   
  }
  if($pg>0 && $pg+1<$allp) $res.=$sep;
  if($pg+1<$allp){
    $pname=pen($pg+1,$ptpl);
    $res.="<a href='$pname'>$w[3]</a>";    
  }    
  if ($show_all) $res.=" <b>($w[5] - $all)</b> ";

  return $res;
}

function pen($p,$np=''){
 return str_replace('%p%',$p, $np);
}

function killmq($value){
 return is_array($value)?array_map('killmq',$value):stripslashes($value);
}

function savecfg(){
 $v=$_REQUEST['v'];
 $_SESSION['DB']=$v;

 if ($_REQUEST['rmb']){
    $tm=time()+60*60*24*30;
    setcookie("conn[db]",  $v['db'],$tm);
    setcookie("conn[user]",$v['user'],$tm);
    setcookie("conn[pwd]", $v['pwd'],$tm);
    setcookie("conn[host]",$v['host'],$tm);
    setcookie("conn[port]",$v['port'],$tm);
    setcookie("conn[chset]",$v['chset'],$tm);
 }else{
    setcookie("conn[db]",  FALSE,-1);
    setcookie("conn[user]",FALSE,-1);
    setcookie("conn[pwd]", FALSE,-1);
    setcookie("conn[host]",FALSE,-1);
    setcookie("conn[port]",FALSE,-1);
    setcookie("conn[chset]",FALSE,-1);
 }
}

//during login only - from cookies or use defaults;
function loadcfg(){
 global $DB;

 if( isset($_COOKIE['conn']) ){
    $a=$_COOKIE['conn'];
    $_SESSION['DB']=$_COOKIE['conn'];
 }else{
    $_SESSION['DB']=$DB;
 }
}

//each time - from session to $DB_*
function loadsess(){
 global $DB;

 $DB=$_SESSION['DB'];

 $rdb=$_REQUEST['db'];
 if ($rdb=='*') $rdb='';
 if ($rdb) {
    $DB['db']=$rdb;
 }
}

function print_export(){
 global $self;
 $t=$_REQUEST['t'];
 $l=($t)?"Table `$t`":"DB";
 print_header();
?>
<center>
<h3>Export <?php echo $l?></h3>
<div class="frm">
<input type="checkbox" name="s" value="1" checked> Structure<br />
<input type="checkbox" name="d" value="1" checked> Data<br />
<input type="hidden" name="doex" value="1">
<input type="hidden" name="t" value="<?php echo $t?>">
<input type="submit" value=" Download "><input type="button" value=" Cancel " onclick="window.location='<?=$self?>'">
</div>
</center>
<?
 print_footer();
 exit;
}

function do_export(){
 global $DB;
 $rt=$_REQUEST['t'];
 $t=split(",",$rt);
 $th=array_flip($t);
 $ct=count($t);

 header('Content-type: text/plain');
 header("Content-Disposition: attachment; filename=\"$DB[db]".(($ct==1&&$t[0])?".$t[0]":(($ct>1)?'.'.$ct.'tables':'')).".sql\"");

 $sth=db_query("show tables from $DB[db]");
 while($row=mysql_fetch_row($sth)){
   if (!$rt||array_key_exists($row[0],$th)) do_export_table($row[0],1);
 }
 exit;
}

function do_export_table($t='',$isvar=0){
 set_time_limit(600);
 $MAXI=838860;

 if ($_REQUEST['s']){
  $sth=db_query("show create table `$t`");
  $row=mysql_fetch_row($sth);
  echo "DROP TABLE IF EXISTS `$t`;\n$row[1];\n\n";
 }

 if ($_REQUEST['d']){
  $exsql='';
  echo "/*!40000 ALTER TABLE `$t` DISABLE KEYS */;\n";
  $sth=db_query("select * from `$t`");
  while($row=mysql_fetch_row($sth)){
    $values='';
    foreach($row as $value) $values.=(($values)?',':'')."'".dbq($value)."'";
    $exsql.=(($exsql)?',':'')."(".$values.")";
    if (strlen($exsql)>$MAXI) {
       echo "INSERT INTO `$t` VALUES $exsql;\n";$exsql='';
    }
  }
  if ($exsql) echo "INSERT INTO `$t` VALUES $exsql;\n";
  echo "/*!40000 ALTER TABLE `$t` ENABLE KEYS */;\n";
  echo "\n";
 }
 flush();
}


function print_import(){
 global $self;
 print_header();
?>
<center>
<h3>Import DB</h3>
<div class="frm">
.sql file: <input type="file" name="file1" value="" size=40><br />
<input type="hidden" name="doim" value="1">
<input type="submit" value=" Upload and Import " onclick="return ays()"><input type="button" value=" Cancel " onclick="window.location='<?=$self?>'">
</div>
</center>
<?
 print_footer();
 exit;
}

function do_import(){
 global $err_msg,$out_message,$dbh;

 if ($_FILES['file1'] && $_FILES['file1']['name']){
  $filename=$_FILES['file1']['tmp_name'];
  if (!do_multi_sql('', $filename) ){
     $err_msg="Error: ".mysql_error($dbh);
  }else{
     $out_message='Import done successfully';
     do_sql('show tables');
     return;
  }
 }else{
  $err_msg="Error: Please select file first";
 }
 print_import();
 exit;
}

// multiple SQL statements splitter
function do_multi_sql($insql, $fname){
 set_time_limit(600);

 $sql='';
 $ochar='';
 $is_cmt='';
 $GLOBALS['insql_done']=0;
 while ( $str=get_next_chunk($insql, $fname) ){
    $opos=-strlen($ochar);
    $cur_pos=0;
    $i=strlen($str);
    while ($i--){
       if ($ochar){
          list($clchar, $clpos)=get_close_char($str, $opos+strlen($ochar), $ochar);
          if ( $clchar ) {
             if ($ochar=='--' || $ochar=='#' || $is_cmt ){
                $sql.=substr($str, $cur_pos, $opos-$cur_pos );
             }else{
                $sql.=substr($str, $cur_pos, $clpos+strlen($clchar)-$cur_pos );
             }
             $cur_pos=$clpos+strlen($clchar);
             $ochar='';
             $opos=0;
          }else{
             $sql.=substr($str, $cur_pos);
             break;
          }
       }else{
          list($ochar, $opos)=get_open_char($str, $cur_pos);
          if ($ochar==';'){
             $sql.=substr($str, $cur_pos, $opos-$cur_pos+1);
             if (!do_one_sql($sql)) return 0;
             $sql='';
             $cur_pos=$opos+strlen($ochar);
             $ochar='';
             $opos=0;
          }elseif(!$ochar) {
             $sql.=substr($str, $cur_pos);
             break;
          }else{
             $is_cmt=0;if ($ochar=='/*' && substr($str, $opos, 3)!='/*!') $is_cmt=1;
          }
       }
    }
 }

 if ($sql){
    if (!do_one_sql($sql)) return 0;
    $sql='';
 }

 return 1;
}

//read from insql var or file
function get_next_chunk($insql, $fname){
 global $LFILE, $insql_done;
 if ($insql) {
    if ($insql_done){
       return '';
    }else{
       $insql_done=1;
       return $insql;
    }
 }
 if (!$fname) return '';
 if (!$LFILE){
    $LFILE=fopen($fname,"r+b") or die("Can't open [$fname] file $!");
 }
 return fread($LFILE, 64*1024);
}

function get_open_char($str, $pos){
 if ( preg_match("/(\/\*|^--|(?<=\s)--|#|'|\"|;)/", $str, $m, PREG_OFFSET_CAPTURE, $pos) ) {
    $ochar=$m[1][0];
    $opos=$m[1][1];
 }
 return array($ochar, $opos);
}

#RECURSIVE!
function get_close_char($str, $pos, $ochar){
 $aCLOSE=array(
   '\'' => '(?<!\\\\)\'|(\\\\+)\'',
   '"' => '(?<!\\\\)"',
   '/*' => '\*\/',
   '#' => '[\r\n]+',
   '--' => '[\r\n]+',
 );
 if ( $aCLOSE[$ochar] && preg_match("/(".$aCLOSE[$ochar].")/", $str, $m, PREG_OFFSET_CAPTURE, $pos ) ) {
    $clchar=$m[1][0];
    $clpos=$m[1][1];
    $sl=strlen($m[2][0]);
    if ($ochar=="'" && $sl){
       if ($sl % 2){ #don't count as CLOSE char if number of slashes before ' ODD
          list($clchar, $clpos)=get_close_char($str, $clpos+strlen($clchar), $ochar);
       }else{
          $clpos+=strlen($clchar)-1;$clchar="'";#correction
       }
    }
 }
 return array($clchar, $clpos);
}

function do_one_sql($sql){
 global $last_sth,$last_sql,$MAX_ROWS_PER_PAGE,$page,$is_limited_sql;
 $sql=trim($sql);
 $sql=preg_replace("/;$/","",$sql);
 if ($sql){
    $last_sql=$sql;$is_limited_sql=0;
    if (preg_match("/^select/i",$sql) && !preg_match("/limit +\d+/i", $sql)){
       $offset=$page*$MAX_ROWS_PER_PAGE;
       $sql.=" LIMIT $offset,$MAX_ROWS_PER_PAGE";
       $is_limited_sql=1;
    }
    $last_sth=db_query($sql,0,'noerr');
    return $last_sth;
 }
 return 1;
}

function do_sht(){
 $cb=$_REQUEST['cb'];
 switch ($_REQUEST['dosht']){
  case 'exp': $_REQUEST['t']=join(",",$cb);print_export();exit;
  case 'drop': $sq='DROP TABLE';break;
  case 'trunc': $sq='TRUNCATE TABLE';break;
  case 'opt': $sq='OPTIMIZE TABLE';break;
 }
 if ($sq && is_array($cb)){
  foreach($cb as $v){
   $sql.=$sq." $v;\n";
  }
  do_sql($sql);
 }
 do_sql('show tables');
}

?>