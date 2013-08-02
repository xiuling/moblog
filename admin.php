<?php
    session_start();
    if($_SESSION['username']){
        echo '<div class="logheader"><p class="welcome">Welcome back, '.$_SESSION['username'].'&nbsp;&nbsp;<a href="admin.php">Manage Page</a>&nbsp;&nbsp;<a href="profile.php">Prifiles</a></p></div>';
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Manage Blogs</title>
	<link rel="stylesheet" type="text/css" href="css/page.css" />
    <style type="text/css">

    </style>
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
	
	<div class="contents">
		<h3><a href="contents.php?action=add">Add New</a></h3>
	<?php 
		require 'db.inc.php';

		$cursor = $collection->find(array('type' => array('$ne' => 'about')));
        $cursor->sort( array( '_id' => -1 ) );

		echo ' <table class="mytable"> ';
        echo ' <tr> <th> Title </th> ';
        echo ' <th> Text </th> ';               
        echo ' <th> Status </th> ';               
        echo ' <th> Type </th> ';
        echo ' <th> Label </th> ';
        echo ' <th> Author </th> ';
        echo ' <th> CteateTime </th> ';
        echo ' <th> Option </th> </tr> ';

        $odd = true;

        foreach($cursor as $key => $value){ 
            if($value['title'] !== NULL) {

                echo ($odd == true) ? ' <tr class="odd_row"> ' : ' <tr class="even_row"> ';
                $odd = !$odd;
                echo ' <td> ';
                echo $value['title'];
                echo ' </td> <td> ';
                echo $value['text'];
                echo ' </td> <td> ';
                echo $value['status'];
                echo ' </td> <td> ';
                echo $value['type'];
                echo '</td> <td> ';
                foreach ($value['label'] as $ke => $val) {
                    echo $val.' ' ;                      
                }   
               
                echo '</td> <td> ';
                echo $value['author'];
                echo '</td> <td> ';
                echo date('Y-m-d H:i:s', $value['created']->sec);
                echo '</td> <td> ';
                echo ' <a href="contents.php?action=edit&id=' . $value['_id'] . '"> [EDIT] </a> ';
                echo ' <a href="delete.php?id=' . $value['_id'] . '" > [DELETE] </a> ';
                echo ' </td> </tr>';                
            } 
        } 
        echo '</table>'; 

    }else{
        echo ' <p> You have not logged in. Please <a href="login.php" >click here to log in</a></p> ';
           
    }
?>

    </div>
    <div class="clear"></div>
    <div id="footer">&copy; 2013</div>
</div>
</body>
</html>