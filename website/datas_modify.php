
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
    session_start();
    require_once('header.php');
    //require_once('configure.php');
    
?>
<TABLE>
<TR>
<TD width = "70px "><a href="datas_artist.php"><h6 style="color:#F2F5A9">artist</h6>
</td>
<TD width = "70px "><a href="datas_genre.php"><h6 style="color:#F2F5A9">genre</h6>
</td>
<TD width = "70px "><a href="datas_track.php"><h6 style="color:FFA500">track</h6>
</td>
<TD width = "70px "><a href="datas_recording.php"><h6 style="color:#F2F5A9">recording</h6>
</td>
<TD width = "100px "><a href="datas_relemedi.php"><h6 style="color:#F2F5A9">release_medium</h6>
<TD width = "100px "><a href="datas_modify.php"><h6 style="color:#F2F5A9">modify_data</h6>
</td>
</td>
</TR>
</TABLE>

<?php
    
    $id = '';
    // get the keyword
    if (isset($_GET['id'])) {
        // cast var as int
        $id = (string) $_GET['id'];
    }
    
    $position = '';
    // get the keyword
    if (isset($_GET['position'])) {
        // cast var as int
        $position = (string) $_GET['position'];
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
    
    //$sql = "SELECT count(*) as COUNTNUM from (select ar.* FROM ( select * from track where TID like '%$id%' and POSITION like '%$position%') ar )";
    //$stmt = oci_parse($conn, $sql);
    
    //oci_execute($stmt, OCI_DEFAULT);
    //oci_fetch($stmt);
    
    // the offset of the list, based on current page
    ?>

<TABLE>
<TD width = "200px" style="color:#C0C0C0">ID
<TD width = "80px "style="color:#C0C0C0">POSITION
<TD width = "120px "style="color:#C0C0C0">MEDIUM_ID
<TD width = "120px "style="color:#C0C0C0">RECORDING_ID
<TD width = "80px "style="color:#C0C0C0">ARTIST
</TD>
</TABLE>

    <?PHP
    while (oci_fetch($stid)) {
    ?>
        <TABLE>
            <TD width = "200px "><?php echo oci_result($stid, 'TID'); ?>
            <TD width = "80px "><?php echo oci_result($stid, 'POSITION'); ?>
            <TD width = "120px "><a id="tip" href="#" onmousemove="movtip(event)">
            <?php
                $aaid = oci_result($stid, 'MID');
                echo $aaid;
                $sql1 = "SELECT releasemedium.NAME, releasemedium.FORMAT FROM releasemedium where releasemedium.MID like '$aaid'";
                $stid1 = oci_parse($conn, $sql1);
                oci_execute($stid1, OCI_DEFAULT);
            ?>
<span id="tip_info"><?php
    while (oci_fetch($stid1)) {
        $out = oci_result($stid1, 'NAME');
        $out1 = oci_result($stid1, 'FORMAT');
        echo $out;echo "("; echo $out1; echo")";
    } ?></span></a>



</TD>
<TD width = "120px "><a id="tip" href="#" onmousemove="movtip(event)">
<?php
    $aaid = oci_result($stid, 'RCID');
    echo $aaid;
    $sql1 = "SELECT recording.NAME, recording.LENGTH FROM recording where recording.ID like '$aaid'";
    $stid1 = oci_parse($conn, $sql1);
    oci_execute($stid1, OCI_DEFAULT);
    ?>
<span id="tip_info"><?php
    while (oci_fetch($stid1)) {
        $out = oci_result($stid1, 'NAME');
        $out1 = oci_result($stid1, 'LENGTH');
        echo $out;echo ":"; echo $out1; echo "\n";
    } ?></span></a>



</TD>
<TD width = "80px "><a id="tip" href="#" onmousemove="movtip(event)">A
<?php
    $aaid = oci_result($stid, 'TID');
    $sql1 = "SELECT artist.ID, artist.NAME FROM artist_track, artist where artist_track.TID like '$aaid' and artist.ID = artist_track.AID";
    $stid1 = oci_parse($conn, $sql1);
    oci_execute($stid1, OCI_DEFAULT);
    ?>
<span id="tip_info"><?php
    while (oci_fetch($stid1)) {
        $out = oci_result($stid1, 'ID');
        $out1 = oci_result($stid1, 'NAME');
        echo $out1;echo "("; echo $out; echo ")";
    } ?></span></a>



</TD>


        </TABLE>
<?PHP } ?>
<form action="<?php $_SERVER['PHP_SELF']?>" method="get" name="search_form" target="_self" id="f">
<TABLE>
<TD width = "200px" style="color:#C0C0C0">
<input type="text" name="id" class="kw" size="8" maxlength="100" style="color:#bbb"/>
<TD width = "80px" style="color:#C0C0C0">
<input type="text" name="position" class="kw" size="5" maxlength="100" style="color:#bbb"/>
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
    
    $pere = "&id={$id}&position={$position}";
    
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