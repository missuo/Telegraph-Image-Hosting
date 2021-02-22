# Telegraph-Image-Hosting
利用Telegraph搭建免费图床

## 写在前面
利用Telegraph搭建的图床，有什么优势和缺陷呢？

**优势：免费、无限流量、无限容量、无审查（懂得都懂哈）**

**缺陷：限制了单文件最多5MB左右**

## 原理
有用过[Telegraph](https://telegra.ph)的朋友，应该都知道发布是不需要登录的，匿名发布即可，上传图片仅需点图片按钮。通过抓包分析发现向[Telegraph-API](https://telegra.ph/upload)发送POST请求，即可返回外链。
~~~
请求头：
Content-Type: multipart/form-data

请求体：
Key-Value: file

返回：
[
    {
        "src": "/file/a672a2690e15c7d86435d.jpg"
    }
]
~~~


