<body>
<div id="fb-root"></div>
<div class="site-wrapper">

<div class="site-wrapper-inner">

<div class="cover-container">
<?php
    
    //session_start();
    require_once('header.php');
    //require_once('configure.php');
    
?>
<TABLE>
<TR>
<TD width = "40px "><a href="query_a.php"><h6 style="color:#F2F5A9">A</h6>
</TD>
<TD width = "40px "><a href="query_b.php"><h6 style="color:#F2F5A9">B</h6>
</td>
<TD width = "40px "><a href="query_c.php"><h6 style="color:#F2F5A9">C</h6>
</td>
<TD width = "40px "><a href="query_d.php"><h6 style="color:#F2F5A9">D</h6>
</td>
<TD width = "40px "><a href="query_e.php"><h6 style="color:#F2F5A9">E</h6>
</td>
<TD width = "40px "><a href="query_f.php"><h6 style="color:#F2F5A9">F</h6>
</td>
<TD width = "40px "><a href="query_g.php"><h6 style="color:#F2F5A9">G</h6>
</td>
<TD width = "40px "><a href="query_h.php"><h6 style="color:#F2F5A9">H</h6>
</td>
<TD width = "40px "><a href="query_i.php"><h6 style="color:#F2F5A9">I</h6>
</td>
<TD width = "40px "><a href="query_j.php"><h6 style="color:#F2F5A9">J</h6>
</td>
<TD width = "40px "><a href="query_k.php"><h6 style="color:#F2F5A9">K</h6>
</td>
<TD width = "40px "><a href="query_l.php"><h6 style="color:#F2F5A9">L</h6>
</td>
<TD width = "40px "><a href="query_m.php"><h6 style="color:#F2F5A9">M</h6>
</td>
<TD width = "40px "><a href="query_n.php"><h6 style="color:#F2F5A9">N</h6>
</td>
<TD width = "40px "><a href="query_o.php"><h6 style="color:#F2F5A9">O</h6>
</td>
<TD width = "40px "><a href="query_p.php"><h6 style="color:#F2F5A9">P</h6>
</td>
<TD width = "40px "><a href="query_q.php"><h6 style="color:#F2F5A9">Q</h6>
</td>
<TD width = "40px "><a href="query_r.php"><h6 style="color:#F2F5A9">R</h6>
</td>
<TD width = "40px "><a href="query_s.php"><h6 style="color:#FFA500">S</h6>
</td>
</TR>
</TABLE>
<?php
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
    $view = "create view hittrack as
    select t.rcid
    from track t
    group by t.rcid
    having count(distinct t.mid)>100";
    $stid = oci_parse($conn, $view);
    oci_execute($stid, OCI_DEFAULT);
    $view = "create view htid as
    select t.tid, t.rcid
    from track t, hittrack ht
    where t.rcid = ht.rcid";
    $stid = oci_parse($conn, $view);
    oci_execute($stid, OCI_DEFAULT);
    $view = "create view hitartist as
    select at1.aid
    from artist_track at1,htid t
    where t.tid = at1.tid
    group by at1.aid
    having count(distinct t.rcid)>10";
    $stid = oci_parse($conn, $view);
    oci_execute($stid, OCI_DEFAULT);
    $view = "create view hitability as
    select lst.aid , sum(lst.count) as score
    from (select hiat.aid, t.rcid ,count(distinct t.mid) as count, rank() over (partition by hiat.aid order by count(distinct t.mid) desc) as rank
          from track t,(select hta.aid,at1.tid
                        from hitartist hta, artist_track at1
                        where hta.aid = at1.aid) hiat
          where t.tid = hiat.tid
          group by hiat.aid,t.rcid
          having count(distinct t.mid)>100) lst
    where lst.rank<=10 
    group by lst.aid";
    $stid = oci_parse($conn, $view);
    oci_execute($stid, OCI_DEFAULT);
    $stmt = oci_parse($conn, "Select count(*) as COUNTNUM from (select artist.name, ab.score
                      from hitartist art , hitability ab,artist
                      where art.aid = ab.aid and artist.id = art.aid)");
    oci_execute($stmt, OCI_DEFAULT);
    oci_fetch($stmt);
    $numrows = oci_result($stmt, 'COUNTNUM');
    //echo $numrows;
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
    
    // the offset of the list, based on current page
    $offset = ($currentpage - 1) * $rowsperpage + 1;
    $num = $offset + $rowsperpage + 1;
    $sql = "SELECT * from (select ar.*, rownum rm FROM (select artist.name, ab.score
    from hitartist art , hitability ab,artist
    where art.aid = ab.aid and artist.id = art.aid
) ar ) where rm between $offset and $num";
    $stid = oci_parse($conn, $sql);
    oci_execute($stid, OCI_DEFAULT);
    
    ?>
    Query S: List all “hit artists” according to their "hit ability".
<TABLE>
<TD width = "200px" style="color:#C0C0C0">NAME
<TD width = "300px "style="color:#C0C0C0">SCORE
</TD>
</TABLE>
    <?PHP
    while (oci_fetch($stid)) {
    ?>
        <TABLE>
            <TD width = "300px "><?php echo oci_result($stid, 'NAME'); ?>
<TD width = "200px "><?php echo oci_result($stid, 'SCORE'); ?>
        </TD>
        </TABLE>
    <?php
    }
        ?>
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
    // if not on page 1, don't show back links
    if ($currentpage > 1) {
        // show << link to go back to page 1
        echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
        // get previous page num
        $prevpage = $currentpage - 1;
        // show < link to go back to 1 page
        echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";
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
                echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x'>$x</a> ";
            } // end else
        } // end if
    } // end for
    
    // if not on last page, show forward and last page links
    if ($currentpage != $totalpages) {
        // get next page
        $nextpage = $currentpage + 1;
        // echo forward link for next page
        echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
        // echo forward link for lastpage
        echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalpages'>>></a> ";
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