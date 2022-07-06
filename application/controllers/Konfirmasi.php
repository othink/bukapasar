<?php
/*
-- ---------------------------------------------------------------
-- MARKETPLACE MULTI BUYER MULTI SELLER + SUPPORT RESELLER SYSTEM
-- CREATED BY : ROBBY PRIHANDAYA
-- COPYRIGHT  : Copyright (c) 2018 - 2019, PHPMU.COM. (https://phpmu.com/)
-- LICENSE    : http://opensource.org/licenses/MIT  MIT License
-- CREATED ON : 2019-03-26
-- UPDATED ON : 2019-03-27
-- ---------------------------------------------------------------
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Konfirmasi extends CI_Controller {
	public function __construct(){
        parent::__construct();
	}
	
	function index(){
		$id = $this->uri->segment(3);
		if (isset($_POST['submit'])){
			$config['upload_path'] = 'asset/files/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '10000'; // kb
            $this->load->library('upload', $config);
            $this->upload->do_upload('f');
            $hasil=$this->upload->data();
            if ($hasil['file_name']==''){
				$data = array('kode_transaksi'=>$this->input->post('id', TRUE),
			        		  'total_transfer'=>$this->input->post('b', TRUE),
			        		  'id_rekening'=>$this->input->post('c', TRUE),
			        		  'nama_pengirim'=>$this->input->post('d', TRUE),
			        		  'tanggal_transfer'=>$this->input->post('e', TRUE),
			        		  'waktu_konfirmasi'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konfirmasi_pembayaran_konsumen',$data);
			}else{
				$data = array('kode_transaksi'=>$this->input->post('id', TRUE),
			        		  'total_transfer'=>$this->input->post('b', TRUE),
			        		  'id_rekening'=>$this->input->post('c', TRUE),
			        		  'nama_pengirim'=>$this->input->post('d', TRUE),
			        		  'tanggal_transfer'=>$this->input->post('e', TRUE),
			        		  'bukti_transfer'=>$hasil['file_name'],
			        		  'waktu_konfirmasi'=>date('Y-m-d H:i:s'));
				$this->model_app->insert('rb_konfirmasi_pembayaran_konsumen',$data);
			}
				$data1 = array('proses'=>'2');
				$where = array('id_penjualan' => $this->input->post('id', TRUE));
				$this->model_app->update('rb_penjualan', $data1, $where);
				echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Sukses Melakukan Konfirmasi Pembayaran Pesanan!</center></div>');
			redirect('konfirmasi/index?success');
		}else{
			$data['title'] = 'Konfirmasi Pesanan';
			$data['description'] = description();
			$data['keywords'] = keywords();
			if (isset($_POST['submit1']) OR $_GET['kode']){
				if ($_GET['kode']!=''){
					$kode_transaksi = filter($this->input->get('kode'));
				}else{
					$kode_transaksi = filter($this->input->post('a'));
				}
				$row = $this->db->query("SELECT a.id_penjualan, b.id_reseller FROM `rb_penjualan` a jOIN rb_reseller b ON a.id_penjual=b.id_reseller where status_penjual='reseller' AND a.kode_transaksi='$kode_transaksi'")->row_array();
				$data['record'] = $this->model_app->view('rb_rekening');
				$data['kode'] = $kode_transaksi;
				$data['total'] = $this->db->query("SELECT nominal FROM rb_penjualan_otomatis where kode_transaksi='".$kode_transaksi."'")->row_array();
				$data['rows'] = $this->model_app->view_where('rb_penjualan',array('id_penjualan'=>$row['id_penjualan']))->row_array();
				$this->template->load(template().'/template',template().'/reseller/view_konfirmasi_pembayaran',$data);
			}else{
				$this->template->load(template().'/template',template().'/reseller/view_konfirmasi_pembayaran',$data);
			}
		}
	}

	function tracking(){
		if (isset($_GET['trx_id'])){
			if ($this->uri->segment(3)!=''){
				$kode_transaksi = filter($this->uri->segment(3));
			}else{
				$kode_transaksi = filter($this->input->post('a'));
			}
			$strx = $this->db->query("SELECT status_trx FROM rb_penjualan_otomatis where kode_transaksi='$kode_transaksi'")->row_array();
			if ($strx['status_trx']==''){
				$data = array('catatan'=>cetak($_GET['trx_id']),'status_trx'=>cetak($_GET['status']));
				$where = array('kode_transaksi' => $kode_transaksi);
				$this->model_app->update('rb_penjualan_otomatis', $data, $where);
			}
		}

		if (isset($_POST['submit1']) OR $this->uri->segment(3)!=''){
			if ($this->uri->segment(3)!=''){
				$kode_transaksi = filter($this->uri->segment(3));
			}else{
				$kode_transaksi = filter($this->input->post('a'));
			}

			$cek = $this->model_app->view_where('rb_penjualan',array('kode_transaksi'=>$kode_transaksi));
			if ($cek->num_rows()>=1){
				$data['title'] = 'Tracking Order '.$kode_transaksi;
				$data['judul'] = $kode_transaksi;
				$data['kode'] = $kode_transaksi;
				$data['description'] = description();
				$data['keywords'] = keywords();
				$data['rows'] = $this->db->query("SELECT * FROM rb_penjualan a JOIN rb_konsumen b ON a.id_pembeli=b.id_konsumen JOIN rb_kota c ON b.kota_id=c.kota_id where a.kode_transaksi='$kode_transaksi'")->row_array();
				$data['record'] = $this->db->query("SELECT a.kode_transaksi, b.*, c.nama_produk, c.gambar, c.satuan, c.berat, c.produk_seo, d.nama_reseller FROM `rb_penjualan` a JOIN rb_penjualan_detail b ON a.id_penjualan=b.id_penjualan JOIN rb_produk c ON b.id_produk=c.id_produk JOIN rb_reseller d ON c.id_reseller=d.id_reseller where a.kode_transaksi='".$kode_transaksi."'");
				$data['total'] = $this->db->query("SELECT a.kode_transaksi, a.kurir, a.service, a.proses, sum(a.ongkir) as ongkir, sum(b.harga_jual*b.jumlah) as total, sum(b.diskon*b.jumlah) as diskon_total, sum(c.berat*b.jumlah) as total_berat FROM `rb_penjualan` a JOIN rb_penjualan_detail b ON a.id_penjualan=b.id_penjualan JOIN rb_produk c ON b.id_produk=c.id_produk where a.kode_transaksi='".$kode_transaksi."'")->row_array();
				$data['unik'] = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='".$kode_transaksi."'")->row_array();
			
				if (isset($_POST['submit1'])){
				$tgl_kirim = date("d-m-Y H:i:s");
				$logo = $this->db->query("SELECT * FROM logo ORDER BY id_logo DESC LIMIT 1")->row_array();
				$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
				$tot = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='".$kode_transaksi."'")->row_array();
				$total_tagihan_akhir = $tot['nominal'];
				$rowc = $cek->row_array();
				$kons = $this->model_reseller->profile_konsumen($rowc['id_pembeli'])->row_array();

				$subject      = "$iden[pengirim_email] - Tracking Orders";
				$message  = "<html><body><img src='".base_url()."asset/logo/$logo[gambar]' style='height:87px'><br>
										<span style='font-size:20px'>Informasi Transaksi #$kode_transaksi</span><br>
										Hai $kons[nama_lengkap]! Terima kasih telah berbelanja di <a style='text-decoration:none; color:#000' href='$iden[url]'>$iden[url]</a>. <br>Berikut informasi Orderan Invoice <b>#$kode_transaksi</b>.<br><br>

					<b>Detail Pemesanan</b>

					<table style='width:100%'>
						<tbody>";
						$no = 1;
						$record_detail = $this->db->query("SELECT a.id_penjual, a.kode_transaksi, b.*, c.nama_produk, c.gambar, c.satuan, c.berat, c.produk_seo, d.nama_reseller FROM `rb_penjualan` a JOIN rb_penjualan_detail b ON a.id_penjualan=b.id_penjualan JOIN rb_produk c ON b.id_produk=c.id_produk JOIN rb_reseller d ON c.id_reseller=d.id_reseller where a.kode_transaksi='".$kode_transaksi."' AND a.kode_kurir!='0'");
						foreach ($record_detail->result_array() as $det) {
							$sub_total = $det['jumlah']*($det['harga_jual']-$det['diskon']);
							$message  .= "<tr>
											<td><h1>$no</h1></td>
											<td><a style='text-decoration:none; color:green; font-size:16px' href='".base_url()."produk/detail/$det[produk_seo]' title='$det[nama_produk]'><b>$det[nama_produk]</b></a>
												<br> Seller : <a href='".base_url()."produk/produk_reseller/$det[id_penjual]'>$det[nama_reseller]</a>
												<br> $det[jumlah] x ".rupiah($det['harga_jual']-$det['diskon'])."
												<br><b>Subtotal : Rp ".rupiah($sub_total)."</b>
											</td>
										</tr>";
							$no++;
						}
						$message  .= "</tbody>
					</table>";

					$message      .= "<br><span style='color:#999'>Total Belanja + Ongkir</span><br>
									<span style='font-size:20px; font-weight:bold; color:red'>Rp ".rupiah($total_tagihan_akhir)."</span><br>";
					if ($rowc['proses']=='0'){
						$message      .= "<span style='color:#333;font-size:12px;'>Transfer Tepat hingga 3 digit terakhir</span><br>
										<span style='color:#999;font-size:12px;'>Perbedaan nilai transfer akan menghambat proses verifikasi</span><br><br>

										<b>METODE PEMBAYARAN :</b><br>
										Silakan melakukan pembayaran ke salah satu rekening di bawah ini:<br><br>

										<table style='width:100%'>";
										$rekening = $this->model_app->view('rb_rekening');
										foreach ($rekening->result_array() as $row){
											$message  .= "<tr><td style='width:88px'><img style='width:69px;height:auto;line-height:100%;outline:none;text-decoration:none;border:0 none' src='".base_url()."asset/images/$row[gambar]'></td>
												<td width='120px' colspan='2'>
												$row[nama_bank], <br>A/N : $row[pemilik_rekening], <br><b>$row[no_rekening] </b></td></tr>";
										}
										$message  .= "</table>

						<br>Rincian pemesananmu dapat dilihat di halaman <a style='text-decoration:none; color:green' target='_BLANK' href='".base_url()."konfirmasi/tracking/$kode_transaksi'>detail transaksi</a>, <br>
						Sudah melakukan pembayaran namun orderan belum terproses? Konfirmasi Pembayaran anda <a href='".base_url()."konfirmasi/index?kode=$kode_transaksi'>disini</a>.</body></html> \n";
					}else{
						$message      .= "Status Pesanan : <b>".status($rowc['proses'])."</b>
						<br>Rincian pemesananmu dapat dilihat di halaman <a style='text-decoration:none; color:green' target='_BLANK' href='".base_url()."konfirmasi/tracking/$kode_transaksi'>detail transaksi</a>";
					}

				$isi_pesan = "*$iden[pengirim_email]* - Tracking Orders #$kode_transaksi : 

";
				$nou = 1;
				foreach ($record_detail->result_array() as $det) {
					$sub_total = $det['jumlah']*($det['harga_jual']-$det['diskon']);
					$isi_pesan  .= "*$nou.* $det[nama_produk] : *$det[jumlah] x ".rupiah($det['harga_jual']-$det['diskon'])."*

";
					$nou++;
				}
				$isi_pesan .= "Total Belanja + Ongkir : *Rp ".rupiah($total_tagihan_akhir)."*";
if ($rowc['proses']=='0'){
	$isi_pesan .= " Silakan melakukan pembayaran ke salah satu rekening di bawah ini:

";
			$norek = 1;
			foreach ($rekening->result_array() as $row){
				$isi_pesan .= "$norek. *$row[nama_bank]*, 
A/N : $row[pemilik_rekening], *$row[no_rekening]*
";
			$norek++;
			}
}else{
	$isi_pesan .= "
	
Status Pesanan : *".status($rowc['proses'])."*";
}		

				echo kirim_email($subject,$message,$kons['email']);
				$this->model_app->wa(format_telpon($kons['no_hp']),$isi_pesan);
			}
				$this->template->load(template().'/template',template().'/reseller/view_tracking_view',$data);
			}else{
				redirect('konfirmasi/tracking');
			}
		}else{
			$data['title'] = 'Tracking Order';
			$data['description'] = description();
			$data['keywords'] = keywords();
			$this->template->load(template().'/template',template().'/reseller/view_tracking',$data);
		}
	}

	public function bayar(){
		$cek_kodetrx = $this->db->query("SELECT * FROM rb_penjualan_otomatis where kode_transaksi='".$this->input->get('inv',TRUE)."' AND pembayaran is NULL");
		if ($cek_kodetrx->num_rows()>=1){
			$record = $this->db->query("SELECT a.kode_transaksi, b.*, c.nama_produk, c.gambar, c.satuan, c.berat, c.produk_seo, d.nama_reseller FROM `rb_penjualan` a JOIN rb_penjualan_detail b ON a.id_penjualan=b.id_penjualan JOIN rb_produk c ON b.id_produk=c.id_produk JOIN rb_reseller d ON c.id_reseller=d.id_reseller where a.kode_transaksi='".$this->input->get('inv',TRUE)."'");
			foreach ($record->result_array() as $row){
			    $kupon = $this->db->query("SELECT nilai FROM rb_penjualan_kupon where id_penjualan_detail='$row[id_penjualan_detail]'")->row_array();
				$catatan = explode('||',$row['keterangan_order']);
				$sub_total = ($row['harga_jual']-$row['diskon'])-$kupon['nilai'];
				if ($catatan[1]!=''){
					$noo = 1;
					$ex = explode(';',$catatan[1]);
					for ($ii=0; $ii < count($ex) ; $ii++) { 
						$exx = explode('|',$ex[$ii]);
						$variasi_terpilih[] = trim($exx[1]);
					}
					$variasi_tersimpan = implode(', ',$variasi_terpilih);
				}

				$produk[] = $row['nama_produk'];
				$jumlah[] = $row['jumlah'];
				$harga[] = $sub_total;
				$catatan_order[] = '';
			}

			$ong = $this->db->query("SELECT sum(ongkir) as total_ongkir, sum(fee_admin)/count(*) as fee_admin FROM `rb_penjualan` where kode_transaksi='".$this->input->get('inv',TRUE)."'")->row_array();
			if ($ong['total_ongkir']>0){
				array_push($produk,"Total Ongkir #".$this->input->get('inv',TRUE));
				array_push($harga,$ong['total_ongkir']);
				array_push($jumlah,"1");
				array_push($catatan_order,"");
			}

			// Masukkan Fee Admin jika ada...
			if ($ong['fee_admin']>'0'){
				array_push($produk,"Fee Admin");
				array_push($harga,$ong['fee_admin']);
				array_push($jumlah,"1");
				array_push($catatan_order,"");
			}
			
			// Fee Transaksi Dibebankan ke Pembeli Jika menggunakan Pembayaran via Payment Gateway
			if (config('ipaymu_fee')>0){
				array_push($produk,"Fee Transaksi");
				array_push($harga,config('ipaymu_fee'));
				array_push($jumlah,"1");
				array_push($catatan_order,"");
			}

			/** Initialize Config */
			$conf['product'] = $produk;
			$conf['price'] = $harga;
			$conf['quantity'] = $jumlah;
			$conf['comments'] = $catatan_order;

			$conf['ureturn'] = site_url('konfirmasi/tracking/'.$this->input->get('inv',TRUE));
			$conf['unotify'] = site_url('konfirmasi/unotify');
        	$conf['ucancel'] = site_url('konfirmasi/ucancel');
			$conf['uniqid'] = uniqid();
			
			/** Load Lib and Init */
			$this->load->library('ipaymu', $conf);
			/** Call Response */
			$response = $this->ipaymu->response();

			/** Result Response */
			$resp = json_decode($response);
			$data = array('catatan'=>$resp->sessionID);
			$where = array('kode_transaksi' => $this->input->get('inv',TRUE));
			$this->model_app->update('rb_penjualan_otomatis', $data, $where);
			redirect($resp->url);
			//print_r($resp);
		}else{
			echo $this->session->set_flashdata('message', '<div class="alert alert-success"><center>Maaf, Status Pembayaran untuk Orderan ini sudah Lunas!</center></div>');
			redirect('konfirmasi/tracking/'.$this->input->get('inv',TRUE));
		}
	}
	
	public function unotify(){
		if ($this->input->post('status')=='berhasil'){ $status_trx = '1'; }else{ $status_trx = ''; }
		$datax = array('pembayaran'=>$status_trx,'status_trx'=>$this->input->post('status'),'penampung'=>$this->input->post('via'));
		$where = array('catatan' =>$this->input->post('trx_id'));
		$this->model_app->update('rb_penjualan_otomatis', $datax, $where);

		$idp = $this->db->query("SELECT kode_transaksi FROM rb_penjualan_otomatis where catatan='".$this->input->post('trx_id')."'")->row_array();
		$data_idp = array('proses'=>'1');
   		$where_idp = array('kode_transaksi'=>$idp['kode_transaksi'],'status_pembeli'=>'konsumen');
   		$this->model_app->update('rb_penjualan', $data_idp, $where_idp);

		if ($this->input->post('status')=='berhasil'){
		    $mut = $this->db->query("SELECT kode_transaksi FROM rb_penjualan_otomatis where catatan='".$this->input->post('trx_id')."'")->row_array();
		    $tgl_kirim = date("d-m-Y H:i:s");
            $logo = $this->db->query("SELECT * FROM logo ORDER BY id_logo DESC LIMIT 1")->row_array();
            $iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();
            $cek_kons = $this->db->query("SELECT b.id_pembeli FROM rb_penjualan_otomatis a JOIN rb_penjualan b ON a.kode_transaksi=b.kode_transaksi where b.status_pembeli='konsumen' AND a.kode_transaksi='$mut[kode_transaksi]' GROUP BY b.id_pembeli")->row_array();
            $kons = $this->model_reseller->profile_konsumen($cek_kons['id_pembeli'])->row_array();
            $subject      = "$iden[pengirim_email] - Pembayaran Sukses,..";
            $message  = "<html><body><img src='".base_url()."asset/logo/$logo[gambar]' style='height:87px'><br>
                                    <span style='font-size:18px; color:green'>Selamat Pembayaran anda Sukses.</span><br>
                                    Hai $kons[nama_lengkap]! Kami telah menerima pembayaran untuk pesanan anda #$mut[kode_transaksi]. <br>
                                    Kami juga telah menginformasikan kepada seller/penjual agar segera memproses pesanannya.<br> 
                                    Silahkan cek disini untuk perkembangan status pesanan : <a href='".base_url()."konfirmasi/tracking/$mut[kode_transaksi]'>Disini</a>.<br><br>

                                    Terima kasih telah berbelanja di $iden[url].</body></html> \n";
                                    
            $isi_pesan_pembeli = "*$iden[pengirim_email]* - Haloo Bpk/Ibk. *$kons[nama_lengkap]*, Pembayaran order anda dengan No Invoice #$mut[kode_transaksi] Telah kami terima, yuk cek disini ".base_url()."konfirmasi/tracking/$mut[kode_transaksi],..";
			
			$this->model_app->wa(format_telpon($kons['no_hp']),$isi_pesan_pembeli);
            echo kirim_email($subject,$message,$kons['email']);


            $penjual = $this->db->query("SELECT b.id_penjual FROM rb_penjualan_otomatis a JOIN rb_penjualan b ON a.kode_transaksi=b.kode_transaksi where b.status_pembeli='konsumen' AND a.kode_transaksi='$mut[kode_transaksi]' AND b.kode_kurir!='0' GROUP BY b.id_penjual");
            foreach ($penjual->result_array() as $row){
                $subject_ress      = "$iden[pengirim_email] - Pembayaran dari Pembeli Sukses,..";
                $ress = $this->db->query("SELECT a.nama_reseller, a.no_telpon, b.email FROM rb_reseller a JOIN rb_konsumen b ON a.id_konsumen=b.id_konsumen where a.id_reseller='$row[id_penjual]'")->row_array();

                $message_ress  = "<html><body><img src='".base_url()."asset/logo/$logo[gambar]' style='height:87px'><br>
                                                <span style='font-size:18px; color:green'>Selamat Pembayaran dari pembeli Sukses.</span><br>
                                                Hai $ress[nama_reseller]! Kami telah menerima pembayaran pesanan dari konsumen anda untuk Invoice <a href='".base_url()."konfirmasi/tracking/$mut[kode_transaksi]'>#$mut[kode_transaksi]</a>. <br><br>
                                                
                                                Harap segera memproses pesanannya, jika dalam 1 x 24 jam belum diproses maka transaksi ini akan otomatis dibatalkan..<br><br>

                                                Terima kasih telah berjualan di $iden[url].</body></html> \n";
                $isi_pesan_penjual = "*$iden[pengirim_email]* - Hai *$ress[nama_reseller]*, Kami telah menerima pembayaran pesanan dari konsumen anda untuk Invoice #$mut[kode_transaksi]

Harap segera memproses pesanannya, jika dalam 1 x 24 jam belum diproses maka transaksi ini akan otomatis dibatalkan..";
			
			    $this->model_app->wa(format_telpon($ress['no_telpon']),$isi_pesan_penjual);
			    
                echo kirim_email($subject_ress,$message_ress,$ress['email']);
            }
		}
	}
}
