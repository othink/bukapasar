            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Transaksi Penjualan / Orderan Konsumen</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-condensed table-condensed">
                    <thead>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Kode Transaksi</th>
                        <th>Konsumen</th>
                        <th>Kurir</th>
                        <th>Status</th>
                        <th>Total + Ongkir</th>
                        <th>Waktu Transaksi</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record->result_array() as $row){
                      $total = $this->db->query("SELECT sum((a.harga_jual-a.diskon)*a.jumlah) as total FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->row_array();
                      $produk = $this->db->query("SELECT * FROM `rb_penjualan_detail` a where a.id_penjualan='$row[id_penjualan]'")->num_rows();
                      $kupon = $this->db->query("SELECT sum(c.nilai) as diskon FROM `rb_penjualan_detail` a JOIN rb_penjualan b ON a.id_penjualan=b.id_penjualan 
                                JOIN rb_penjualan_kupon c ON a.id_penjualan_detail=c.id_penjualan_detail
                                    where b.id_penjualan='$row[id_penjualan]'")->row_array();
                      
                      echo "<tr><td>$no</td>
                              <td>$row[kode_transaksi]</td>
                              <td><a href='".base_url().$this->uri->segment(1)."/detail_konsumen/$row[id_konsumen]'>$row[nama_lengkap]</a></td>";
                              if ($row['kode_kurir']=='1'){
                                $ceks = $this->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$row['kurir']."'")->row_array();
                                echo "<td>$row[service] - $ceks[merek]</td>";
                              }elseif ($row['kode_kurir']=='0'){
                                $ceks = $this->db->query("SELECT * FROM rb_sopir where id_sopir='".(int)$row['kurir']."'")->row_array();
                                echo "<td>COD - $row[service]</td>";
                              }else{
                                echo "<td><span style='text-transform:uppercase'>$row[kode_kurir]</span> - $row[service]</td>";
                              }
                              echo "<td>".status($row['proses'])."</td>
                              <td>
                                Rp ".rupiah($total['total']+$row['ongkir']-$kupon['diskon'])."</span> ($produk Produk)";
                                if ($row['kode_kurir']!='0'){
                                  if ($row['proses']!='0'){
                                    $cek_payment = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='$row[kode_transaksi]' AND pembayaran is null");
                                    if ($cek_payment->num_rows()>=1){
                                      echo "<br><a style='color:red' href='".base_url().$this->uri->segment(1)."/penjualan_konsumen?terima=$row[id_penjualan]' onclick=\"return confirm('Yakin ubah status Pembayaran ini jadi diterima?')\"><small><i>Pending Payment</i></small></a>";
                                    }
                                  }
                                }
                              echo "</td>
                              <td>".jam_tgl_indo($row['waktu_transaksi'])."</td>
                              <td><center>";
                              if ($row['proses']<'4'){
                                  echo "<a class='btn btn-primary btn-xs' href='".base_url().$this->uri->segment(1)."/penjualan_konsumen?sukses=$row[id_penjualan]' onclick=\"return confirm('Apa anda yakin Pesanan ini sudah selesai?')\"><span class='fa fa-check-square'></span></a>";
                              }else{
                                echo "<a class='btn btn-default btn-xs' href='#' onclick=\"return confirm('Maaf, Pesanan ini sudah selesai.')\"><span class='fa fa-check-square-o'></span></a>";
                              }
                                echo " <a class='btn btn-success btn-xs' title='Detail Data' href='".base_url().$this->uri->segment(1)."/detail_penjualan_konsumen/$row[id_penjualan]'><span class='glyphicon glyphicon-search'></span></a>
                                <a class='btn btn-warning btn-xs' title='Edit Data' href='".base_url().$this->uri->segment(1)."/edit_penjualan_konsumen/$row[id_penjualan]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url().$this->uri->segment(1)."/delete_penjualan_konsumen/$row[id_penjualan]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>
              </div>
              </div>
              