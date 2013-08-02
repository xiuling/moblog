<?php
	session_start();
	include 'db.inc.php';

	switch ($_GET['action']) {
		case 'add':
			$error = array();
			$title = isset($_POST['title']) ? trim($_POST['title']) : '';
			if (empty($title)) {
				$error[] = urlencode('Please enter the title.');
			}
			$type = isset($_POST['type']) ? trim($_POST['type']) : '';
			if (empty($type)) {
				$error[] = urlencode('Please enter a type.');
			}
			$text = isset($_POST['text']) ? trim($_POST['text']) : '';
			if (empty($text)) {
				$error[] = urlencode('Please enter the content.');
			}
			//$label = isset($_POST['label']) ? $_POST['label'] : '';
			$label = array();
			$labels = isset($_POST['label']) ? trim($_POST['label']) : '';
			$label = explode(' ', $labels);


			if (empty($label)) {
				$error[] = urlencode('Please enter the label.');
			}
			if (empty($error)) {
				$post = array('title' => $title, 'type' => $type, 'text' => $text,  'label' =>  $label,  
					 'author' => $_SESSION['username'], 'created' => new MongoDate(), 'status' => 1 ); 

   				$result = $collection->insert($post);
			} else {
				header('Location:contents.php?action=add&title='.$title.'&type='.$type.'&text='.$text.'&label='.$_POST['label'].' 
					&error='.join($error, urlencode('<br />')));
			}
		break;
		case 'edit':
			$error = array();
			$title = isset($_POST['title']) ? trim($_POST['title']) : '';
			if (empty($title)) {
				$error[] = urlencode('Please enter the title.');
			}
			$type = isset($_POST['type']) ? trim($_POST['type']) : '';
			if (empty($type)) {
				$error[] = urlencode('Please enter a type.');
			}
			$text = isset($_POST['text']) ? trim($_POST['text']) : '';
			if (empty($text)) {
				$error[] = urlencode('Please enter the text.');
			}
			//$label = isset($_POST['label']) ? $_POST['label'] : '';
			$label = array();
			$labels = isset($_POST['label']) ? trim($_POST['label']) : '';
			$label = explode(' ', $labels);
			if (empty($label)) {
				$error[] = urlencode('Please enter the label.');
			}

			if (empty($error)) {				
				//update
				$post = array('title' => $title, 'type' => $type,'text' => $text, 'label' => $label, 
					 'modified' => new MongoDate(), 'status' => 1 ); 

   				$result = $collection->update( array('_id' => new MongoId($_POST['_id'])) , array('$set' => $post) , array("upsert" => true));
			} else {
				header('Location:contents.php?action=edit&id=' . $_POST['_id'] .
					'&error=' . join($error, urlencode('<br />')));
			}
		break;
		case 'saveDraft':
			$title = isset($_POST['title']) ? trim($_POST['title']) : '';
			if (empty($title)) {
				$error[] = urlencode('Please enter the title.');
			}
			$type = isset($_POST['type']) ? trim($_POST['type']) : '';
			$text = isset($_POST['text']) ? trim($_POST['text']) : '';
			//$label = isset($_POST['label']) ? $_POST['label'] : '';
			$label = array();
			$labels = isset($_POST['label']) ? trim($_POST['label']) : '';
			$label = explode(' ', $labels);

			if($_POST['_id']){
				$post = array('title' => $title, 'text' => $text, 'type' => $type, 'label' => $label, 
					 'modified' => new MongoDate(), 'status' => 0, array("upsert" => true) ); 
   				$result = $collection->update( array('_id' => new MongoId($_POST['_id'])) , array('$set' => $post), array("upsert" => true));
				
			}else{				
				$post = array('title' => $title, 'type' => $type, 'text' => $text, 'label' => $label, 
					  'author' => $_SESSION['username'], 'created' => new MongoDate(), 'status' => 0 ); 
   				$result = $collection->insert($post);
			}
		break;
		
	}
	
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
<p> Done!</p>
<?php
	header ('Refresh: 1; URL= admin.php');
	echo ' <p> You will be redirected to your original page request. </p> ';
    echo ' <p> If your browser doesn\'t redirect you properly ' . 
                'automatically, <a href="admin.php" >click here </a> . </p> ';
?>
	</div>
</div>
<div class="clear"></div>
</div>
</body>
</html>