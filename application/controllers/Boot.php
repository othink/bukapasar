<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Boot extends CI_Controller {
	function __construct(){
		parent::__construct();
	}
	function updatekurir(){
		$q = $this->db->query("SELECT subdistrict_id,subdistrict_name FROM tb_ro_subdistricts WHERE city_id='".$this->uri->segment(3)."'");
        $jeniskendaraan = "1";
		$provinsi_kota = "9:54|9:55";
		$posisi = $this->uri->segment(4);
		$ongkir = "25000";
		$ket = "BP Raider";
		foreach($q->result() as $row){
            echo $row->subdistrict_id." ".$row->subdistrict_name."<br>";
            $tujuan = $row->subdistrict_id;
            $data = array('id_jenis_kendaraan'=> $jeniskendaraan,
                        'provinsi_kota'=>$provinsi_kota,
                        'posisi'=>$posisi,
                        'tujuan'=>$tujuan,
                        'ongkir'=>$ongkir,
                        'keterangan'=>$ket);
            if($tujuan != $posisi){
            	$this->model_app->insert('rb_driver_ongkir',$data);
            }
			
        }
	}
	function articles(){
		$q = $this->db->query("SELECT * FROM article_sections");
        foreach($q->result() as $row){
            echo $row->name."";
            $url = sprintf(
                        'https://news.google.com/rss/topics/%s?hl=%s',
                        $row->google_news_topic,
                        $row->google_news_language ?: 'en'
                    );
            echo "==== ".$url."<br>";

        }

        $news = simplexml_load_file('https://news.google.com/rss/topics/CAAqJggKIiBDQkFTRWdvSkwyMHZNREY2Ykd3NEVnVmxiaTFIUWlnQVAB?hl=ID');

		foreach($news->channel->item as $object) {
			$guid = $object->guid;
			$checksum = hash('md5', $guid);
			echo $guid."<br>";
		    echo "<strong>" . $object->title . "</strong><br />";
		    echo "" . $object->images[0]->url."<br>".$object->source . "<br />";
		    echo "" . $object->pubDate . "<br />";
		    echo strip_tags($object->description) ."<br /><br />";
		}
	}
	function wablast(){
		//$q = $this->db->query("SELECT * FROM rb_konsumen WHERE wa_boot='0' limit 100");
		//$q = $this->db->query("SELECT * FROM rb_konsumen WHERE wa_boot='0' AND `kecamatan_id` = 1002");
		//$q = $this->db->query("SELECT * FROM `rb_konsumen` WHERE `kota_id` = 430");
		//$q = $this->db->query("SELECT * FROM `rb_konsumen` WHERE wa_boot='0' AND `kota_id` = 78");
		$q = $this->db->query("SELECT * FROM `rb_konsumen` WHERE `wa_boot` = 0 AND `remark` LIKE '%#JAWA TENGAH#KEBUMEN%'");
        foreach($q->result() as $row){
        	$phone = $row->no_hp;
        	$id_konsumen = $row->id_konsumen;
        	$pesan = "Maaf Kak *" . strtoupper($row->nama_lengkap) . "*, Kami dari *bukapasar.com* ingin membantu jualan online, PRODUK atau JASA. Kakak bisa kirim info produk dan gambarnya ke WA 0811171095 *GRATIS*. Maaf abaikan WA ini jika tidak berkenan";
			
			$this->model_app->wa($phone,$pesan);
			$this->db->query("UPDATE rb_konsumen SET wa_boot='1' where id_konsumen='$id_konsumen'");
            echo $id_konsumen." ". $pesan."";
            echo "<br><br>";

        }
	}
	function syndataiumkaddr(){
		$tgl_kirim = date("d-m-Y H:i:s");
		$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();

		$name = $this->uri->segment(4);
		$hSecret = fopen("asset/".$this->uri->segment(3)."/".$name.".txt","r");
		$nr = 1;
		while (!feof($hSecret))
		{
			$pString = trim(fgets($hSecret));
			if ($pString!="")
			{
				$datax = explode("#", $pString);
				$username = strtolower($datax[0]);
			    $phone = $datax[1];
			    $password = "123";
			    $cekhp = substr($phone, 0,2);
			    $lasthp = substr($phone, -3);
				$email = $username."".$lasthp."@bukapasar.com";
				$alamat_lengkap = $datax[9];
				$nama_lengkap = $datax[2];
				$cek_prov = $this->db->query("SELECT `province_id` FROM tb_ro_provinces where province_name LIKE '%".$datax[5]."%'")->row_array();
				$provinsi_id = "".$cek_prov[province_id];
				$cek_kab = $this->db->query("SELECT `city_id` FROM tb_ro_cities where province_id='$provinsi_id' AND city_name LIKE '%".$datax[6]."%'")->row_array();
				$kota_id = "".$cek_kab[city_id];
				$cek_kec = $this->db->query("SELECT `subdistrict_id` FROM tb_ro_subdistricts where city_id='$kota_id' AND subdistrict_name LIKE '%".$datax[6]."%'")->row_array();
				$kecamatan_id = "".$cek_kec[subdistrict_id];

			    //echo $nr." ".$username."#".$phone."#<br>";
				//echo $username."".$lasthp."#".$phone."#<br>";
					$cek_username = $this->db->query("SELECT * FROM rb_konsumen where username='".cetak($username)."'");
			        if ($cek_username->num_rows()<='0'){
			        	//echo $nr." ".$username."".$lasthp."#".$phone."#<br>";
			        	$data = array('username'=>cetak($username."".$lasthp),
								'password'=>hash("sha512", md5($password)),
								'nama_lengkap'=> $nama_lengkap,
								'email'=>cetak($email),
								'no_hp'=>cetak($phone),
								'alamat_lengkap'=>cetak($alamat_lengkap),
								'kecamatan_id'=>"".$kecamatan_id,
								'kota_id'=>"".$kota_id,
								'provinsi_id'=>"".$provinsi_id,
								'token'=> 'Y',
								'referral_id'=> '4',
								'wa_boot' => '0',
								'tanggal_daftar'=>date('Y-m-d H:i:s'));
			        	//print_r($data)."<br>";
						$this->model_app->insert('rb_konsumen',$data);
						$id = $this->db->insert_id();
						$kons = $this->model_reseller->profile_konsumen($id)->row_array();
						echo $nr." ".$username."".$lasthp."#".$phone."#".$nama_lengkap."#<br>";
						/*if($id != ""){
							$uname = $username."".$lasthp;
							$isi_pesan = "Hai *Kak ".$username."* Terima kasih telah mendukung UMKM Indonesia berjualan *Online* Silakan untuk melengkapi data diri sesuai KTP di ".base_url()."members/edit_profile username: *$uname* password: *$password* siapa tahu Kakak mendapatkan bantuan. Apa salahnya kalau mencoba ya, siapa tahu beruntung.";
							$this->model_app->wa(format_telpon($phone),$isi_pesan);
						}*/
			        }
				$nr++;
			}
		}

	}
	function syndataiumkx(){
		$tgl_kirim = date("d-m-Y H:i:s");
		$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();

		$name = $this->uri->segment(4);
		$hSecret = fopen("asset/".$this->uri->segment(3)."/".$name.".txt","r");
		$nr = 1;
		while (!feof($hSecret))
		{
			$pString = trim(fgets($hSecret));
			if ($pString!="")
			{
				$datax = explode("#", $pString);
				$username = strtolower($datax[0]);
			    $phone = $datax[1];
			    $password = "123";
			    $cekhp = substr($phone, 0,2);
			    $lasthp = substr($phone, -3);
				$email = $username."".$lasthp."@bukapasar.com";
			    //echo $nr." ".$username."#".$phone."#<br>";
				//echo $username."".$lasthp."#".$phone."#<br>";
					$cek_username = $this->db->query("SELECT * FROM rb_konsumen where username='".cetak($username)."' OR no_hp='".cetak($phone)."'");
			        if ($cek_username->num_rows()<='0'){
			        	echo $nr." ".$username."".$lasthp."#".$phone."#<br>";
			        	$data = array('username'=>cetak($username."".$lasthp),
								'password'=>hash("sha512", md5($password)),
								'nama_lengkap'=> $username,
								'email'=>cetak($email),
								'no_hp'=>cetak($phone),
								'token'=> 'Y',
								'referral_id'=> '4',
								'tanggal_daftar'=>date('Y-m-d H:i:s'));
						$this->model_app->insert('rb_konsumen',$data);
						$id = $this->db->insert_id();
						$kons = $this->model_reseller->profile_konsumen($id)->row_array();
						if($id != ""){
							$uname = $username."".$lasthp;
							$isi_pesan = "Hai *Kak ".$username."* Terima kasih telah mendukung UMKM Indonesia berjualan *Online* Silakan untuk melengkapi data diri sesuai KTP di ".base_url()."members/edit_profile username: *$uname* password: *$password* siapa tahu Kakak mendapatkan bantuan. Apa salahnya kalau mencoba ya, siapa tahu beruntung.";
							$this->model_app->wa(format_telpon($phone),$isi_pesan);
						}
			        }
				$nr++;
			}
		}

	}
	function syndataoggi(){
		$tgl_kirim = date("d-m-Y H:i:s");
		$iden = $this->db->query("SELECT * FROM identitas where id_identitas='1'")->row_array();

		$name = $this->uri->segment(4);
		$hSecret = fopen("asset/".$this->uri->segment(3)."/".$name.".txt","r");
		$nr = 1;
		while (!feof($hSecret))
		{
			$pString = trim(fgets($hSecret));
			if ($pString!="")
			{
				$datax = explode("#", $pString);
				$username = strtolower($datax[0]);
			    $email = $username."@mail.com";
			    $phone = format_telpon($datax[1]);
			    $password = "123";
			    //echo $nr." ".$username."#".$phone."#<br>";
			    //
				//if((strlen($datax[1]) > 8)&&(strlen($datax[1]) < 15)){
			        

			        $cek_username = $this->db->query("SELECT * FROM rb_konsumen where username='".cetak($username)."' OR no_hp='".cetak($phone)."'");
			        if ($cek_username->num_rows()<='0'){
			        	echo $nr." ".$username."#".$phone."#<br>";
			        	/*$data = array('username'=>cetak($username),
								'password'=>hash("sha512", md5($password)),
								'nama_lengkap'=> $username,
								'email'=>cetak($email),
								'no_hp'=>cetak($phone),
								'token'=> 'Y',
								'referral_id'=> '4',
								'tanggal_daftar'=>date('Y-m-d H:i:s'));
						$this->model_app->insert('rb_konsumen',$data);
						$id = $this->db->insert_id();
						$kons = $this->model_reseller->profile_konsumen($id)->row_array();
						if($id != ""){
							$isi_pesan = "Hai *Kak ".$username."* Terima kasih telah mendukung UKM Indonesia berjualan di *$iden[url]* Silakan untuk melengkapi data diri anda sesuai dengan identitas pada KTP di ".base_url()."members/edit_profile username: *$username* password: *$password* Siap mencari produk dari UKM di sekitar kita? ".base_url()."produk";
							$this->model_app->wa(format_telpon($phone),$isi_pesan);
						}*/
			        }

			    //}else{
			    //	echo $username."#".$phone."#<br>";
			    //}
			        $nr++;
			}
		}
		fclose($hSecret);
	}
}
