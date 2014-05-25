    
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
<TD width = "80px "style="color:#C0C0C0"><a href="datas_modify3.php" style="color:#FFFFFF">GENRE</TD>
<TD width = "150px "style="color:#C0C0C0"><a href="datas_modify4.php" style="color:#C0C0C0">RECORDING</TD>
<TD width = "150px "style="color:#C0C0C0"><a href="datas_modify5.php" style="color:#C0C0C0">RELEASEMEDIUM</TD>
<TD width = "80px "style="color:#C0C0C0"><a href="datas_modify6.php" style="color:#C0C0C0">TRACK</TD>
</TD>
</TABLE>

<?php
    $id = '';
    // get the keyword
    if (isset($_GET['id'])) {
        // cast var as int
        $id = (string) $_GET['id'];
    }
    
    $name = '';
    // get the keyword
    if (isset($_GET['gid'])) {
        // cast var as int
        $name = (string) $_GET['gid'];
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
        $sql = "insert into genre (id, name) values ($id, '$name')";
        echo $sql;
        //echo $sql;
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt, OCI_DEFAULT);
        $commit = "commit write nowait";
        $stmt = oci_parse($conn, $commit);
        oci_execute($stmt, OCI_DEFAULT);
    }
    if ($run == 'delete') {
        $sql = "delete from genre where ";
        $query = '';
        $flag = 0;
        if ($id <> '') {
            if ($flag == 1) {
                $query = "$query and ";
            }
            $flag = 1;
            $query =  "$query id = $id ";
        }
        if ($name <> '') {
            if ($flag == 1) {
                $query = "$query and ";
            }
            $flag = 1;
            $query =  "$query name = '$name' ";
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
ID:
<input type="text" name="id" class="kw" size="10" maxlength="100" style="color:#000"/> <br> <br>
NAME:
<input type="text" name="gid" class="kw" size="10" maxlength="100" style="color:#000"/> <br> <br>
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
