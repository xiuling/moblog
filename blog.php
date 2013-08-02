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
		body#intro #home a{color: #333; padding-bottom: 5px; border-color: #727377; background-color: #FFF;}
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

$name = (isset($_GET['name'])) ? trim($_GET['name']) : '';
$email = (isset($_GET['email'])) ? trim($_GET['email']) : '';
$content = (isset($_GET['content'])) ? trim($_GET['content']) : '';

$cursor = $collection->findOne(array("_id" => new MongoId($_GET['id'])));

?>

<div class="main">	
	<div class="contents">
		<h3><a href="blog.php?id=<?php echo $cursor['_id']; ?>"><?php echo $cursor['title']; ?></a></h3>
		<p class="small">Author：<?php echo $cursor['author'] ;?> &nbsp;type：<?php echo $cursor['type'] ;?> Created：<?php echo date('Y-m-d H:i:s', $cursor['created']->sec); ?></p>
		<div><?php echo $cursor['text']; ?></div>
	</div>
	
	<div class="comments">
	<?php 
		if($cursor['comments'] !== NULL){
			echo '<h3>comments here:</h3>';
			foreach ($cursor['comments'] as $key => $value) {
				echo '<div class="eachComment" style="margin-bottom: 10px;">';
	                echo '<p><span class="small">author:&nbsp;' . $value['name'] .'&nbsp;&nbsp; mail:&nbsp;' . $value['email'] . '&nbsp;&nbsp; created:&nbsp;' . date('Y-m-d H:i:s', $value['created']->sec) . '</span></p>';
	                echo ' <div> ' . $value['content'] . ' </div> </div>';				
			}
		}else{
			echo '<p>There is no comments yet.</p>';
			echo '<p>You can give a comment.</p>';
		}

	?>
	</div>
	<?php
		if(isset($_GET['error']) && $_GET['error']!== ''){
			echo '<div id="error">'.$_GET['error'].'</div>';
		}
	?>

	<div class="addComments">
		<form action="postcomment.php" method="post">
			<input type="hidden" name="_id" value="<?php echo $_GET['id']; ?>" />
			<table>
				<tr>
				 	<td>Name</td>
				 	<td><input type="text" name="name" size="30" value="<?php echo $name; ?>" /></td>
				</tr>
				<tr>
				 	<td>Email</td>
				 	<td><input type="text" name="email" size="30" value="<?php echo $email; ?>" /></td>
				</tr>
				<tr>
				 	<td>Content</td>
				 	<td><textarea name="content" rows="20" cols="50"><?php echo $content; ?></textarea></td>
				</tr>
				<tr>
				 	<td cols="2"><input type="submit" value="Submit Comment" /></td>
				</tr>
			</table>
		</form>
	</div>
</div>

<?php 
include 'sidebar.php';
include 'foot.inc.php';
?>
