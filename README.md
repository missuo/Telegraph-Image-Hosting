<!--
 * @Author: Vincent Young
 * @Date: 2022-10-05 05:19:33
 * @LastEditors: Vincent Young
 * @LastEditTime: 2022-10-05 07:11:25
 * @FilePath: /Telegraph-Image-Hosting/README.md
 * @Telegram: https://t.me/missuo
 * 
 * Copyright © 2022 by Vincent, All Rights Reserved. 
-->
# Telegraph-Image-Hosting
Build a free image hosting with [Telegraph](https://telegra.ph)

**[中文](https://github.com/missuo/Telegraph-Image-Hosting/blob/main/README-OLD.md) document is no longer maintained**

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

![0e8a64f1538a6727edfda](https://telegraph.eowo.us/file/0e8a64f1538a6727edfda.png)

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

![7e28f947345cba3709835](https://telegraph.eowo.us/file/7e28f947345cba3709835.png)

2. Don't forget to click on the `Other fields` to add the header information.

![7516a06df832d1897922a](https://telegraph.eowo.us/file/7516a06df832d1897922a.png)

## Deploy the Web Site (Optional)
**Please excuse the fact that I can't write a front-end, so the front-end is very minimal. If you have written a nice front-end, please submit a `Pull Request` and I will appreciate it.**

1. Connect your server by SSH, then enter your web directory.
```shell
cd /www/wwwroot/xxx.com
```
2. Download the source code on GitHub with `Git`.
```shell
git clone https://github.com/missuo/Telegraph-Image-Hosting.git ./
```

3. Move all the files in `web` directory to web root directory.
```shell
mv web/* ./
```

4. Modify the line 34 to your domain in `api.php`

5. Have fun.

## Finally
There is no guarantee that it will work all the time, after all, Telegraph is on the GFW list. Use it and cherish it!

## LICENSE
[MIT License](https://raw.githubusercontent.com/missuo/Telegraph-Image-Hosting/main/LICENSE)
