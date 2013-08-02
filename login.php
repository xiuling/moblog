<?php
session_start();
include 'db.inc.php';

$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if(isset($_POST['submit'])){
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	$cursor = $db->users->findOne(array("username" => $username, "password" => $password));

	if($cursor){
		$_SESSION['username'] = $username;
		$_SESSION['logged'] = 1;
		$_SESSION['authCode'] = $cursor['authCode'];

		header('Refresh: 1; URL=admin.php');
		echo '<p>You will be redirected to your admin page</p>';
		echo '<p>If your browser doesn\'t redirect automatically,you can <a href="admin.php">click here</a></p>';
	} else{
		$_SESSION['username'] = '';
		$_SESSION['logged'] = 0;
		$error = '<p> Invalid username or password.</p>';
	}
} else{


?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="css/page.css">
	<title>LogIn</title>
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
<?php
	if(isset($error)){
		echo $error;
	}
?>
<div class="main">
	<form action="login.php" method="post">
		<table>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="username" size="20" value="<?php echo $username; ?>" /></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" size="20" value="<?php echo $password; ?>" /></td>
			</tr>
			<tr>
				<td cols="2"><input type="submit" name="submit" value="Submit" /></td>
			</tr>
		</table>
	</form>
</div>
<div class="clear"></div>
</div>
</body>
</html>
<?php
}
?>
