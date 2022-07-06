<?php $detail = $this->db->query("SELECT * FROM rb_penjualan where id_penjualan='".$this->uri->segment(3)."'")->row_array(); ?>
<html>
<head>
<title><?php echo $rows[kode_transaksi]; ?></title>
<base href="<?php echo base_url();?>" />
</head>
<style>
@media print {
  	* { background: transparent !important; color: black !important; text-shadow: none !important; filter:none !important; -ms-filter: none !important; }
  	@page { 
  		margin: 0.5cm; 
  		font-size : 12px;
  	}
  	
}
</style>
<script type="text/javascript">
<!--
window.print();
//-->
</script>

<body>
<?php
$penjual = $this->db->query("SELECT `nama_reseller`,`no_telpon` FROM `rb_reseller` where id_reseller='".$rows['id_penjual']."'")->row_array();
?>
<table width="100%" >
                  <tbody>
                    <tr><th align="left"><b>FROM</b> : </th>
                        <td><?php echo $penjual['nama_reseller']." / ".$penjual['no_telpon']; ?></td>
                    </tr>
                    <tr>
                                                            <td colspan="2" style="text-align:left;border-bottom:1px solid #000">&nbsp;</td>
                                                        </tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><th align="left"><b>To</b> : </th>
                        <td><?php echo strtoupper($rows[nama_lengkap])." / HP  ".$rows[no_hp]; ?></td>
                    </tr>
                    <tr><th valign="top" align="left"><b>Alamat </b> :</th>               
                        <td><?php echo alamat($rows['kode_transaksi']); ?></td>
                    </tr>
                    
                  </tbody>
                  </table>
<?php 
                    echo "<div style='padding:5px; font-size:16px; font-weight:bold; margin-bottom:5px;'>Barang : </div>";
                    $no = 1;
                    foreach ($record as $row){
                        echo "$row[nama_produk] <b> : $row[jumlah]</b><br>";
                      $no++;
                    }
echo "<br><br><span style='font-size:9px;'>".$detail[kurir] ." - ". $detail[service]."</span>";
?>
</body>
</html>