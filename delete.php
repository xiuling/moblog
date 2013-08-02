<?php   
	session_start();
   if($_SESSION['username']){
        echo '<div class="logheader"><p class="welcome">Welcome back, '.$_SESSION['username'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php">Manage Blog</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="change.php">Profiles</a></p></div>';
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Blogs</title>
	<link rel="stylesheet" type="text/css" href="css/page.css" />
	<script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script>
</head>
<body>
<div id="wrap">
<div id="head">
    <div id="banner"><h1><a href="index.php">Blogs</a></h1></div>
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
			<div class="clear"></div>
		</form>
	</div>
</div>	
<div class="main">
	<div class="contents">
<?php
	include 'db.inc.php';
    
	$result = $collection->remove(array('_id' => new MongoId($_GET['id']))); 

?>
	<p> Your blog has been deleted.</p>
	<?php
	header ('Refresh: 1; URL= admin.php');
	echo ' <p> You will be redirected to your original page request. </p> ';
            echo ' <p> If your browser doesn\'t redirect you properly ' . 
                'automatically, <a href="admin.php" >click here </a> . </p> ';
?>


<?php   
	}else{
    	header ('Refresh: 1; URL= login.php');
		echo ' <p> You have not logged in. You will be redirected to login page. </p> ';
            echo ' <p> If your browser doesn\'t redirect you properly ' . 
                'automatically, <a href="login.php" >click here </a> . </p> ';
    }
?>

	</div>
</div>
	<div class="clear"></div>
</div>
</body>
</html>