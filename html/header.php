<!DOCTYPE html>
<html>

<?php
	include("functions/functions.php");
	session_start();
?>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles/new_style.css" media="all">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
  <body>
    <div class="topnav">
      <div class="mini_topnav">
        <a>Contact Us</a>
		<?php
			if(($_SESSION['SESS_USERID'] == 0)) {
				echo '<a class="menu_link" href="login.php"> Login </a>'; 
			}
			else {
        echo '<a href="logout.php">Logout</a>';
				echo '<a href="myAccount.php">My Account</a>';
			}
		?>
		</b> 
      </div>
      <div class=topnav_mid>
        <a href="index.php"><img src="images/Book Monster.png" alt="home_logo" width="250" height="80"></a>
        <div class="search-container">
          <form action="result.php" class="search_form">
            <input type="text" placeholder="Search.." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
          </form>
        </div>
      </div>
      <div class="topnav_menu">
        <a href="index.php">Home</a>
        <a href="#news">Books</a>
        <a href="#top_sellers">Top Sellers</a>
		<a href="index2.php"> Rate a Book </a>
        <a href="shoppingCart.php" style="float: right"> Shopping Cart</a> 
      </div>
    </div>
  </body>
</html>
