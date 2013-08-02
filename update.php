<?php
    // include 'adminheader.inc.php';
    session_start();
    if($_SESSION['username']){
        echo '<div class="logheader">Welcome back, '.$_SESSION['username'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php">Manage Blog</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="change.php">Profiles</a></div>';
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Blogs</title>
<!--    <link rel="stylesheet" type="text/css" href="../css/base.css" />  -->
    <link rel="stylesheet" type="text/css" href="../css/admincommon.css" />
    <link rel="stylesheet" type="text/css" href="../css/page.css" />
    <script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script>
</head>
<body>
    <div id="nav"><a href="index.php">Blogs</a></div>
    <div id="search">
        <form method="get" action="search.php">
            <label for="search">Search</label>
<?php
    echo '<input type="text" name="search" ';
    if (isset($_GET['search'])) {
        echo ' value="' . htmlspecialchars($_GET['search']) . '" ';
    }
    echo '/>';
?>
            <input type="submit" value="Search" />
        </form>
    </div>
    <div id="wrap">

<?php
	include 'db.inc.php';

	switch ($_GET['action']) {
		case 'changepass':
			$cursor = $db->users->findOne(array("username" => $_SESSION['username']));

		    if ($cursor) {
		        $password = $cursor['password'];
		    }

			$error = array();
			$oldPass = isset($_POST['oldPass']) ? trim($_POST['oldPass']) : '';
			if (empty($oldPass)) {
				$error[] = urlencode('Please enter the oldPass.');
			}
			if($oldPass!==$password){
				$error[] = urlencode('Old password is wrong.');
			}
			$newPass1 = isset($_POST['newPass1']) ? trim($_POST['newPass1']) : '';
			if (empty($newPass1)) {
				$error[] = urlencode('Please enter the newPass.');
			}
			$newPass2 = isset($_POST['newPass2']) ? trim($_POST['newPass2']) : '';
			if (empty($newPass2)) {
				$error[] = urlencode('Please enter the newPass again.');
			}
			if($newPass1!==$newPass2){
				$error[] = urlencode('The two new password are not the same.');
			}

			if(empty($error)){
				//$post = array('title' => $title, 'type' => $type,'text' => $text, 'label' => $label, 
					 //'modified' => new MongoDate(), 'status' => 1 ); 

   				$result = $collection->update( array('username' => $_SESSION['username']), array('password' => $newPass2) );
				if($result){
					echo 'Password has been changed.';
					header ('Refresh: 1; URL= change.php');
				}
			}else {
				header('Location:change.php?action=changepass'.' 
					&error='.join($error, urlencode('<br />')));
			}
			break;
		case 'changeabout':
			$title = trim($_POST['title']);
			$text = trim($_POST['text']);
			if($_GET['id']){
				$post = array('title' => $title, 'text' => $text, 'modified' => new MongoDate(), 'status' => 1 ); 
   				$result = $collection->update( array('_id' => new MongoId($_GET['id'])) , array('$set' => $post) , array("upsert" => true));
				
			}else{
				$post = array('title' => $title, 'type' => 'about', 'label' => array('about'), 'text' => $text, 
					 'author' => $_SESSION['username'], 'created' => new MongoDate(), 'status' => 1 ); 

   				$result = $collection->insert($post);
			}
			if($result){
				header ('Refresh: 0; URL= about.php');
			}
			break;
		case 'adduser':
			$error = array();
			$username = isset($_POST['username']) ? trim($_POST['username']) : '';
			if (empty($username)) {
				$error[] = urlencode('Please enter the username.');
			}
			$password = isset($_POST['password']) ? trim($_POST['password']) : '';
			if (empty($password)) {
				$error[] = urlencode('Please enter the password.');
			}
			$authCode = isset($_POST['authCode']) ? trim($_POST['authCode']) : '';
			if(empty($error)){
				$post = array('username' => $username, 'password' => $password, 'authCode' => $authCode);
				$result = $db->users->insert($post);
				if($result){
						echo 'The New User has been added.';
						header ('Refresh: 1; URL= change.php');
				}
			} 
	}
?>
<?php
    // include 'adminfoot.inc.php';
    }else{
        header ('Refresh: 1; URL= login.php');
        echo ' <p> You have not logged in. You will be redirected to login page. </p> ';
            echo ' <p> If your browser doesn\'t redirect you properly ' . 
                'automatically, <a href="login.php" >click here </a> . </p> ';
    }
?>

    </div>
    <div id="foot" style="clear:both;">
    </div>
</body>
</html>