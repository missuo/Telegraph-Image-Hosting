<!--
 * @Author: Vincent Young
 * @Date: 2022-10-05 05:19:33
 * @LastEditors: Vincent Young
 * @LastEditTime: 2023-10-14 23:17:50
 * @FilePath: /Telegraph-Image-Hosting/README.md
 * @Telegram: https://t.me/missuo
 * 
 * Copyright © 2022 by Vincent, All Rights Reserved. 
-->
# Telegraph-Image-Hosting
Build a free image hosting with [Telegraph](https://telegra.ph)

## Features
- Free
- Unlimited bandwidth (It will consume the traffic of your VPS)
- No censorship (NSFW image can be uploaded)
- You can upload any file, not just image files

## Disadvantages
- Single file cannot exceed 5MB
- Access speed may not be ideal in China Mainland

## Current Strategy (Recommended)
**Full enable HTTPS**
```
Client -> Cloudflare Server (Optional) -> Your VPS (Example: Oracle Cloud) -> Telegraph Servers (NL) 
```
## Demo Site
[missuo.ru](https://missuo.ru)

![bacd124a30fb599596f13](https://missuo.ru/file/bacd124a30fb599596f13.png)

## Deployment
### Preparation
- a Domain (Make sure it's not blocked by GFW)
- an overseas VPS (Make sure you can access [Telegraph](https://telegra.ph))
- Install Nginx

### Configure Nginx
**I don't talk too much about simple steps such as domain name resolution and SSL certificate application.**
```
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
```
**Note: Remove all other useless `location` related configuration and just keep the two above.**


## With uPic for macOS/iOS
**Thanks to my best friend [Svend](https://github.com/gee1k) (author of [uPic](https://github.com/gee1k/uPic)) for guiding the configuration scheme**

1. You can change the `API URL` and `Domain` to your own.

![7e28f947345cba3709835](https://missuo.ru/file/7e28f947345cba3709835.png)

2. Don't forget to click on the `Other fields` to add the header information.

![7516a06df832d1897922a](https://missuo.ru/file/7516a06df832d1897922a.png)


1. Connect your server by SSH, then enter your web directory.
```shell
cd /www/wwwroot/xxx.com
```
2. Download the source code on GitHub with `wget`.
```shell
wget https://raw.githubusercontent.com/missuo/Telegraph-Image-Hosting/main/index.html
```
3. Modify the API `https://missuo.ru/upload` to your domain.

4. Have fun.

## Finally
There is no guarantee that the Telegraph will work all the time, as it is on the GFW list. However, when you can use it, make sure to cherish it!

## Author
**Telegraph-Image-Hosting** © [Vincent Young](https://github.com/missuo), Released under the [MIT](./LICENSE) License.<br>
