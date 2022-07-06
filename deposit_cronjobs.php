<?php
date_default_timezone_set('Asia/Jakarta');
$db['host'] = "localhost"; //host
$db['user'] = "bukh7281_user"; //username database
$db['pass'] = "Bismillah11!!"; //password database
$db['name'] = "bukh7281_db1"; //nama database
$koneksi = mysqli_connect($db['host'], $db['user'], $db['pass'], $db['name']);

$cek_deposit = mysqli_query($koneksi,"SELECT `nominal`,`id_reseller` FROM `rb_withdraw` where `transaksi`='kredit' AND `status`='Pending' AND akun='konsumen'");
while($row = mysqli_fetch_array($cek_deposit)){
    echo $row['nominal']."#".$row['id_reseller']."<br>";
}