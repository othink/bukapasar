<?php
  $file = fopen("shoppingrss.xml", "w");
  fwrite($file, '<?xml version="1.0" encoding="UTF-8"?> 
  <rss version="2.0">');
  fwrite($file, "<channel> 
				<title>RSS $iden[nama_website]</title> 
				<description>$iden[meta_deskripsi]</description>
				<link>$iden[url]</link> 
				<language>id-id</language>");

				foreach ($rss->result_array() as $row) {
					$title = cetak_meta($row['nama_produk'],0,255);
					$title = str_replace("&", "", $title); 
					$isi = str_replace("&", "", $row['tentang_produk']); 
					fwrite($file, "<item>
						                <title>".$title."</title>
						                <link>".base_url()."produk/detail/$row[produk_seo]</link>
						                <description>".strip_tags(html_entity_decode($isi))."</description>
					                </item>");
				}
  fwrite($file, "</channel>
  	</rss>");
  fclose($file);
?>