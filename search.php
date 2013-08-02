<?php
session_start();
if($_SESSION['username']){
	echo '<div class="logheader"><p class="welcome">Welcome back, '.$_SESSION['username'].'&nbsp;&nbsp;<a href="admin.php">Manage Page</a>&nbsp;&nbsp;<a href="profile.php">Prifiles</a></p></div>';
} else{

}
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Blogs</title>
	<link rel="stylesheet" type="text/css" href="css/page.css">		
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
	<div class="main">

<?php
	require 'db.inc.php';

	if($search = (isset($_GET['search'])) ? $_GET['search'] : ''){
		$query   = new MongoRegex("/$search/");  
    	$cursor = $collection->find( array( '$or' => array( array('text' => $query), array('title' => $query) ) ) );  

		if ($cursor->count() == 0) {
			echo '<div class="contents"><strong>No articles found that match the search terms.</strong></div>';
		} else {
			foreach($cursor as $key => $value){ 
				echo '<div class="contents">';
				echo '<h3><a href="blog.php?id='.$value['_id'].'">'.$value['title'].'</a></h3>';
				echo '<p><span class="small">author:'.$value['author'].'&nbsp;type:'.$value['type'].'&nbsp;created:'.date('Y-m-d H:i:s', $value['created']->sec).'</span></p>';
				echo '<div id="main">'.$value['text'].'</div>';
				echo '</div>';
			}
		}
	}
	if($type = (isset($_GET['type'])) ? $_GET['type'] : ''){		
		$query   = new MongoRegex("/$type/");  
    	$cursor = $collection->find( array('type' =>  $query ));
    	
		if ($cursor->count() == 0) {
			echo '<div class="contents"><strong>No articles found that match the search terms.</strong></div>';
		} else {
			foreach($cursor as $key => $value){ 
				echo ' <div class="contents"> ';
	            echo ' <h3><a href="blog.php?id='.$value['_id'].'"> ' . $value['title'] . '</a></h3>';
	            echo '<p><span class="small">type:<a href="search.php?type='.$value['type'].'">' . $value['type'] . '</a>&nbsp;&nbsp; created:' . date('Y-m-d H:i:s', $value['created']->sec) .'</span></p>';
	            echo ' <div> ' . $value['text'] . '</div>' ;
	            echo '</div> ';
			}
		}
	}
	if($label = (isset($_GET['label'])) ? $_GET['label'] : ''){		
		$query   = new MongoRegex("/$label/");  
    	$cursor = $collection->find( array('label' =>  $query ));
    	
		if ($cursor->count() == 0) {
			echo '<div class="contents"><strong>No articles found that match the search terms.</strong></div>';
		} else {
			foreach($cursor as $key => $value){ 
				echo ' <div class="contents"> ';
	            echo ' <h3><a href="blog.php?id='.$value['_id'].'"> ' . $value['title'] . '</a></h3>';
	            echo '<p><span class="small">type:<a href="search.php?type='.$value['type'].'">' . $value['type'] . '</a>&nbsp;&nbsp; created:' . date('Y-m-d H:i:s', $value['created']->sec) .'</span></p>';
	            echo ' <p> ' . $value['text'] . '</p>' ;
	            echo '</div> ';
			}
		}
	}
	echo '</div>';
	include 'sidebar.php';
	include 'foot.inc.php';
?>