
<style type="text/css">
body {font:normal 12px Verdana}
a#tip {position:relative;left:1px; font-weight:bold;}
a#tip:link,a#tip:hover {text-decoration:none;display:block}
a#tip span {display:none;text-decoration:none;}
a#tip:visited {text-decoration:underline;}
a#tip:hover #tip_info {display:block;background:#FFFFFF;padding:0px 50px;position:absolute;top:0px;left:2px;color:#000}
a img{border:none}
</style>
<script>
function movtip(e){
    var ele,x,y;
    if(e.target){
        ele=e.target;
        x=e.layerX;
        y=e.layerY;
    }
    else{
        ele=event.srcElement;
        x=event.x;
        y=event.y;
    }
    if(ele.tagName!="A"){
        document.title=ele.tagName;
        return;
    }
    ele=ele.getElementsByTagName("span")[0];
    with(ele.style){
        left=x+3+"px";
        top=y+3+"px";
    }
}
</script>


<body>
<div id="fb-root"></div>
<div class="site-wrapper">

<div class="site-wrapper-inner">

<div class="cover-container">
<?php
    error_reporting(0);
    //session_start();
    require_once('header.php');
    //require_once('configure.php');
    
?>
<TABLE>
<TR>
<TD width = "70px "><a href="datas_artist.php"><h6 style="color:#F2F5A9">artist</h6>
</td>
<TD width = "70px "><a href="datas_genre.php"><h6 style="color:#F2F5A9">genre</h6>
</td>
<TD width = "70px "><a href="datas_track.php"><h6 style="color:#F2F5A9">track</h6>
</td>
<TD width = "70px "><a href="datas_recording.php"><h6 style="color:#FFA500">recording</h6>
</td>
<TD width = "100px "><a href="datas_relemedi.php"><h6 style="color:#F2F5A9">release_medium</h6>
</td>
<TD width = "100px "><a href="datas_modify.php"><h6 style="color:#F2F5A9">modify_data</h6>
</td>
</TR>
</TABLE>

<?php
    
    $id = '';
    if (isset($_GET['id'])) {
        // cast var as int
        $id = (string) $_GET['id'];
    }
    
    $name = '';
    // get the keyword
    if (isset($_GET['name'])) {
        // cast var as int
        $name = (string) $_GET['name'];
    }
    
    $length = '';
    // get the keyword
    if (isset($_GET['length'])) {
        // cast var as int
        $type = (string) $_GET['length'];
    }
    
    $ora_host = "icoracle.epfl.ch";
    $ora_port="1521";
    $ora_sid = "srso4.epfl.ch";
    $ora_username = "db2014_g18";
    $ora_password = "db2014_g18";
    $charset = "UTF8";
    $ora_connstr = "(description=(address=(protocol=tcp)
    (host=".$ora_host.")(port=".$ora_port."))
    (connect_data=(service_name=".$ora_sid.")))";
    $conn = oci_connect($ora_username, $ora_password,$ora_connstr,$charset);
    $sql = "SELECT count(*) as COUNTNUM from (select ar.* FROM ( select * from recording where ID like '%$id%' and NAME like '%$name%'and LENGTH like '%$length%') ar )";
    $stmt = oci_parse($conn, $sql);

    oci_execute($stmt, OCI_DEFAULT);
    oci_fetch($stmt);
    
    $numrows = oci_result($stmt, 'COUNTNUM');
    //oci_fetch($r);
    $rowsperpage = 20;
    // find out total pages
    $totalpages = ceil($numrows / $rowsperpage);
    // get the current page or set a default
    if (isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) {
        // cast var as int
        $currentpage = (int) $_GET['currentpage'];
    } else {
        // default page num
        $currentpage = 1;
    } // end if
    // if current page is greater than total pages...
    if ($currentpage > $totalpages) {
        // set current page to last page
        $currentpage = $totalpages;
    } // end if
    // if current page is less than first page...
    if ($currentpage < 1) {
        // set current page to first page
        $currentpage = 1;
    } // end if
    ?>
<?php
    // the offset of the list, based on current page
    $offset = ($currentpage - 1) * $rowsperpage + 1;
    $num = $offset + $rowsperpage - 1;
    $sql = "SELECT * from (select ar.*, rownum rm FROM  ( select * from recording where ID like '%$id%' and NAME like '%$name%'and LENGTH like '%$length%') ar ) where rm between $offset and $num";
    $stid = oci_parse($conn, $sql);
    oci_execute($stid, OCI_DEFAULT);
    ?>

<TABLE>
<TD width = "80px" style="color:#C0C0C0">ID
<TD width = "300px "style="color:#C0C0C0">NAME
<TD width = "80px "style="color:#C0C0C0">LENGTH
<TD width = "80px "style="color:#C0C0C0">TRACK
</TD>
</TABLE>

<?PHP
    while (oci_fetch($stid)) {
        ?>
<TABLE>
<TD width = "80px "><?php echo oci_result($stid, 'ID'); ?>
<TD width = "300px "><?php $namee = oci_result($stid, 'NAME');
    if (mb_strlen($namee) <= 35) {
        echo $namee;
    }
    else {
        echo substr($namee, 0, 32);
        echo "...";
    }?>
<TD width = "80px "><?php echo oci_result($stid, 'LENGTH'); ?>
<TD width = "80px "><a id="tip" href="#" onmousemove="movtip(event)">T
<?php
    $aaid = oci_result($stid, 'ID');
    $sql1 = "SELECT track.TID, track.POSITION FROM track where track.RCID like '$aaid'";
    $stid1 = oci_parse($conn, $sql1);
    oci_execute($stid1, OCI_DEFAULT);
    ?>
<span id="tip_info"><?php
    while (oci_fetch($stid1)) {
        $out = oci_result($stid1, 'TID');
        $out1 = oci_result($stid1, 'POSITION');
        echo $out;echo ":"; echo $out1;
    } ?></span></a>



</TD>

</TABLE>
<?PHP } ?>
<form action="<?php $_SERVER['PHP_SELF']?>" method="get" name="search_form" target="_self" id="f">
<TABLE>
<TD width = "80px" style="color:#C0C0C0">
<input type="text" name="id" class="kw" size="5" maxlength="100" style="color:#bbb"/>
<TD width = "300px" style="color:#C0C0C0">
<input type="text" name="name" class="kw" size="12" maxlength="100" style="color:#bbb"/>
<TD width = "80px" style="color:#C0C0C0">
<input type="text" name="length" class="kw" size="5" maxlength="100" style="color:#bbb"/>
</TABLE>
<input name="submit" type="submit" class="sb" value="keyword search" style="color:#000"/>
<br />

</form>
<?php
    //$conn = oci_connect('db2014_g18', 'db2014_g18', 'icoracle.epfl.ch:1521/srso4.epfl.ch');
    //if (!$conn) {
    
    //    $e = oci_error();
    
    //    print htmlentities($e['message']);
    
    //    exit;
    
    //}
    /******  build the pagination links ******/
    // range of num links to show
    $range = 3;
    
    $pere = "&id={$id}&name={$name}&length={$length}";
    
    // if not on page 1, don't show back links
    if ($currentpage > 1) {
        // show << link to go back to page 1
        echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1$pere'><<</a> ";
        // get previous page num
        $prevpage = $currentpage - 1;
        // show < link to go back to 1 page
        echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage$pere'><</a> ";
    } // end if
    
    // loop to show links to range of pages around current page
    for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
        // if it's a valid page number...
        if (($x > 0) && ($x <= $totalpages)) {
            // if we're on current page...
            if ($x == $currentpage) {
                // 'highlight' it but don't make a link
                echo " [<b>$x</b>] ";
                // if not current page...
            } else {
                // make it a link
                echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x$pere'>$x</a> ";
            } // end else
        } // end if
    } // end for
    
    // if not on last page, show forward and last page links
    if ($currentpage != $totalpages) {
        // get next page
        $nextpage = $currentpage + 1;
        // echo forward link for next page
        echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage$pere'>></a> ";
        // echo forward link for lastpage
        echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages$pere'>>></a> ";
    } // end if
    /****** end build pagination links ******/
    ?>

<?php
    require_once('footer.php');
    ?>

</div>

</div>

</div>
</body>