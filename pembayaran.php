<?php
session_start();
//koneksi ke database
include 'koneksi.php';

//jika tidak ada session pelanggan (blm login)
if (!isset($_SESSION["pelanggan"]) OR empty($_SESSION["pelanggan"]))
{
	echo "<script>alert('Silahkan Login Dahulu');</script>";
	echo "<script>location='login.php';</script>";
	exit();
}

//mendapatkan id_pembelian dari url
$idpem = $_GET["id"];
$ambil = $koneksi->query("SELECT * FROM pembelian WHERE id_pembelian='$idpem'");
$detpem = $ambil->fetch_assoc();

// echo "<pre>";
// print_r($detpem);
// print_r($_SESSION);
// echo "</pre>";

//mendapatkan id_pelanggan yang beli
$id_pelanggan_beli = $detpem["id_pelanggan"];
//mendapatkan id_pelanggan yang login
$id_pelanggan_login = $_SESSION["pelanggan"]["id_pelanggan"];

if ($id_pelanggan_login !==$id_pelanggan_beli)
{
	echo "<script>alert('Nakal Ya Lihat Yang Lain - Lain, Balik!!');</script>";
	echo "<script>location='riwayat.php';</script>";
	exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Pembayaran</title>
	<link rel="stylesheet" href="admin/assets/css/bootstrap.css">
</head>
<body>
	<?php include 'menu.php';?>

	<div class="container">
		<h2>Konfirmasi Pembayaran</h2>
		<p>Kirim Bukti Pembayaran A</p>
		<div class="alert alert-info">Total Tagihan Anda <strong>Rp. <?php echo number_format($detpem["total_pembelian"]) ;?></strong></div>


		<form method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label>Nama Penyetor</label>
				<input type="text" class="form-control" name="nama">
			</div>
			<div class="form-group">
				<label>Bank</label>
				<input type="text" class="form-control" name="bank">
		</form>
		<div class="form-group">
				<label>Jumlah</label>
				<input type="text" class="form-control" name="jumlah">
	</div>
	<div class="form-group">
				<label>Foto Bukti</label>
				<input type="file" class="form-control" name="bukti">
				<p class="text_danger">Foto Bukti Harus JPG Maksimal 2MB</p>
	</div>
	<button class="btn btn-primary" name="kirim">Kirim</button>
		</form>
	</div>
<?php 
//jika ada tombol kirim
if (isset($_POST["kirim"]))
{
	// upload dulu foto bukti
	$namabukti = $_FILES["bukti"]["name"];
	$lokasibukti = $_FILES["bukti"]["tmp_name"];
	$namafiks = date("YmdHis").$namabukti;
	move_uploaded_file($lokasibukti, "bukti_pembayaran/$namafiks");

	$nama = $_POST["nama"];
	$bank = $_POST["bank"];
	$jumlah = $_POST["jumlah"];
	$tanggal = date("Y-m-d");

	//simpan pembayaran
	$koneksi->query("INSERT INTO pembayaran(id_pembelian,nama,bank,jumlah,tanggal,bukti)
	VALUES ('$idpem','$nama','$bank','$jumlah','$tanggal','$namafiks') ");

	// update data pembelian dari pending jadi sudah kirim pembayaran
	$koneksi->query("UPDATE pembelian SET status_pembelian='Sudah Kirim Pembayaran'
		WHERE id_pembelian='$idpem'");

	echo "<script>alert('Terima Kasih Sudah Mengirim Bukti Pembayaran');</script>";
	echo "<script>location='riwayat.php';</script>";
}
?>

</body>
</html>