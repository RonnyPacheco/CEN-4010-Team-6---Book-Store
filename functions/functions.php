<?php

	//connect to database. //server, user, password, database
	$con = mysqli_connect("localhost","root","","geek_text");

	if(mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_errno();
	}

//Creating the shopping cart
	function cart(){

		if(isset($_GET['add_cart'])){

				global $con;

				$ip = getIp();
				$book_id = $_GET['add_cart'];

				$check_book = "select * from cart where ip_add='$ip' AND book_id='$book_id' ";

				$run_check = mysqli_query($con, $check_book);

				if(mysqli_num_rows($run_check)>0){

					echo "";


				}
				else {

					$insert_book = "insert into cart (book_id, ip_add) values ('$book_id','$ip')";

					$run_pro = mysqli_query($con, $insert_book);

					echo "<script>window.open('index.php','_self')</script>";

				}


			}
}

//getting the total books added
function total_books() {

	if(isset($_GET['add_cart'])){

		global $con;

		$ip = getIp();

		$get_items = "select * from cart where ip_add = '$ip'";

		$run_items = mysqli_query($con, $get_items);

		$count_items = mysqli_num_rows($run_items);
	}

		else {

			global $con;

			$ip = getIp();

			$get_items = "select * from cart where ip_add = '$ip'";

			$run_items = mysqli_query($con, $get_items);

			$count_items = mysqli_num_rows($run_items);

		}



echo $count_items;
}

//Getting total price of books in the cart
function total_price(){

	$total = 0;

	global $con;

	$ip = getIp();

	$sel_price = "select * from cart where ip_add= '$ip'";

	$run_price = mysqli_query($con, $sel_price);

	while ($p_price=mysqli_fetch_array($run_price)){

			$pro_id = $p_price['book_id'];

			$pro_price = "select * from books where isbn='$pro_id'";

			$run_book_price = mysqli_query($con, $pro_price);

			while ($b_price = mysqli_fetch_array($run_book_price)){

					$book_price = array($b_price['price']);

					$values = array_sum($book_price);

					$total +=$values;
			}


	}

	echo "$" . $total;
}


//getting customer IP
	function getIp() {
	$ip = $_SERVER['REMOTE_ADDR'];

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}

	return $ip;
}

	//function to get genres
	function getGenres(){

		if(!isset($_GET['genre'])){

			//make variable global
			global $con;

			//variable that gets items from book_genres table
			$get_genres = "select * from book_genres order by genre_type";

			$run_genres =  mysqli_query($con, $get_genres);

			//loop that runs depending on the amount of rows on the book_genres table
			while($row_genres=mysqli_fetch_array($run_genres)){
				//fetching the information from the genre_type table on database
				$genre_id = $row_genres['genre_id'];
				$genre_type = $row_genres['genre_type'];
				//display genres
				echo "<li><a href='genres.php?genre=$genre_id'>$genre_type </a></li>";
			}

		}

	}

	//function to display books on main page.
	function getBook(){

		global $con;
		if(!isset($_GET['genre'])){

			$rec_limit = 5;
			/* Get total number of records */
			$sql = "SELECT count(isbn) FROM book_ratings_view";
			$retval = mysqli_query($con, $sql );

			if(! $retval ) {
			die('Could not get data: ' . mysqli_error($con));
			}
			$row = mysqli_fetch_array($retval  );
			$rec_count = $row[0];

			if( isset($_GET{'page'} ) ) {
			$page = $_GET{'page'};
			$offset = $rec_limit * ($page - 1) ;
			}else {
			$page = 0;
			$offset = 0;
			}

			$left_rec = $rec_count - ($page * $rec_limit);
					$total_pages = ceil($rec_count / $rec_limit);


			$retval = mysqli_query($con, $sql );

			if(! $retval ) {
			die('Could not get data: ' . mysql_error());
			}


			$get_b = "SELECT * FROM book_ratings_view LIMIT $rec_limit OFFSET $offset";

			$run_b = mysqli_query($con, $get_b);

		//connection to db
			global $con;
			//get 6 random books
			//$get_b = "SELECT * FROM book_ratings_view LIMIT ";

			$run_b = mysqli_query($con, $get_b);
			while($row_b=mysqli_fetch_array($run_b)){
					//initializing variable with book name.
					$b_title = $row_b['book_title'];
					$b_price = $row_b['price'];
					$b_image = $row_b['book_image'];
					$b_year = $row_b['year'];
					$b_rating = round ($row_b['avgRating']);

					//primary key
					//used to display individual details page.
					$b_isbn = $row_b['isbn'];

				echo "
					<div id='single_book'>

							<h3>$b_title</h3>
							<a href='details.php?b_isbn=$b_isbn'><img src='admin/book_images/$b_image' width='150px' height='200px'  /></a>
							<p> $ $b_price </p>
							<p> Year: $b_year </p>
							<p> Rating: $b_rating/5</p>

						<div style='margin: auto;'>
						<a href='details.php?b_isbn=$b_isbn' style='float:left;'>More Info</a>

						<a href='index.php?add_cart=$b_isbn'><button style='float:right'>Add to Cart</button></a>

						</div>
					</div>

				";

			}
			$pagLink = "<div class='pagination'>";
			for ($i=1; $i<=$total_pages; $i++) {
				if($page == $i){
					$pagLink .= "<a class='active' href='index.php?page=".$i."'>".$i."</a>";
				}else{
					$pagLink .= "<a  href='index.php?page=".$i."'>".$i."</a>";
				}

			};
			echo $pagLink . "</div>";
		}

	}




	function getBookGenre(){

		if(isset($_GET['genre'])){

			//getting book genre from url
			$book_genre = $_GET['genre'];

			//connection to db
			global $con;

			//get 6 random books
			$get_books_genre = "select * from books where genre ='$book_genre' LIMIT 0,6";
			$run_books_genre = mysqli_query($con, $get_books_genre);
			$book_counter = mysqli_num_rows($run_books_genre);

			if($book_counter == 0){
					echo "<h2>There are no books on this genre!</h2>";
			}
			else{
				while($row_books_genre=mysqli_fetch_array($run_books_genre)){

						//initializing variable with book name.
					$b_title = $row_books_genre['book_title'];
					$b_author = $row_books_genre['author'];
					$b_genre = $row_books_genre['genre'];
					$b_release = $row_books_genre['release_date'];
					$b_price = $row_books_genre['price'];
					$b_image = $row_books_genre['book_image'];
						//primary key
						//used to display individual datails page.
					$b_isbn = $row_books_genre['isbn'];
					echo "
						<div id='single_book'>

							<h3>$b_title</h3>
							<a href='details.php?b_isbn=$b_isbn'><img src='admin/book_images/$b_image' width='150px' height='200px'  /></a>
							<p> $ $b_price </p>

							<div style='margin: auto;'>
							<a href='details.php?b_isbn=$b_isbn' style='float:left;'>More Info</a>

							<a href='index.php?b_isbn=$b_isbn'><button style='float:right'>Add to Cart</button></a>

							</div>
						</div>

						";
				}
			}
		}
	}


	function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}

?>
