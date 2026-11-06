<?php 

session_start();

include("util.php"); 

?>

<!DOCTYPE html>
<HTML>
<HEAD>
<TITLE>Titan Dashboard</TITLE>

<?php include("bootstrap.php"); ?>

<?php

$op = "inbox";

if (isset($_GET['op'])) {
    $op = $_GET['op'];

    if ($op == 'login') {
        $_SESSION['uid'] = $_POST['uid'];
    }
    else if ($op == 'logout') {
        unset($_SESSION['uid']);
    }
}

if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];  
    $uname = getName($db, $uid);
}

?>

</HEAD>

<BODY>

<DIV class="container">

<!-- banner -->
<DIV class="row">
<DIV class="col-9" style="font-size: 30px; padding: 10px;">Welcome <?php print $uname; ?>!</DIV>
<DIV class="col-3" style="position: relative; padding: 20px;"> 
<?php
    if (isset($_SESSION['uid'])) {
        showLogoutForm();
    }
    else {
        showLoginForm();
    }
?>
</DIV>
</DIV>

<!-- navbar: menu -->
<DIV class="row">

<!-- href="?op=inbox" access current html/php file -->
<DIV class="col-3 menuItem"><A href="?op=inbox">Inbox</A></DIV>
<DIV class="col-3 menuItem"><A href="dashboard.php?op=compose">Compose</A></DIV>
<DIV class="col-3 menuItem"><A href="?op=history">History</A></DIV>
<DIV class="col-3 menuItem"><A href="?op=users">View Users</A></DIV>

</DIV>

<!-- actual content for each menu item -->
<DIV class="row main">

<DIV class="col-12">

<?php

switch ($op) {
    case "add":
        addTitan($_POST);
        break;
    case "remove":
        removeTitans($db, $_POST['cb']);
        break;
    case "compose":
        showComposeForm($db, $uid);
        break;
    case "send":
        sendMessage($db, $uid, $_POST);
        break;
    case "inbox":
        showInbox($db, $uid);
        break;
    case "message":
        if (isset($_GET['mid'])) {
            showMessage($db, $_GET['mid']);
        }
        break;
    case "history":
        showHistoryForm($db, $uid);
        if (isset($_POST['ddlTo'])) {
            showHistory($db, $uid, $_POST['ddlTo']);
        }
        break;
    case "users":
        showTitans($db);
        break;
    default:
        break;
}

?>

</DIV> <!-- col-12 -->

</DIV> <!-- row -->

</DIV> <!-- container -->

</BODY>
</HTML>
