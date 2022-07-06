<div class="ps-page--single">
    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="#">Members</a></li>
                <li>Profile Koperasi</li>
            </ul>
        </div>
    </div>
</div>
<div class="ps-vendor-dashboard pro" style='margin-top:10px'>
    <div class="container">
        <div class="ps-section__content">
            <?php include "menu-members.php"; ?>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <?php
                      echo "<a href='".base_url()."members/daftar_koperasi' class='ps-btn btn-block'><center><i class='icon-pen'></i> Daftar / Edit Data</center></a>";
                      include "sidebar-members.php";
                    ?><div style='clear:both'><br></div>
                </div>

                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12 ">
                    <figure class="ps-block--vendor-status biodata">
                        <?php 
                          echo $this->session->flashdata('message'); 
                                $this->session->unset_userdata('message');
                          $cek_sopir = $this->db->query("SELECT a.*, b.nama_lengkap, b.no_hp, b.kecamatan_id, b.kota_id FROM rb_koperasi a JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen 
                                                          where a.id_konsumen='".$this->session->id_konsumen."'");
                          if ($cek_sopir->num_rows()>=1){
                            $rows = $cek_sopir->row_array();
                            
                            echo "<p style='font-size:17px'>Hai <b>$row[nama_lengkap]</b>, selamat datang di halaman Data Koperasi! <br>
                                                              Pastikan data profil sesuai dengan KTP untuk kemudahan dalam bertransaksi.</p><br>

                              <div class='form-group row' style='margin-bottom:5px'>
                              <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Nama Lengkap</b></label>
                                <div class='col-sm-9'>
                                  $rows[nama_lengkap]
                                </div>
                              </div>
                              <div class='form-group row' style='margin-bottom:5px'>
                              <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>No Hp</b></label>
                              <div class='col-sm-9'>
                                $rows[no_hp]
                              </div>
                              </div>
                            
                              
                              <div class='form-group row' style='margin-bottom:5px'>
                              <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>No KTP</b></label>
                              <div class='col-sm-9'>
                                $rows[nomor_ktp]
                              </div>
                              </div>

                              <div class='form-group row' style='margin-bottom:5px'>
                              <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Keterangan</b></label>
                              <div class='col-sm-9'>
                              ".($rows['lainnya']==''? '<i style="color:#cecece">Tidak ada keterangan,..</i>':$rows['lainnya'])."
                              </div>
                              </div>
                              
                              <div class='form-group row' style='margin-bottom:5px'>
                              <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Lampiran / File</b></label>
                              <div class='col-sm-9'>";
                              if ($rows['lampiran'] != ''){ 
                                $exx = explode(';',$rows['lampiran']);
                                $hitungex1 = count($exx);
                                $noi = 1;
                                  for($i=0; $i<$hitungex1; $i++){
                                    if (file_exists("asset/images/".$exx[$i])) { 
                                        $files_bahantugas = $this->mylibrary->Size("asset/images/".$exx[$i]);
                                        echo "<p style='margin: 0 0 0px 11px;'>$noi). <a href='".site_url($this->uri->segment(1).'/download_file/images/'.$exx[$i])."'>$exx[$i]</a> ($files_bahantugas)</p>";
                                    }else{
                                        echo "<p style='margin: 0 0 0px 11px;'>$noi). <a href='#'><i>Maaf File '$exx[$i] (0)' Gagal Terkirim!</i></a></p>";
                                    }
                                    $noi++;
                                }
                              }
                              echo "</div>
                              </div>
                              
                              <div class='form-group row' style='margin-bottom:5px'>
                              <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Status</b></label>
                              <div class='col-sm-9'>
                              ".($rows['aktif'] == 'N' ? '<i style="color:red">Non Aktif (Menunggu Verifikasi)</i>' : '<i style="color:green">Aktif (Ter-Verifikasi)</i>')."
                              </div>
                              </div>";

                              if($rows['nomor_nasabah'] !=""){
                                echo "<div class='form-group row' style='margin-bottom:5px'>
                                    <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Nomor Nasabah</b></label>
                                    <div class='col-sm-9'>
                                    ".$rows['nomor_nasabah']."
                                    </div>
                                    </div>
                                    <div class='form-group row' style='margin-bottom:5px'>
                                    <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Rekening Simpanan</b></label>
                                    <div class='col-sm-9'>
                                    ".$rows['rek_simpanan']."
                                    </div>
                                    </div>
                                    
                                    <div class='form-group row' style='margin-bottom:5px'>
                                    <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Rekening Pembiayaan</b></label>
                                    <div class='col-sm-9'>
                                    ".$rows['rek_pembiayaan']."
                                    </div>
                                    </div>
                                    <div class='form-group row' style='margin-bottom:5px'>
                                    <label class='col-sm-3 col-form-label' style='margin-bottom:1px'>Rekening Deposito</b></label>
                                    <div class='col-sm-9'>
                                    ".$rows['rek_deposito']."
                                    </div>
                                    </div>";
                              }
                          }else{
                            echo "<div class='alert alert-danger'><strong>PENTING</strong> - Halo kak! Mau ikutan Koperasi pendanaan modal UMKM?. <br> 
                                                                                    yuk isikan dulu datanya, Daftarkan sesuai KTP <a href='".base_url()."members/daftar_koperasi' style='color:#000'><b>disini</b></a>.</div><br>";
                                                                                    
                                  $page = $this->model_app->view_where('halamanstatis',array('id_halaman'=>4))->row_array();
                                  echo "<h3><b>$page[judul]</h3>
                                  $page[isi_halaman]";
                          }
                        ?>
                    </figure>
                </div>
              
            </div>
        </div>
    </div>
</div>

