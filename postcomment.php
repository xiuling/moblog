<?php
	require 'db.inc.php';

	include 'header.inc.php';

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
				echo '<div class="main">';
				echo '<p> Done!</p>';
				header ('Refresh: 1; URL= blog.php?id='.$id);
				echo ' <p> You will be redirected to your original page request. </p> ';
			    echo ' <p> If your browser doesn\'t redirect you properly ' . 
			                'automatically, <a href="blog.php?id='.$id.'" >click here </a> . </p> ';
			    echo '</div>';
	    	}
	    
	    
    }else{
    	header('Location: blog.php?id='.$id.'&name='.$name.' &email='.$email.'&content='.$content.'
					&error='.join($error, urlencode('<br />')));
    }

    include 'foot.inc.php';
?>