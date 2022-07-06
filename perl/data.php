<?php
echo  $_REQUEST["username"]." ".$_REQUEST["phone"]." ".$_REQUEST["name"]." ".$_REQUEST["email"]." ".$_REQUEST["alamat_lengkap"]." ".$_REQUEST["remark"]."\n";
$username=$_REQUEST["username"];
$phone=$_REQUEST["phone"];
$name=$_REQUEST["name"];
$email=$_REQUEST["email"];
$alamat_lengkap=$_REQUEST["alamat_lengkap"];
$remark=$_REQUEST["remark"];
/*
$hResult = mysql_query("select tb_bonus.member_id from tb_member,tb_bonus where tb_member.member_id=tb_bonus.member_id and bonusconfig_id=$bonusconfig_id and tb_bonus.member_id=$member_id");
list($member_id) = mysql_fetch_array($hResult);
if ($member_id=="")
{
    $query= "insert into tb_bonus(member_id,bonusconfig_id,bonus_value1,bonus_value2,bonus_total,bonus_saved,bonus_net,bonus_adm,bonus_status)
    values ('$member_id','$bonusconfig_id','$bonus_value1','$bonus_value2','$bonus_total','$bonus_saved','$bonus_net','$bonus_adm','$bonus_status')";
    mysql_query($query);
}else{
    mysql_query("update tb_bonus set bonus_value1=$bonus_value1,bonus_value2=$bonus_value2,bonus_total=$bonus_total,bonus_saved=$bonus_saved,bonus_net=$bonus_net,bonus_adm=$bonus_adm,bonus_status=$bonus_status where member_id='$member_id' and bonusconfig_id=$bonusconfig_id");
}*/

?>