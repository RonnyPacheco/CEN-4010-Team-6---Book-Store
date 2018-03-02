<!DOCTYPE html>
<html>

<?php
	session_start();
	include("functions/functions.php");
  echo file_get_contents("html/header.php");
?>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles/new_style.css" media="all" </link>
</head>
<body>

	<div class="content" >
	<form action = "" method = "post" enctype="multipart/form-data">

			<table align = "center" width = "700" bgcolor = "skyblue">

					<tr align = "center">
								<th>Remove</th>
								<th>Books</th>
								<th>Quantity</th>
								<th>Total Price</th>
					</tr>

					<?php
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

									$book_tit = $b_price['book_title'];

									$book_img = $b_price['book_image'];

									$single_price = $b_price['price'];

									$values = array_sum($book_price);

									$total +=$values;


					?>

					<tr align = "center">
							<td><input type = "checkbox" name = "remove[]" value="<?php echo $pro_id; ?>"/></td>
							<td><?php echo $book_tit; ?><br>
							<img src='admin/book_images/<?php echo $book_img;?>' width ='100' height='120'/>
							</td>
							<td><input type = "text" size = "4" name = "quantity" value = "<?php echo $_SESSION['quantity'];?>"/></td>
							<?php

							if(isset($_POST['update_cart'])){

											$qty = $_POST['quantity'];

											$update_qty = "update cart set quantity='$qty'";

											$run_qty = mysqli_query($con, $update_qty);

											$_SESSION['quantity'] = $qty;

											$total = $total*$qty;

							}

							?>



							<td><?php echo "$" . $single_price; ?></td>
					</tr>


				<?php } } ?>

				<tr align = "right">
						<td colspan="4"><b>Total:</b></td>
						<td><?php echo "$" . $total; ?></td>
				</tr>

				<tr align = "center">
							<td colspan="1"><input type= "submit" name = "update_cart" value = "Update Cart"/></td>
							<td><input type= "submit" name = "continue" value = "Continue Shopping"/></td>
							<td><button><a href = "checkout.php" style="text-decoration: none; color:black;">Checkout</a></button></a></td>
				</tr>


			</table>

	</form>


	<?php
	function updatecart(){

				global $con;

				$ip = getIp();

				if(isset($_POST['update_cart'])){

						foreach ($_POST['remove'] as $remove_id) {

							$delete_book = "delete from cart where book_id= '$remove_id' AND ip_add = '$ip'";

							$run_delete = mysqli_query($con, $delete_book);

							if($run_delete){

								echo "<script>window.open('shoppingcart.php','_self')</script>";


							}
						}


				}
				if(isset($_POST['continue'])){

					echo "<script>window.open('index.php','_self')</script>";

				}

		echo @$up_cart = updatecart();
	}
	?>

	</div>

</body>
</html>
