<?php
header("Content-type:application/json;charset=utf-8");
$file = $_FILES['file'];//得到传输的数据
//print_r($file);
//得到文件名称
$name = $file['name'];
$tmp_name = $file['tmp_name'];
$type = strtolower(substr($name,strrpos($name,'.')+1)); //得到文件类型，并且都转化成小写

//判断是否是通过HTTP POST上传的
if(!is_uploaded_file($file['tmp_name'])){
    //如果不是通过HTTP POST上传的
    return ;
}

    $ch = curl_init();
    $url = 'https://telegraph.work/upload';
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
        'code' => '200',
        'status' => 'success',
        'src' => 'https://telegraph.work'.$src
        );
    $result
    = json_encode($result,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    echo $result;
?>
