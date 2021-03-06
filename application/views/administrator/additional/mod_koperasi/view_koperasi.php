<div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Daftar Koperasi <br><small>Semua data pendaftaran Koperasi Syariah Mitra Berkah Bersama</small></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-condensed">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Nama</th>
                        <th>alamat</th>
                        <th>No KTP</th>
                        <th>Keterangan</th>
                        <th style='width:120px'>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $no = 1;
                    foreach ($record->result_array() as $row){

                    if ($row['aktif']=='Y'){
                      $verifikasi = 'N';
                      $alert = "Yakin ingin Un-verifikasi data ini?";
                      $button = 'default';
                      $bintang = "<i class='fa fa-star text-yellow'></i>"; 
                    }else{
                      $verifikasi = 'Y';
                      $alert = "Yakin ingin Verifikasi data ini?";
                      $button = 'primary';
                      $bintang = "<i class='fa fa-star-o'></i>"; 
                    }
                    
                    echo "<tr><td>$no</td>
                              <td>$bintang $row[nama_lengkap]</td>
                              <td>".kecamatan($row['kecamatan_id'],$row['kota_id'])."</td>
                              <td>$row[nomor_ktp]</td>
                              <td>$row[lainnya]</td>
                              <td><center>
                              <a class='btn btn-$button btn-xs' title='Verifikasi Data' href='".base_url()."administrator/verifikasi_koperasi/$row[id_koperasi]/$verifikasi' onclick=\"return confirm('$alert')\"><span class='glyphicon glyphicon-ok'></span></a>
                              <a class='btn btn-success btn-xs detail-sopir' data-id='$row[id_koperasi]' title='Detail Data' href='#'><span class='glyphicon glyphicon-search'></span></a>
                              <a class='btn btn-danger btn-xs' title='Delete Data' href='".base_url()."administrator/delete_koperasi/$row[id_koperasi]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>
                          </tr>";
                      $no++;
                    }
                  ?>
                  </tbody>
                </table>
              </div>

<div class="modal fade bs-example-modal-lg" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h5 class="modal-title" id="myModalLabel">Detail Data</h5>
        </div>
        <div class="modal-body">
          <div class="content-body"></div>
        </div>
    </div>
</div>
</div>

<script>
    $(function(){
        $(document).on('click','.detail-sopir',function(e){
            e.preventDefault();
            $("#myModalDetail").modal('show');
            $.post("<?php echo site_url().$this->uri->segment(1); ?>/detail_koperasi",
                {id:$(this).attr('data-id')},
                function(html){
                    $(".content-body").html(html);
                }   
            );
        });
    });
</script>