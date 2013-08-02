<?php   
	session_start();
   if($_SESSION['username']){
        echo '<div class="logheader"><p class="welcome">Welcome back, '.$_SESSION['username'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php">Manage Blog</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="change.php">Profiles</a></p></div>';
    }
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Blogs</title>
	<link rel="stylesheet" type="text/css" href="css/page.css" />
	<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
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
	require 'db.inc.php';

	echo '<div class="contents">';

	$name = (isset($_POST['name'])) ? trim($_POST['name']) : '';
	$email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
	$content = (isset($_POST['content'])) ? $_POST['content'] : '';
	$id = (isset($_POST['_id'])) ? $_POST['_id'] : '';

	$error = array();

	if (empty($name)) {
		$error[] = urlencode('Please enter your name.');
	}
	$email = isset($email) ? trim($email) : '';
	if (empty($email)) {
		$error[] = urlencode('Please enter your email.');
	}
	$content = isset($_POST['content']) ? trim($_POST['content']) : '';
	if (empty($content)) {
		$error[] = urlencode('Please enter the content.');
	}

	if (empty($error)) {
		$post = array('name' => $name, 'email' => $email, 'content' => $content, 
					  'created' => new MongoDate() ); 

   		$result = $collection->update( array('_id' => new MongoId($id)),  array('$addToSet' => array('comments' =>  $post)) );
			
			if($result){				
				echo '<p> Done!</p>';
				//header ('Refresh: 1; URL= blog.php?id='.$id);
				echo ' <p> You will be redirected to your original page. </p> ';
			    echo ' <p> If your browser doesn\'t redirect you properly ' . 
			                'automatically, <a href="blog.php?id='.$id.'" >click here </a> . </p> ';
	    	}	    
    }else{
    	header('Location: blog.php?id='.$id.'&name='.$name.' &email='.$email.'&content='.$content.'
					&error='.join($error, urlencode('<br />')));
    }
	echo '</div>';

    include 'foot.inc.php';
?>