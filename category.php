<?php
	session_start();
    if($_SESSION['username']){
        echo '<div class="logheader"><p class="Welcome">Welcome back, '.$_SESSION['username'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php">Manage Blog</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="profile.php">Profiles</a></p></div>';
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Profiles</title>
	<link rel="stylesheet" type="text/css" href="css/page.css">
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
		</form>
	</div>
	<div class="clear"></div>
</div>	


<?php
    include 'db.inc.php';

    if (isset($_GET['error']) && $_GET['error'] != '') {
		echo ' <div id="error"> ' . $_GET['error'] . ' </div> ';
	}
?>
<div class="contents" >
	<h3>Type</h3>
	<?php
		$types = $db->category->find(array("category" => "type"));
		if($types !== NULL){
			foreach ($types as $key => $value) {
			echo $value['name'].', ';
			}
		}
		
	?>
	<form action="update.php?action=addtype" method="post">
		<input type="text" name="name" size="20" /><input type="submit" value="Add" />
	</form> 
	<h3>Label</h3>
	<?php
		$labels = $db->category->find(array("category" => "label"));
		if($labels !== NULL){
			foreach ($labels as $key => $value) {
			echo $value['name'].', ';
			}
		}
		
	?>
	<form action="update.php?action=addlabel" method="post">
		<input type="text" name="name" size="20" /><input type="submit" value="Add" />
	</form> 

<?php
} else{
	 echo ' <p> You have not logged in. Please <a href="login.php" >click here to log in</a></p> ';
}
?>
</div>
	
<?php include 'foot.inc.php'; ?>
