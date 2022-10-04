<?php
/*
 * @Author: Vincent Young
 * @Date: 2022-10-05 05:18:48
 * @LastEditors: Vincent Young
 * @LastEditTime: 2022-10-05 07:08:37
 * @FilePath: /Telegraph-Image-Hosting/Web/api.php
 * @Telegram: https://t.me/missuo
 * 
 * Copyright © 2022 by Vincent, All Rights Reserved. 
 */
header("Content-type:application/json;charset=utf-8");
$file = $_FILES['file'];
$name = $file['name'];
$tmp_name = $file['tmp_name'];
$type = strtolower(substr($name,strrpos($name,'.')+1)); 
if(!is_uploaded_file($file['tmp_name'])){
    return ;
}
    $ch = curl_init();
    $url = 'https://telegra.ph/upload';
    $post_data = array('file' => new \CURLFile(realpath($tmp_name)));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1); //POST提交
    curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
    $data =curl_exec($ch);
    curl_close($ch);
    $res= json_decode($data,TRUE);
    $src = $res[0]['src'];
    $result = array(
        'code' => 200,
        'status' => 'success',
        'src' => 'https://missuo.ru'.$src
        );
    $result
    = json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    echo $result;
?>
