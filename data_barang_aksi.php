<?php

require('connection.php');
include 'stok.php';

if (isset($_POST['add']))
{
	$nama = mysqli_real_escape_string($con, $_POST['d_nama']);
	$spek = mysqli_real_escape_string($con, $_POST['d_spek']);
	$lokasi = mysqli_real_escape_string($con, $_POST['d_lokasi']);
	$kondisi = mysqli_real_escape_string($con, $_POST['d_kondisi']);
	$jumlah = mysqli_real_escape_string($con, $_POST['d_jumlah']);
	$sumberdana = mysqli_real_escape_string($con, $_POST['d_sumberdana']);
	$id_supplier = mysqli_real_escape_string($con, $_POST['d_id_s']);

	if ($nama == null || $spek == null || $lokasi == null || $kondisi == null || $jumlah == null || $sumberdana == null)
	{
		header('Location: main.php?s=d&err=true');
		return;
	}

	$query = "Insert Into barang (nama_barang, spesifikasi, lokasi, kondisi, jumlah_barang, sumber_dana)
			 Values ('$nama', '$spek', '$lokasi', '$kondisi', '$jumlah', '$sumberdana')";

	if (mysqli_query($con, $query))
	{
		$lastId = mysqli_insert_id($con);
		$stokUpdateQuery = "Insert Into stok (id_barang, nama_barang, jml_masuk, jml_keluar, total_barang)
							Values ('$lastId', '$nama', '$jumlah', 0, '$jumlah')";

		if (mysqli_query($con, $stokUpdateQuery))
		{
			$tgl_masuk = date("d-m-Y");
			$barangMasukQuery = "Insert Into barang_masuk (id_barang, nama_barang, tgl_masuk, jml_masuk, id_supplier)
								 Values ('$lastId', '$nama', '$tgl_masuk', '$jumlah', '$id_supplier')";

			if (mysqli_query($con, $barangMasukQuery))
			{
				//updateStok($lastId);
				header('Location: main.php?s=d');
			}
		}
	}
}

if (isset($_POST['edit']))
{
	$id = mysqli_real_escape_string($con, $_POST['d_id']);
	$nama = mysqli_real_escape_string($con, $_POST['d_nama']);
	$spek = mysqli_real_escape_string($con, $_POST['d_spek']);
	$lokasi = mysqli_real_escape_string($con, $_POST['d_lokasi']);
	$kondisi = mysqli_real_escape_string($con, $_POST['d_kondisi']);
	$jumlah = mysqli_real_escape_string($con, $_POST['d_jumlah']);
	$sumberdana = mysqli_real_escape_string($con, $_POST['d_sumberdana']);
	$id_supplier = mysqli_real_escape_string($con, $_POST['d_id_s']);

	if ($id == null || $nama == null || $spek == null || $lokasi == null || $kondisi == null || $jumlah == null || $sumberdana == null)
	{
		header('Location: main.php?s=d&err=true');
		return;
	}

	$query = "Update barang 
			  Set nama_barang='$nama', spesifikasi='$spek', lokasi='$lokasi', kondisi='$kondisi', jumlah_barang='$jumlah', sumber_dana='$sumberdana' 
			  Where id_barang='$id'";

	if (mysqli_query($con, $query))
	{
		$stokUpdateQuery = "Update stok Set nama_barang='$nama', jml_masuk='$jumlah', total_barang='$jumlah' Where id_barang='$id'";
		if (mysqli_query($con, $stokUpdateQuery))
		{
			$barangMasukQuery = "Update barang_masuk Set nama_barang='$nama', jml_masuk='$jumlah', id_supplier='$id_supplier' Where id_barang='$id'";

			if (mysqli_query($con, $barangMasukQuery))
			{
				updateStok($id);
				header('Location: main.php?s=d');
			}
		}
	}
}

if (isset($_POST['delete']))
{
	$id = mysqli_real_escape_string($con, $_POST['d_id']);

	if ($id == null)
	{
		header('Location: main.php?s=d');
		return;
	}

	$query = "Delete From barang Where id_barang='$id'";

	if (mysqli_query($con, $query))
	{
		header('Location: main.php?s=d');
	}
}

?>