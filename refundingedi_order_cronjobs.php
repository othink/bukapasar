<?php
date_default_timezone_set('Asia/Jakarta');
$db['host'] = "localhost"; //host
$db['user'] = "bukh7281_user"; //username database
$db['pass'] = "Bismillah11!!"; //password database
$db['name'] = "bukh7281_db1"; //nama database
$koneksi = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name']);

function rekening(){
    global $koneksi;
    $rek = mysqli_fetch_array(mysqli_query($koneksi,"SELECT id_rekening FROM `rb_rekening` ORDER BY RAND() DESC LIMIT 1"));
    return $rek['id_rekening'];
}

$cek_order = mysqli_query($koneksi,"SELECT b.id_penjualan_otomatis, a.id_penjualan, a.id_pembeli, a.kode_transaksi, b.nominal, b.waktu_proses, SUBSTRING_INDEX(TIMEDIFF(NOW(), b.waktu_proses), ':', 1) as selisih FROM `rb_penjualan` a JOIN rb_penjualan_otomatis b ON a.kode_transaksi=b.kode_transaksi where a.proses='1' AND b.pembayaran='1'");
while($row = mysqli_fetch_array($cek_order)){
    if ((int)$row['selisih']>='24'){
        mysqli_query($koneksi,"INSERT INTO rb_withdraw VALUES('','".rekening()."','$row[id_pembeli]','$row[nominal]','Sukses','kredit','','konsumen','".date('Y-m-d H:i:s')."')");
        mysqli_query($koneksi,"UPDATE rb_penjualan_otomatis SET pembayaran=NULL where kode_transaksi='$row[kode_transaksi]'");
        mysqli_query($koneksi,"UPDATE rb_penjualan SET proses='0' where id_penjualan='$row[id_penjualan]'");
    }
}