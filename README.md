# Telegraph-Image-Hosting
利用Telegraph搭建免费图床

## 写在前面
利用Telegraph搭建的图床，有什么优势和缺陷呢？

**优势：免费、无限流量、无限容量、无审查（懂得都懂哈）、理论上可以上传任意小于5MB的文件（不仅是图片格式）** 

**缺陷：限制了单文件最多5MB左右。由于用的是Cloudflare的免费CDN服务，速度可能很一般**

### 目前策略

用户端 -> Cloudflare节点 -> xTom Osaka(我的VPS) -> Telegraph Servers(NL) 全程启用强制HTTPS

## 直接使用
我已经搭建好了一个站点 [TelegraphImageHosting](https://telegraph.work)，您可以在网页端直接上传，但是一次只能上传一张小于5MB的照片。

当然你也可以搭配图片上传的一些工具，更好的体验。这里推荐一下：[uPic](https://github.com/gee1k/uPic)

在uPic作者 [Svend](https://github.com/gee1k/) 的帮助下，Telegraph图床已经可以完美在macOS和iOS上使用。

配置如图：
![uPic配置1](https://telegraph.work/file/f9e9d47869a16477187ef.png)
![uPic配置2](https://telegraph.work/file/eab7ab4db54e7c871d404.png)

**你所看到的图片来自uPic上传到Telegraph图床**

## 原理
有用过[Telegraph](https://telegra.ph)的朋友，应该都知道发布是不需要登录的，匿名发布即可，上传图片仅需点图片按钮。通过抓包分析发现向[Telegraph-API](https://telegra.ph/upload)发送POST请求，即可返回外链。
~~~
请求头：
Content-Type: multipart/form-data

请求体：
Key-Value: file
~~~

成功返回的格式如下：
~~~
[
    {
        "src": "/file/a672a2690e15c7d86435d.jpg"
    }
]
~~~
我们找到了上传图片的API，以及图片的外链通过格式为 
~~~
https://telegra.ph/file/a672a2690e15c7d86435d.jpg
~~~
后面的字符串会在POST请求中返回给你

## 搭建过程

### 准备

一个域名 

一台海外VPS

### 开始搭建
1. 将域名解析到你的VPS的IP，在VPS上安装Linux面板，例如宝塔，并且拥有Nginx和PHP。 
2. 新建一个站点，开启HTTPS，如果你愿意可以使用CloudflareCDN，我的图床已经启用CF。
3. 修改Nginx配置，反向代理upload和file即可。
~~~
server
{
    listen 80;
	listen 443 ssl http2;
    server_name telegraph.work;
    index index.php index.html index.htm default.php default.htm default.html;
    root /www/wwwroot/image;
    if ($server_port !~ 443){
        rewrite ^(/.*)$ https://$host$1 permanent;
    }
    ssl_certificate    /www/server/panel/vhost/cert/telegraph.work/fullchain.pem;
    ssl_certificate_key    /www/server/panel/vhost/cert/telegraph.work/privkey.pem;
    ssl_protocols TLSv1.1 TLSv1.2 TLSv1.3;
    ssl_ciphers EECDH+CHACHA20:EECDH+CHACHA20-draft:EECDH+AES128:RSA+AES128:EECDH+AES256:RSA+AES256:EECDH+3DES:RSA+3DES:!MD5;
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;
    add_header Strict-Transport-Security "max-age=31536000";
    error_page 497  https://$host$request_uri;

    #SSL-END
    
    #ERROR-PAGE-START  错误页配置，可以注释、删除或修改
    #error_page 404 /404.html;
    #error_page 502 /502.html;
    #ERROR-PAGE-END
    
    #PHP-INFO-START  PHP引用配置，可以注释或修改
    include enable-php-73.conf;
    #PHP-INFO-END
    
    #REWRITE-START URL重写规则引用,修改后将导致面板设置的伪静态规则失效
    include /www/server/panel/vhost/rewrite/telegraph.work.conf;
    #REWRITE-END
    
    location /upload {
                add_header Access-Control-Allow-Origin *;
                add_header Access-Control-Allow-Methods 'GET, POST, OPTIONS';
                add_header Access-Control-Allow-Headers 'DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Authorization';
                if ($request_method = 'OPTIONS') {
                	return 204;}
                proxy_pass https://telegra.ph/upload;
    }
    location /file {
                proxy_pass https://telegra.ph/file;
    }
  
    client_max_body_size 5m;
}
~~~
4. 到此你已经完成了接口和图片外链的配置。可以配合图床上传工具使用了。
5. 配置网页端的上传功能（我使用了PHP Curl POST请求，AJAX提交From）
PHP核心代码：
~~~
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
~~~
6. 当然你也可以直接下载我的代码，在你的网站根目录下解压，即可使用。
~~~
cd /www/wwwroot/image
wget https://github.com/missuo/Telegraph-Image-Hosting/archive/main.zip
unzip main.zip
cd Telegraph-Image-Hosting-main
mv * ../
~~~
## 最后的最后
**不保证能够一直使用，毕竟Telegraph在GFW名单中。且用且珍惜!**
