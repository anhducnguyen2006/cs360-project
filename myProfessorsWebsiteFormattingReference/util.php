<?php
session_start();

include_once("db_connect.php");

// special hashmap variables: $_POST, $_GET, $_SESSION

// prints a String
function debug($str) {
    echo "<H3>$str</H3>\n";
}


// prints an array or map
function debug2($array) {
    echo "<PRE>";
    print_r($array);
    echo "</PRE>\n";
}


// shows login form 
// action is performed in dashboard.php
function showLoginForm() {
?>
    <DIV class="login">
    <FORM name='fmLogin' method='POST' action='dashboard.php?op=login'>
    <INPUT type='text' name='uid' size='4' placeholder='user id' />
    <INPUT type='submit' value='Log in' />
    </FORM>
    </DIV>
<?php
}


// shows logout form, no paramaters are needed
// action is performed in dashboard.php
function showLogoutForm() {
?>
    <DIV class="logout">
    <FORM name='fmLogout' method='POST' action='dashboard.php?op=logout'>
    <INPUT type='submit' value='Logout' />
    </FORM>
    </DIV>
<?php
}


// TODO
// send a message
// insert a new row to message table
// $mailData is $_POST (hashmap)
function sendMessage($db, $sid, $mailData) {

    //debug2($mailData);
    
    $rid     = $mailData['ddlTo'];
    $subject = $mailData['tfSubject'];
    $content = $mailData['taMsg'];

    // TODO
    $query = "INSERT INTO msg(sid, rid, subject, content, sdate)
			VALUE(?,   ?,   ?,       ?,       NOW())"; 

    $sql = $db->prepare($query);

    $params = array($sid, $rid, $subject, $content);
    debug2($params);

    $sql->execute($params);

    // reloads the page in 2 seconds and displays user's inbox
    header("refresh:2;url=dashboard.php?op=inbox");

    if ($sql->rowCount() == 1) { // if one row is affected
        printf("<P>successfully sent message to $rid</P>\n");
    }
    else {
        printf("<P>failed to send message to $rid</P>\n");
    }

}


// TODO
// show inbox of the given user
function showInbox($db, $uid) {

    // TODO: create a query to retrieve all messages $uid received
    //       prepare the query, and execute it
    //       save the resulting table to $rows

	$query = "SELECT * FROM  msg JOIN t1 ON sid=id  WHERE rid=?";

    $sql = $db->prepare($query);
    $sql->execute([$uid]);


    $rows = $sql->fetchAll();

    // debug2($rows);

    printf("<H4>You have %d messages</H4>\n", count($rows)); 

?>
<TABLE cellspacing="0" cellpadding="5" style="width: 100%;"> 

<TR><TH width="10%">From</TH><TH width="20">Received</TH><TH width="50%">Subject</TH><TH width="20%">Read</TH></TR>


<?php

    // TODO: display messages in foreach loop

    foreach ($rows as $row) {
	    $sid = $row['sid'];
	    $subject = $row['subject'];
	    $sdate = $row['sdate'];
	    $rdate = $row['rdate'];
	    //  $sname = getName($db, $sid);
	    $sname = $row['name'];

	    printf("<TR><TD>%s</TD><TD>%s</TD><TD>%s</TD></TR>\n", $sname, $sdate, $subject, $rdate);
    }    
    

    print("</TABLE>\n");
}


// TODO
// shows one message
function showMessage($db, $mid) {

    // TODO: write a query to retrieve netire message based on $mid
	// prepare the query
	// execute the query
	// save result to $msg
	
?>	
    <TABLE border="0" cellpadding="10" style="width: 60%;">

    <TR>
    <TD>From</TD>
    <TD><?php print $name; ?></TD>
    </TD>

    <TR>
    <TD>Subject</TH>
    <TD><?php print $subject; ?> </TD>
    </TR>

    <TR>
    <TD>Message</TH>
    <TD><TEXTAREA name="taMsg" cols="50" rows="5" class="width100" READONLY>
	<?php print $content; ?>
	 </TEXTAREA></TD>
    </TR>

    </TABLE>
	

<?php
}


// TODO
// displays history form: user chooses a user from the  drop down list 
function showHistoryForm($db, $uid) {






}


// TODO
// displays history with selected user (friend)
// - gets all messages between $uid and $fid order by sdate
// - displays them in a reasonable format 
function showHistory($db, $uid, $fid) {


}


// displays user management table: add, remove, view
// ** slightly modified from viewTitans.php
function showTitans($db) {
?>

<TABLE> 
<TR>
<TH>ID</TH>
<TH>Name</TH>
<TH>Planet</TH>
<TH>Power</TH>
<TH>Action</TH>
</TR>

<?php

    // add a row to add a new Titan
    showAddForm();

    // displays all Titans

    $query = "SELECT t1.id, name, planet, power
              FROM   t1 LEFT OUTER JOIN t2 ON t1.id=t2.id";
    $res = $db->query($query);

    if ($res != FALSE) {
        $nRows = $res->rowCount();
        $nCols = $res->columnCount();

        //printf("<H3>rows = %d cols = %d</H3>\n", $nRows, $nCols);    
        // iterate through the result table
        // and print one HTML TABLE row per result row

        print("<H3>$nRows users</H3>\n");
        print("<FORM name='remove' method='POST' action='dashboard.php?op=remove'>\n");

        while ($row = $res->fetch()) {
            $id     = $row['id'];
            $name   = $row['name'];
            $planet = $row['planet'];
            $power  = $row['power'];

            print("<TR><TD>$id</TD> <TD>$name</TD> <TD>$planet</TD> <TD>$power</TD>");
            addCB($id);
            print("</TR>\n");
        }
    }

    print "<TR><TD colspan=4></TD> <TD style='align: right;'><INPUT TYPE='submit' VALUE='remove' /></TD></TR>\n";
    print "</TABLE>\n";
}


function getName($db, $id) {
    $query = "SELECT name 
              FROM   t1
              WHERE  id=?";

    $sql = $db->prepare($query);
    $sql->execute([$id]);

    $result = $sql->fetch();

    if ($result) {
        return $result['name'];
    }
    else {
        return "Unknown";
    }
}

function genTitanDDL($db, $sid) {

    $query = "SELECT * FROM t1";

    $sql = $db->prepare($query);
    $sql->execute();

    $rows = $sql->fetchAll();
    //debug2($rows);

    print "<SELECT name='ddlTo' class='width100'>\n";

    foreach ($rows as $row) {
        $id   = $row['id'];
        $name = $row['name'];

        if ($id != $sid) {
            print "<OPTION value='$id'>$name   </OPTION>\n";
        }
    }

    print "</SELECT>\n";
}

// $sid: sender's id
function showComposeForm($db, $sid) {

    $name = getName($db, $sid);

?>
    <FORM name="fmCompose" method="POST" action='?op=send'>

    <TABLE border="0" cellpadding="10" style="width: 60%;">

    <TR>
    <TD><INPUT type="submit" value="Send" /></TD>
    <TD>From: <?php print $name; ?></TD>
    </TR>

    <TR>
    <TD>To</TH>
    <TD><?php genTitanDDL($db, $sid); ?> </TD>
    </TR>

    <TR>
    <TD>Subject</TH>
    <TD><INPUT type='text' name='tfSubject' class="width100" /></TD>
    </TR>

    <TR>
    <TD>Message</TH>
    <TD><TEXTAREA name="taMsg" cols="50" rows="5" class="width100"> </TEXTAREA></TD>
    </TR>

    </TABLE>
    </FORM>
<?php
}


// generate one table row with 5 columns
// columns will contain text input fields
function showAddForm() {

    print "<FORM name='fmAdd' method='POST' action='dashboard.php?op=add'>\n";
    print "<TR>\n";
    print "<TD><INPUT TYPE='number' NAME='id'    /></TD>\n";
    print "<TD><INPUT TYPE='text'   NAME='name'  /></TD>\n";
    print "<TD><INPUT TYPE='text'   NAME='planet'/></TD>\n";
    print "<TD><INPUT TYPE='text'   NAME='power' /></TD>\n";
    print "<TD><INPUT TYPE='submit' VALUE='add'  /></TD>\n";
    print "</TR>\n";
    print "</FORM>\n";
}


// adds one Titan
// note: $db is not passed as parameter, it's a global variable
function addTitan($input) {

    global $db;

    $id     = $input['id'];
    $name   = $input['name'];
    $planet = $input['planet'];
    $power  = $input['power'];

    $sql1 = "INSERT INTO t1 VALUE($id, '$name')"; 
    $sql2 = "INSERT INTO t2 VALUE($id, '$planet', '$power')"; 

    $res1 = $db->query($sql1);
    $res2 = $db->query($sql2);

    header("refresh:2;url=dashboard.php?op=users");

    if ($res1 && $res2) {
        print("<P>$name added to the database</P>");    
    }
    else {
        print("<P>$name was NOT added to the database</P>");    
    }

}


// removes all Titans based on the ids given in the array
function removeTitans($db, $ids) {

    //debug2($ids);

    $count = 0;
    foreach ($ids as $id) {
        $query1 = "DELETE FROM t1 WHERE id=?";
        $query2 = "DELETE FROM t2 WHERE id=?";

        $sql1 = $db->prepare($query1);
        $sql2 = $db->prepare($query2);

        $sql1->execute([$id]);
        $sql2->execute([$id]);

        if ($sql1->rowCount() == 1 && $sql2->rowCount() == 1) {
            ++$count;
        }
    }

    header("refresh:2;url=dashboard.php?op=users");
    print("<P>Removed $count titans</P>\n");

    $diff = count($ids) - $count;
    if ($diff > 0) {
        print ("<P>Unable to remove $diff titans.</P>\n");
    }
}

// adds a checkbox for one Titan
function addCB($id) {
    print "<TD><INPUT TYPE='checkbox' name='cb[]' value='$id' /> </TD>\n";
}

?>


