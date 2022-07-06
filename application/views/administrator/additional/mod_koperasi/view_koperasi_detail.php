<?php 
if ($row['aktif']=='Y'){
  $verifikasi = "<span style='color:green'>Ter-verifikasi</span>";

}else{
  $verifikasi = "<span style='color:red'>Menunggu verifikasi</span>";
}
    echo "<dl class='dl-horizontal'>
            <dt>Nama Lengkap</dt> <dd style='color:red'>$row[nama_lengkap]</dd>
            <dt>Alamat</dt> <dd>".kecamatan($row['kecamatan_id'],$row['kota_id'])."</dd>
            <dt>No KTP </dt> <dd>$row[nomor_ktp]</dd>
            <dt>Keterangan</dt> <dd>$row[lainnya]</dd>
            <dt>Lampiran</dt> <dd>"; 
            if ($row['lampiran'] != ''){ 
              $exx = explode(';',$row['lampiran']);
              $hitungex1 = count($exx);
              $noi = 1;
                for($i=0; $i<$hitungex1; $i++){
                  if (file_exists("asset/images/".$exx[$i])) { 
                      $files_bahantugas = $this->mylibrary->Size("asset/images/".$exx[$i]);
                      echo "<p style='border-bottom:1px dotted #cecece; margin: 0 0 5px 11px;'>$noi). <a href='".site_url('members/download_file/images/'.$exx[$i])."'>$exx[$i]</a> ($files_bahantugas)</p>";
                  }else{
                      echo "<p style='border-bottom:1px dotted #cecece; margin: 0 0 5px 11px;'>$noi). <a href='#'><i>Maaf File '$exx[$i] (0)' Gagal Terkirim!</i></a></p>";
                  }
                  $noi++;
              }
            }else{
              echo "<i style='color:#8a8a8a'>Tidak ada file dilampirkan...</i>";
            }
            echo "</dd>
            <dt>Status</dt> <dd>$verifikasi</dd>
        </dl>";
?>