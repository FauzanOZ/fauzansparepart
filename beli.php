<?php
session_start();
//mendapatkan id produk dari url
$id_produk = $_GET['id'];


//jk sudah ada produk itu di keranjang, maka produk itu jumlahnya di +1
if (isset($_SESSION['keranjang'][$id_produk]))
{
	$_SESSION['keranjang'][$id_produk]+=1;
}
//selain itu (blm ada dikeranjang), mk produk itu dianggap dibeli 1
else
{
	$_SESSION['keranjang'][$id_produk] = 1;
}



//echo "<pre>";
//print_r($_SESSION);
//echo "</pre>";


//larikan ke halaman keranjang
echo "<script>alert(produk telah masuk ke keranjang belanjaan);</script>";
echo "<script>location='keranjang.php';</script>";
?> 