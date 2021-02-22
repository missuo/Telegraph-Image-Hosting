# Telegraph-Image-Hosting
利用Telegraph搭建免费图床

## 写在前面
利用Telegraph搭建的图床，有什么优势和缺陷呢？

**优势：免费、无限流量、无限容量、无审查（懂得都懂哈） 

**缺陷：限制了单文件最多5MB左右

## 直接使用
我已经搭建好了一个站点 [TelegraphImageHosting](https://telegraph.work)，您可以在网页端直接上传，但是一次只能上传一张小于5MB的照片。

当然你也可以搭配图片上传的一些工具，更好的体验。这里推荐一下：[uPic](https://github.com/gee1k/uPic)

在uPic作者 [Svend](https://github.com/gee1k/)的帮助下，Telegraph图床已经可以完美在macOS和iOS上使用。

配置如图：
![uPic配置1](https://telegraph.work/file/f9e9d47869a16477187ef.png)
![uPic配置2](https://telegraph.work/file/eab7ab4db54e7c871d404.png)

**你所看到的图片来自uPic上传到Telegraph

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



