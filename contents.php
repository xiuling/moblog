<?php
	session_start();
    if($_SESSION['username']){
        echo '<div class="logheader"><p class="Welcome">Welcome back, '.$_SESSION['username'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php">Manage Blog</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="profile.php">Profiles</a></p></div>';
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Manage Blogs</title>
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

	if ($_GET['action'] == 'edit') {
	//retrieve the record’s information
		$cursor = $collection->findOne(array("_id" => new MongoId($_GET['id'])));  //not find()
		$title = $cursor['title'];
		$type = $cursor['type'];
		foreach ($cursor['label'] as $key => $val) {
			$label=$val.' '.$label;
		}
		$text = $cursor['text'];
	} else {
		//set values to blank
		$title = isset($_GET['title']) ? trim($_GET['title']) : '';
		$type = isset($_GET['type']) ? trim($_GET['type']) : '';
		$label = isset($_GET['label']) ? trim($_GET['label']) : '';
		$text = isset($_GET['text']) ? trim($_GET['text']) : '';
	}

	if (isset($_GET['error']) && $_GET['error'] != '') {
		echo ' <div id="error"> ' . $_GET['error'] . ' </div> ';
	}
?>
<div class="contents">
	<h2><?php echo ucfirst($_GET['action']); ?> Blog Content</h2>
	<form action="commit.php?action=<?php echo $_GET['action']; ?>" method="post" id="form1">
		<table class="left">
			<tr>
				<td> Title </td>
				<td><input type="text" name="title" value="<?php echo $title; ?>" class="long" /> </td>
			</tr>
			<tr>
				<td> Type </td>
				<td><input type="text" name="type" value="<?php echo $type; ?>" class="long" />
			<?php
				$types = $db->command(
				    array(
				        "distinct" => "posts",
				        "key" => "type", 
				        "query" => array("type" => array('$ne' => 'about'))
				    )
				);
				foreach ($types['values'] as $types) {									
					echo '<span class="small">' . $types . '&nbsp; </span>';
				}
			?>
				</td>
			</tr> 
			<!-- <tr>
				<td> Label </td>
				<td>
				<?php
				/*$labels = $db->command(
				    array(
				        "distinct" => "posts",
				        "key" => "label", 
				        "query" => array("label" => array('$ne' => 'about'))
				    )
				);
				foreach ($labels['values'] as $labels) {				
					echo '<input type="checkbox" name="label[]" value="'.$labels.'" />'.$labels.'&nbsp;';
				}*/
			?> </td>
			</tr>  -->
			 <tr>
				<td> Label </td>
				<td><input type="text" name="label" value="<?php echo $label; ?>" class="long" />
				<?php
				$labels = $db->command(
				    array(
				        "distinct" => "posts",
				        "key" => "label", 
				        "query" => array("label" => array('$ne' => 'about'))
				    )
				);
				foreach ($labels['values'] as $labels) {				
					echo '<span class="small">' . $labels . '&nbsp; </span>';	
				}
			?> </td>
			</tr> 
		
			<tr>
				<td> Content </td>
				<td><textarea name="text" cols="50" rows="20"><?php echo $text;?></textarea></td>
			</tr>
			
			<tr>
				<td colspan="2">
			<?php
				if ($_GET['action'] == 'edit') {
					echo '<input type="hidden" value="' . $_GET['id'] . '" name="_id" />';
				}
			?>
				<input type="submit" name="submit" value="<?php echo ucfirst($_GET['action']); ?>" />&nbsp;&nbsp;&nbsp;
                <input type="reset" value="Reset" />&nbsp;&nbsp;&nbsp;
                <input type="button" id="saveDraft" value="Save Draft" />
				</td>
			</tr>
		</table>
	</form>
	<script type="text/javascript">
		jQuery(window).load(function(){
		});

		jQuery(function(){ 
			jQuery('#saveDraft').click(function(){
				jQuery.ajax({
					type:'POST',
					url:'commit.php?action=saveDraft',
					dataType:'json',
					data:jQuery("#form1").serialize(),
					success: function(data){
					}
				});
			});
		});
	</script>	

<?php
	}else{
    	header ('Refresh: 1; URL= login.php');
		echo ' <p> You have not logged in. You will be redirected to login page. </p> ';
            echo ' <p> If your browser doesn\'t redirect you properly ' . 
                'automatically, <a href="login.php" >click here </a> . </p> ';
    }
?>

	</div>
	<div id="foot" style="clear:both;"></div>
</div>
</body>
</html>
