# InWeCrypTO API文档

# 简介

> RESTful API
> 版本 v2
> 返回数据格式,如下:
```json
    {
        "msg": "操作成功",
        "code": 4000,
        "url": "",
        "data":
    }
```
> 请求头包含如下信息:
```
    {
        "Authorization": "", // 用户认证信息
    }
```
> 请求url: http://xxx.xxx.xx/v2/
> 所有分页列表,可以用per_page参数控制分页大小,默认10

## 1\. 用户管理

### 1.1 管理员用户
#### 1.1.1 获取登录验证码
- **请求地址:** /get_code
- **请求参数:** [post]
- **返回数据:**
    ```
        {
            phone : "手机号"
        }
    ```
#### 1.1.2 获取用户认证
- **请求地址:** /login
- **请求参数:** [post]
    ```
    {
        phone : "手机号",
        code : "验证码"
    }
    ```
- **返回数据:**
    ```
    {
        "id": 1,
        "name": "what-00",
        "phone": "18180895200",
        "img": null,
        "email": "what-00@qq.com",
        "created_at": "2018-02-01 08:27:23",
        "updated_at": "2018-02-01 08:27:23",
        "token": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6Ly9pbndlY3J5cHRvLmFkbS92Mi9sb2dpbiIsImlhdCI6MTUxNzg4NzYyNywiZXhwIjoxNTE3OTIzNjI3LCJuYmYiOjE1MTc4ODc2MjcsImp0aSI6Ik1jYXlCeGtpUnhGeXg2eXkifQ.iCdcbQc5gr1ZvmIbejuO8J10G7Dkh9e2Khpp7w5mBGQ",
        "menu_group": { // 菜单权限
            "id": 1,
            "group_id": 1,
            "user_id": 1,
            "created_at": "2018-02-01 08:45:15",
            "updated_at": "2018-02-01 08:45:15",
            "menus": [
                {
                    "id": 1,
                    "name": "测试",
                    "desc": null,
                    "url": "/test",
                    "lang": "zh",
                    "enable": 1,
                    "created_at": "2018-02-01 06:56:14",
                    "updated_at": "2018-02-01 06:57:20",
                    "pivot": {
                        "group_id": 1,
                        "menu_id": 1
                    }
                }
            ]
        }
    }
    ```
#### 1.1.3 管理用户
- **请求地址:** /admin
- **请求参数:** [get, post, put, delete]
    ```
    {
        "id": 1,
        "name": "what-00",
        "phone": "18180895200",
        "img": null,
        "email": "what-00@qq.com",
        "created_at": "2018-02-01 08:27:23",
        "updated_at": "2018-02-01 08:27:23",
        "menu_group": {
            "id": 1,
            "group_id": 1,
            "user_id": 1,
            "created_at": "2018-02-01 08:45:15",
            "updated_at": "2018-02-01 08:45:15",
            "info": {
                "id": 1,
                "group_name": "测试测试组", // 用户角色
                "enable": 1,
                "created_at": "2018-02-01 07:09:04",
                "updated_at": "2018-02-01 07:09:04"
            }
        }
    }
    ```
- **返回数据:**

#### 1.1.4 修改管理员权限
- **请求地址:** /admin/:admin_id/menu_groups
- **请求参数:** [put]
    ```
    admin_id  // 管理员ID
    {
        group_id: 1 // 菜单权限组ID
    }
    ```
#### 1.1.5 用户菜单权限及用户详细信息
- **请求地址:** /admin/:admin_id
- **请求参数:** [get]
- **返回数据:**
    ```
    {
        "id": 1,
        "name": "what-00",
        "phone": "18180895200",
        "img": null,
        "email": "what-00@qq.com",
        "created_at": "2018-02-01 08:27:23",
        "updated_at": "2018-02-01 08:27:23",
        "menu_group": {
            "id": 1,
            "group_id": 1,
            "user_id": 1,
            "created_at": "2018-02-01 08:45:15",
            "updated_at": "2018-02-01 08:45:15",
            "menus": [
                {
                    "id": 1,
                    "name": "测试",
                    "desc": null,
                    "url": "/test",
                    "lang": "zh",
                    "enable": 1,
                    "created_at": "2018-02-01 06:56:14",
                    "updated_at": "2018-02-01 06:57:20",
                    "pivot": {
                        "group_id": 1,
                        "menu_id": 1
                    }
                }
            ]
        }
    }
    ```

### 1.2 普通用户
#### 1.2.1 管理用户
- **请求地址:** /user
- **请求参数:** [get, post, put, delete]
    ```
    {
        "id": 10,
        "name": "11",
        "img": null,
        "email": "530743734@qq.com",
        "created_at": "2018-01-18 02:54:42",
        "updated_at": "2018-01-18 02:54:42",
        "lang": "zh",
        "enable": true
    }
    ```
- **返回数据:**
#### 1.2.2 发送消息
- **请求地址:** /user/:user_id/send_sys_msg
- **请求参数:** [post]
    ```
    {
        msg: "" // 消息内容
    }
    ```
- **返回数据:**

#### 1.2.3 冻结/解冻账户
- **请求地址:** /user/:user_id/frozen
- **请求参数:** [post]
    ```
    user_id // 用户ID
    {
        enable: true // true 解冻, false 冻结
    }
    ```
- **返回数据:**

---

## 2. 项目

### 2.1 项目列表
- **请求地址:** /category
- **请求参数:** [get, post, put, delete]
    ```
    [get] 搜索条件
    ?type=1 // 项目类型, 1.交易中,2.众筹中,3.即将众筹,4.众筹结束
    ?keyword= // 项目关键字, 区分大小写

    ?getKeys // 获取项目列表,key=>value 类型
    ```
- **返回数据:**
    ```
    {
        "id": 10,
        "type": 1,
        "name": "Trinity",
        "long_name": "Trinity",
        "desc": null,
        "img": "https://trinity.tech/images/footer_logo.png",
        "url": null,
        "website": "https://trinity.tech/", // 项目官网
        "unit": "TNC", // 项目符号
        "token_holder": null,
        "room_id": "39099342848001",
        "sort": 0,
        "is_hot": false,
        "is_top": false, // 推荐搜索
        "is_scroll": true, // 右上角滚动行情
        "enable": 1,
        "created_at": "2018-01-23 05:47:19",
        "updated_at": "2018-01-23 05:47:19",
        "cover_img": null, // 手机App项目封面图片
    }
    ```

### 2.2 项目ICO介绍
- **请求地址:** /category/:c_id/desc
- **请求参数:** [get, post, put, delete]
    ```
    c_id // 项目ID
    ?lang=en // 语言
    ```
- **返回数据:**
    ```
    [{
        "id": 7,
        "category_id": 10,
        "start_at": "2018-01-23 06:00:00", // ICO开始时间
        "end_at": "2018-02-23 06:00:00",  // ICO介绍时间
        "content": "英文1ico介绍", // ICO介绍内容
        "lang": "en",   // ICO介绍语言
        "enable": 1,
        "created_at": "2018-01-23 07:00:49",
        "updated_at": "2018-01-23 07:05:12"
    }]
    ```

### 2.3 项目浏览器
- **请求地址:** /category/:c_id/explorer
- **请求参数:** [get, post, put, delete]
    `c_id // 项目ID`
    ```
    [post]

    "params" : [
        {
            "name":"",
            "url":""
        }
    ]
    ```
- **返回数据:**
    ```
    [{
        "id": 9,
        "category_id": 10,
        "name": "NEO Tracker", // 浏览器名称
        "desc": null, // 描述
        "url": "",  // 浏览器地址
        "sort": 0,  // 排序
        "enable": 1,
        "created_at": "2018-01-23 07:22:46",
        "updated_at": "2018-01-23 07:23:37"
    }]
    ```

### 2.4 项目媒体
- **请求地址:** /category/:c_id/media
- **请求参数:** [get, post, put, delete]
    `c_id // 项目ID`
    ```
    [post]

    "params" : [
        {
            "name":"",
            "url":"",
            "img":"", // 媒体图标
            "qr_img": "" // 媒体二维码图片,如微信二维码
        }
    ]
    ```
- **返回数据:**
    ```
    [{
        "id": 10,
        "category_id": 10,
        "img": "",   // 媒体图片链接
        "name": "Trinity Official English Channel", // 媒体名称
        "desc": null, // 描述
        "url": "",  // 跳转链接
        "qr_img": null, // 媒体二维码图片地址, 如微信
        "sort": 0, // 排序
        "enable": 1,
        "created_at": "2018-01-23 07:38:03",
        "updated_at": "2018-01-23 07:40:23"
    }]
    ```

### 2.5 项目结构
- **请求地址:** /category/:c_id/structure
- **请求参数:** [get, post, put, delete]
    ```
    c_id // 项目ID
    ?lang=en // 语言

    [post]

    "params" : [
        {
            "percentage":"50", // 结构比例
            "color_name":"",  // 结构名称
            "color_value":"", // 结构颜色
            "desc":"", // 结构描述
            "lang": "" // 结构语言
        }
    ]
    ```
- **返回数据:**
    ```
    [{
        "id": 10,
        "category_id": 10,
        "percentage": 25,     // 结构占比
        "color_name": null,   // 结构名称
        "color_value": "#FF713D",   // 结构验收
        "desc": "en 用于团队建设 ~", // 结构描述
        "lang": "en", // 结构描述语言类型
        "sort": 0,
        "enable": 1,
        "created_at": "2018-01-23 08:08:40",
        "updated_at": "2018-01-23 08:11:10"
    }]
    ```

### 2.6 项目钱包
- **请求地址:** /category/:c_id/wallet
- **请求参数:** [get, post, put, delete]
    `c_id // 项目ID`
    ```
    [post]

    "params" : [
        {
            "name":"",
            "url":""
        }
    ]
    ```
- **返回数据:**
    ```
    [{
        "id": 3,
        "category_id": 10,
        "name": "Neon Wallet", // 项目钱包名称
        "desc": null,
        "url": "", // 项目钱包链接
        "sort": 0, // 排序
        "enable": 1,
        "created_at": "2018-01-23 07:22:46",
        "updated_at": "2018-01-23 07:23:37"
    }]
    ```

### 2.7 项目标签
- **请求地址:** /category/:c_id/industry
- **请求参数:** [get, post, delete]
    ```
    c_id // 项目ID
    ?lang=en // 语言

    [post]
    {
        "name":"",
        "lang":""
    }
    ```
- **返回数据:**
    ```
    [{
        "id": 6,
        "category_id": 10,
        "name": "Blockchain Service",   // 项目标签
        "desc": null,
        "lang": "en",   // 项目标签语言类型
        "created_at": "2018-01-24 01:37:45",
        "updated_at": "2018-01-24 01:37:45"
    }]
    ```

### 2.8 项目介绍
- **请求地址:** /category/:c_id/presentation
- **请求参数:** [get, post, put, delete]
    ```
    c_id // 项目ID
    ?lang=en // 语言
    ```
- **返回数据:**
    ```
    [{
        "id": 360,
        "title": "Trinity通过状态通道技术对NEO进行链下扩容。", // 项目介绍标题
        "desc": "",
        "content": "", // 项目介绍内容
        "lang": "zh",   // 项目介绍语言
        "created_at": "2018-01-28 15:14:00",
        "updated_at": "2018-01-28 15:14:00"
    }]
    ```
### 2.9 项目空投
- **请求地址:** /category/candy_bow
- **请求参数:** [get, post, put, delete]
    ```
    ?category_id // 项目ID
    ?lang=en // 语言
    ?year= // 年
    ?month= // 月
    ?day= // 日
    ```
- **返回数据:**
    ```
    [
        {
            "id": 8,
            "category_id": 7,
            "enable": true,
            "sort": 0,  // 排序
            "name": "en 必读", // 空投名称
            "img": null,    // 空投图片链接
            "desc": "测试 en",    // 空投描述
            "content": "", // 空投内容
            "url": null,
            "year": 2018, // 空投时间, 年
            "month": 1, // 空投时间, 月
            "day": 27,  // 空头时间, 日
            "is_scroll": 0,  // 是否固定显示
            "created_at": null,
            "updated_at": null,
            "lang": "en",    // 空投显示语言
            "category": { // 空投项目
                "id": 7,
                "name": "Zilliqa",
                "long_name": "Zilliqa",
                "unit": "ZIL",
                "type": 1,
                "type_name": "Trading"
            }
        }
    ]
    ```

## 3. 文章资讯
### 3.1 文章管理
- **请求地址:** /article
- **请求参数:** [get, post, put, delete]
    ```
    [get] // 列表筛选
    ?type= 文章类型
    ?lang= 文章语言
    ?keyword= 文章title关键字
    ?is_scroll=0 // 筛选轮播, 1是轮播,0不是轮播,空 所有
    ?is_sole=0  // 筛选原创文章, 1原创,0不是原创,空 所有

    [post,put]
    当文章类型type=6的时候,url为文件上传后的链接,必填,content可以为空,url请用/upload/file?get_oss_policy
    ```
- **返回数据:**
    ```
    [{
        "id": 64,
        "category_id": 4,   // 文章所属项目
        "type": 1,  // 文章类型 1 文本,2图文,3视频,4,trading,6,文件
        "title": "瑞讯银行推出比特币交易所交易产品", // 文章标题
        "author": null, // 文章作者
        "img": null,    // 文章封面
        "url": "",  // 视屏外链
        "video": null,  // 本地视屏链接
        "desc": "瑞讯银行推出比特币交易所交易产品", // 文章描述
        "sort": 4,  // 文章排序
        "click_rate": 199,  // 点击量
        "lang": "zh",   // 文章语言
        "is_hot": true, // 是否热点
        "is_top": true, // 是否置顶
        "is_scroll": false, // 是否轮播
        "is_sole": false,   // 是否原创
        "enable": false,
        "created_at": "2017-11-16 11:35:33",
        "updated_at": "2017-12-28 07:48:35"
    }]
    ```
### 3.2 文章类型
- **请求地址:** /article/types
- **请求参数:** [get]
- **返回数据:**
    ```
    {
        "1": "文本",
        "2": "图文",
        "3": "视频",
        "4": "交易观点"
    }
    ```
### 3.3 文章项目关联
- **请求地址:** /article/:art_id/cc
- **请求参数:** [get, post]
    ```
    art_id // 文章ID
    {
        'category_ids':[] // 关联的项目ID
    }
    ```
- **返回数据:**
    ```
    {
        "id": 18,
        "article_id": 65,   // 文章ID
        "category_id": 3,   // 项目ID
        "created_at": "2018-01-26 07:52:34",
        "updated_at": "2018-01-26 07:52:34",
        "category": {
                "id": 1,
                "name": "Ethereum", // 关联项目名称
                "img": "",  // 关联项目图片
            }
    }
    ```

### 3.4 文章标签关联
- **请求地址:** /article/:art_id/tags
- **请求参数:** [get, post]
    ```
    art_id // 文章ID
    {
        'tag_ids':[] // 关联的标签ID
    }
    ```
- **返回数据:**
    ```
    {
        "id": 1,
        "name": "技术追踪", // 标签名称
        "desc": "测试 描述",    // 标签描述
        "enable": true,
        "created_at": "2018-01-18 18:18:00",
        "updated_at": "2018-01-18 18:18:00",
        "lang": "zh",   // 标签语言
        "sort": 8,      // 标签排序
    }
    ```
### 3.5 文章标签管理
- **请求地址:** /article/tags
- **请求参数:** [get, post, put, delete]
- **返回数据:**
    ```
    {
         "id": 10,
         "name": "Media Report",    // 标签名称
         "desc": "biao qian",   // 标签描述
         "enable": true,
         "created_at": "2018-01-24 02:05:39",
         "updated_at": "2018-01-24 02:53:17",
         "lang": "en",  // 标签语言
         "sort": 10 // 标签排序
    }
    ```

### 3.6 文章评论
- **请求地址:** /article/comment
- **请求参数:** [get, put, delete]
    ```
    [put] 修改评论内容
    {
        "content": "hhidhfiehfhofofo"
    }
    ```
- **返回数据:**
    ```
    [
        {
            "id": 40,
            "article_id": 295,
            "user_id": 33,
            "content": "hhidhfiehfhofofo",
            "ip": "222.211.212.149",
            "enable": true, // 是否评论是否被删除
            "created_at": "2018-01-28 16:55:30",
            "updated_at": "2018-01-28 16:55:30",
            "article": {
                "id": 295,
                "title": "EOS Channel – 专注于EOS资讯与技术"
            },
            "user": null // 评论用户信息
        }
    ]
    ```

## 4. 广告管理
- **请求地址:** /ads
- **请求参数:** [get, post, put, delete]
- **返回数据:**
    ```
    {
        "id": 4,
        "name": "InWe App",
        "desc": "InWe App",
        "img": "", // 广告图片
        "url": "/app",  // 跳转链接
        "sort": 0,  // 广告排序
        "enable": true,
        "created_at": "2018-01-16 18:00:00",
        "updated_at": "2018-01-16 18:00:00",
        "lang": "zh",    // 广告语言
        "type": 1, // 1小方块,2长方块
    }
    ```
## 5. 交易所公告
- **请求地址:** /ex_notice
- **请求参数:** [get, post, put, delete]
- **返回数据:**
    ```
    {
        "id": 135,
        "source_name": "gate.io",   // 公告来源名称
        "source_url": "https://gate.io/article/16352", // 公告来源链接
        "url": null,    // 公告跳转链接
        "lang": "zh",   // 公告语言
        "is_hot": false,
        "is_top": false,
        "is_scroll": false,
        "enable": true,
        "created_at": "2018-01-16 17:47:00",
        "updated_at": "2018-01-16 17:47:00",
        "desc": "gate.io上线Zilliqa(ZIL) 和 RuffChain(RUFF)交易", // 公告标题
        "content": "" // 公告内容
    }
    ```
## 6. 搜索推荐关键字
- **请求地址:** /serach_keyword
- **请求参数:** [get, post, put, delete]
- **返回数据:**
    ```
    {
        "id": 1,
        "name": "测试",   // 关键字
        "desc": null,
        "enable": true,
        "lang": "zh",   // 关键字语言
        "created_at": "2018-01-27 14:56:00",
        "updated_at": "2018-01-27 14:56:00"
    }
    ```
## 7. 菜单权限管理
### 7.1 菜单管理
- **请求地址:** /menu
- **请求参数:** [get, post, put, delete]
- **返回数据:**
    ```
    {
        "id": 1,
        "name": "测试",   // 菜单名称
        "desc": null,   // 菜单描述
        "url": "/test", // 菜单链接
        "lang": "zh",
        "enable": 1,
        "created_at": "2018-02-01 06:56:14",
        "updated_at": "2018-02-01 06:57:20"
    }
    ```
### 7.2 菜单组管理
#### 7.2.1 菜单组管理
- **请求地址:** /menu_group
- **请求参数:** [get, post, put, delete]
- **返回数据:**
    ```
    {
        "id": 1,
        "group_name": "测试测试组",  // 菜单组名
        "enable": 1,
        "created_at": "2018-02-01 07:09:04",
        "updated_at": "2018-02-01 07:09:04"
    }
    ```
#### 7.2.2 菜单组成员管理
- **请求地址:** /menu_group/:group_id/menus
- **请求参数:** [get, post]
    ```
    group_id // 菜单组ID
    {
        menu_ids: [] // 菜单ID
    }
    ```
- **返回数据:**
    ```
    {
        "id": 1,
        "group_id": 1,
        "menu_id": 1,
        "created_at": "2018-02-01 07:33:55",
        "updated_at": "2018-02-01 07:33:55",
        "menu": {
            "id": 1,
            "name": "测试", // 菜单组成员名称
            "desc": null,
            "url": "/test",
            "lang": "zh",
            "enable": 1,
            "created_at": "2018-02-01 06:56:14",
            "updated_at": "2018-02-01 06:57:20"
        }
    }
    ```

## 8. 文件上传
- **请求地址:** /upload
- **请求参数:** [get]
    ```
    /img?get_oss_policy
    /video?get_oss_policy
    /file?get_oss_policy
    ```
- **返回数据:**

## 9. 资产管理
### 9.1 资产类型列表
- **请求地址:** /wallet_category
- **请求参数:** [get]
- **返回数据:**
    ```
    [
        {
            "id": 1,
            "name": "ETH", // 资产类型名称
            "img": null // 资产类型图片
        }
    ]
    ```
### 9.2 资产列表
- **请求地址:** /gnt_category
- **请求参数:** [get, post, put, delete]
    [get] // 搜索
    ?name= // 资产名称
- **返回数据:**
    ```
    [
        {
            "id": 1,
            "category_id": 1,
            "name": "OMG", // 资产名称
            "icon": "", // 资产图片
            "address": "0xd26114cd6ee289accf82350c8d8487fedb8a0c07", // 资产地址
            "gas": "420993", // gas
            "created_at": "2017-07-30 23:14:33",
            "updated_at": "2017-07-30 23:14:33",
            "wallet_category": {
                "id": 1,
                "name": "ETH", // 资产类型
                "img": null
            }
        }
    ]
    ```
