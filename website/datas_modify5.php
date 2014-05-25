    
    <body>
        <div id="fb-root"></div>
            <div class="site-wrapper">
            
            <div class="site-wrapper-inner">
                
                <div class="cover-container">
                <?php
                    require_once('header.php');
                ?>

                    
<TABLE>
<TR>
<TD width = "70px "><a href="datas_artist.php"><h6 style="color:#F2F5A9">artist</h6>
</td>
<TD width = "70px "><a href="datas_genre.php"><h6 style="color:#F2F5A9">genre</h6>
</td>
<TD width = "70px "><a href="datas_track.php"><h6 style="color:#F2F5A9">track</h6>
</td>
<TD width = "70px "><a href="datas_recording.php"><h6 style="color:#F2F5A9">recording</h6>
</td>
<TD width = "100px "><a href="datas_relemedi.php"><h6 style="color:#F2F5A9">release_medium</h6>
</td>
<TD width = "100px "><a href="datas_modify.php"><h6 style="color:#FFA500">modify_data</h6>
</td>
</TR>
</TABLE>
<TABLE>
<TD width = "80px" style="color:#FFFFFF"><a href="datas_modify.php" style="color:#C0C0C0">ARTIST</TD>
<TD width = "150px "style="color:#C0C0C0"><a href="datas_modify1.php" style="color:#C0C0C0">ARTIST_GENRE</TD>
<TD width = "150px "style="color:#C0C0C0"><a href="datas_modify2.php" style="color:#C0C0C0">ARTIST_TRACK</TD>
<TD width = "80px "style="color:#C0C0C0"><a href="datas_modify3.php" style="color:#C0C0C0">GENRE</TD>
<TD width = "150px "style="color:#C0C0C0"><a href="datas_modify4.php" style="color:#C0C0C0">RECORDING</TD>
<TD width = "150px "style="color:#C0C0C0"><a href="datas_modify5.php" style="color:#FFFFFF">RELEASEMEDIUM</TD>
<TD width = "80px "style="color:#C0C0C0"><a href="datas_modify6.php" style="color:#C0C0C0">TRACK</TD>
</TD>
</TABLE>

<?php
    $rid = '';
    // get the keyword
    if (isset($_GET['rid'])) {
        // cast var as int
        $rid = (string) $_GET['rid'];
    }
    
    $mid = '';
    // get the keyword
    if (isset($_GET['mid'])) {
        // cast var as int
        $mid = (string) $_GET['mid'];
    }
    
    $name = '';
    // get the keyword
    if (isset($_GET['name'])) {
        // cast var as int
        $name = (string) $_GET['name'];
    }
    
    $format = '';
    // get the keyword
    if (isset($_GET['format'])) {
        // cast var as int
        $format = (string) $_GET['format'];
    }
    
    $op = '';
    if (isset($_GET['op'])) {
        // cast var as int
        $run = (string) $_GET['op'];
    }
    if ($run == 'insert') {
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
        $sql = "insert into releasemedium (rid, mid, name, format) values ($rid, $mid, '$name', '$format')";
        echo $sql;
        //echo $sql;
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt, OCI_DEFAULT);
        $commit = "commit write nowait";
        $stmt = oci_parse($conn, $commit);
        oci_execute($stmt, OCI_DEFAULT);
    }
    if ($run == 'delete') {
        $sql = "delete from releasemedium where ";
        $query = '';
        $flag = 0;
        if ($rid <> '') {
            if ($flag == 1) {
                $query = "$query and ";
            }
            $flag = 1;
            $query =  "$query rid = $rid ";
        }
        if ($name <> '') {
            if ($flag == 1) {
                $query = "$query and ";
            }
            $flag = 1;
            $query =  "$query name = '$name' ";
        }
        if ($mid <> '') {
            if ($flag == 1) {
                $query = "$query and ";
            }
            $flag = 1;
            $query =  "$query mid = $mid ";
        }
        if ($format <> '') {
            if ($flag == 1) {
                $query = "$query and ";
            }
            $flag = 1;
            $query =  "$query format = '$format' ";
        }
        $sql = "$sql $query";
        echo $sql;
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
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt, OCI_DEFAULT);
        $commit = "commit write nowait";
        $stmt = oci_parse($conn, $commit);
        oci_execute($stmt, OCI_DEFAULT);
    }

    ?>


<form action="<?php $_SERVER['PHP_SELF']?>" method="get" name="search_form" target="_self" id="f" align = "left">
<br>
RID:
<input type="text" name="rid" class="kw" size="10" maxlength="100" style="color:#000"/> <br> <br>
MID:
<input type="text" name="mid" class="kw" size="10" maxlength="100" style="color:#000"/> <br> <br>
NAME:
<input type="text" name="name" class="kw" size="10" maxlength="100" style="color:#000"/> <br> <br>
FORMAT:
<input type="text" name="format" class="kw" size="10" maxlength="100" style="color:#000"/> <br> <br>
Type of Operation:
<input type="radio" name="op" value="insert" checked>INSERT
<input type="radio" name="op" value="delete">DELETE
<br> <br>
<input name="run" type="submit" class="sb" value="run" style="color:#000"/>
<br>

</form>
                    <?php
                        require_once('footer.php');
                    ?>

                </div>

            </div>

        </div>
    </body>
</html>
