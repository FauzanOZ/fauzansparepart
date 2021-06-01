<?php
session_start();
include 'koneksi.php';

$id_pembelian = $_GET["id"];

$ambil = $koneksi->query("SELECT * FROM pembayaran 
	LEFT JOIN pembelian on pembayaran.id_pembelian=pembelian.id_pembelian 
	WHERE pembelian.id_pembelian='$id_pembelian'");
$detbay = $ambil->fetch_assoc();

// echo "<pre>";
// print_r ($detbay);
// echo "</pre>";

//jika blm ada data pembayaran
if ($detbay["status_pembelian"]=="pending")
{
	echo "<script>alert('Belum ada Data Pembayaran') </script>";
	echo "<script>location='riwayat.php';</script>";
	exit();
}

// jika data pembayaran yg bayar tidak sesuai dengan yang login
// echo "<pre>";
// print_r ($_SESSION);
// echo "</pre>";

if ($_SESSION["pelanggan"]['id_pelanggan']!==$detbay["id_pelanggan"])
{
	echo "<script>alert('Anda Tidak Berhak Melihat Pembayaran Orang Lain') </script>";
	echo "<script>location='riwayat.php';</script>";
	exit();
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Lihat Pembayaran</title>
	<link rel="stylesheet" type="text/css" href="admin/assets/css/bootstrap.css">
</head>
<body>
	<?php include 'menu.php'; ?>
	<div class="container">
		<h3>Lihat Pembayaran</h3>
		<div class="row">
			<div class="col-md-6">
				<table class="table">
					<tr>
						<th>Nama</th> 			
						<td><?php echo $detbay["nama"] ?></td>
					</tr>
					<tr>
						<th>Bank</th> 			
						<td><?php echo $detbay["bank"] ?></td>
					</tr><tr>
						<th>Tanggal</th> 			
						<td><?php echo $detbay["tanggal"] ?></td>
					</tr><tr>
						<th>Jumlah</th> 			
						<td>Rp. <?php echo $detbay["jumlah"] ?></td>
					</tr>
				</table>	
			</div>
			<div class="col-md-6">
				<img src="bukti_pembayaran/<?php echo $detbay["bukti"] ?>" alt="" class="img-responsive" width="300" >

		</div>
	</div>
</body>
</html>