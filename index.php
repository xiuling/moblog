<?php
session_start();
if($_SESSION['username']){
	echo '<div class="logheader"><p class="welcome">Welcome back, '.$_SESSION['username'].'&nbsp;&nbsp;<a href="admin.php">Manage Page</a>&nbsp;&nbsp;<a href="profile.php">Prifiles</a></p></div>';
} 
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Blogs</title>
	<link rel="stylesheet" type="text/css" href="css/page.css" />
	<style type="text/css">
		body#intro #home a{color: #333; padding-bottom: 5px; border-color: #727377; background-color: #EEE;}
	</style>
</head>
<body id="intro">
<div id="wrap">
	<div id="head">
		<div id="banner"><h1><a href="index.php">Blogs</a></h1></div>
		<div id="search">
			<form method="get" action="search.php">
				<label for="search">Search</label>
				<?php
					echo '<input type="text" name="search" ';
					if(isset($_GET['search'])){
						echo 'value="' . htmlspecialchars($_GET['search']) . '"';
					}
					echo '/>';
				?>
				<input type="submit" value="Search" />
			</form>
		</div>
		<div class="clear"></div>
	</div>
	<div id="nav">
		<ul>
			<li id="home"><a href="index.php">Home</a></li>
			<li id="about"><a href="about.php">About</a></li>
		</ul>
	</div>


<?php
include 'db.inc.php';

echo '<div class="main">';

$cursor = $collection->find(array('type' => array('$ne' => 'about')));
$cursor->sort(array('_id' => -1));

foreach ($cursor as $key => $value) {
	if($value['title'] !== NULL){
		echo '<div class="contents">';
		echo '<h3><a href="blog.php?id='.$value['_id'].'">'.$value['title'].'</a></h3>';
		echo '<p><span class="small">author:'.$value['author'].'&nbsp;type:'.$value['type'].'&nbsp;created:'.date('Y-m-d H:i:s', $value['created']->sec).'</span></p>';
		echo '<div id="main">'.$value['text'].'</div>';
		echo '</div>';
	}
}
echo '</div>';
?>
<div id="sidebar">
	<div class="category side">
		<h4>Category</h4>
		<?php 
			include 'db.inc.php';
			$types = $db->command(
				array(
					"distinct" => "posts",
					"key" => "type",
					"query" => array("type" => array('$ne' => 'about'))
					)
				);
			foreach ($types['values'] as $type) {
				echo '<p><a href="search.php?type='.$type.'">'.$type.'</a></p>';
			}

		?>
	</div>
	<div class="label side">
		<h4>Label</h4>
		<?php 
			$labels = $db->command(
				array(
					"distinct" => "posts",
					"key" => "label",
					"query" => array("label" => array('$ne' => 'about'))
					)
				);
			foreach ($labels['values'] as $label) {
				echo '<p><a href="search.php?label='.$label.'">'.$label.'</a></p>';
			}
		?>
	</div>
	<div class="function side">
		<h4>Function</h4>
		<p><a href="login.php">Log in</a></p>
		<p><a href="logout.php">Log out</a></p>
	</div>
</div>

<div class="clear"></div>
<div id="footer">&copy; 2013</div>
</div>
</body>
</html>