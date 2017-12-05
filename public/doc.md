# InWeCrypTO 后台管理API文档

[TOC]

* * *

# 简介
> 所有请求默认RESTful
> Api 版本 v1

## 1. 用户认证
### 1.1 获取用户token
- **请求地址:** /login
- **请求参数:** [post] *name,password*
- **返回数据:**
```
{
    "msg": "操作成功",
    "code": 4000,
    "url": "",
    "data": {
        "id": 2,
        "name": "admin",
        "email": "what-00@qq.com",
        "img": null,
        "phone": null,
        "created_at": "2017-12-01 13:11:35",
        "updated_at": "2017-12-01 13:11:35",
        "Token": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjIsImlzcyI6Imh0dHA6Ly9pbndlY3J5cHRvY21zLmFkbS9sb2dpbiIsImlhdCI6MTUxMjE5NzIxMCwiZXhwIjoxNTEyMjAwODEwLCJuYmYiOjE1MTIxOTcyMTAsImp0aSI6IjZQY3gwa2Z1c2p6YjBLZmsifQ.eD9kOigPP90mPKrG-fVf2gNHYEHKsm_Wcc58xvKsOfg"
    }
}
```

### 1.2 验证
> 每次请求在HTTP Header 中带入Token
> 如:Authorization: Bearer eyJ0...gNHYEHKsm_Wcc58xvKsOfg

* * *

## 2. 项目管理
### 2.1 项目管理
> /project/main?get_key // 获取所有项目键值列表
> /project/main?keyword=关键字 // 搜索项目
> /project/main?type=5 // 项目类型分类

- **请求地址:** /project/main
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
{
    "type": 5, // 项目类型
    "name": "", // 项目名称
    "long_name": "", // 项目全称
    "desc": "", // 项目twitter内容
    "img": "", // 项目图片
    "url": "", // 项目实时价格
    "sort": 0, // 排序越大越靠前
    "is_top": 0, // 主要排序
    "is_hot": 0, 
    "is_scroll": 1, // 是否显示到首页实时价格轮播
    "callback_fun": "\CategoryFun::getProjectDetail", // 后台项目数据处理方法
    "score": "8.7", // 项目评分
    "grid_type": 3, // 项目方块类型
    "color": "#2EC1C1", // 项目方块颜色
    "website": "", // 项目官网
    "ico_id": "",--暂无 // 关联ico
    "updated_at":"", // 项目修改时间
}
```

### 2.2 项目介绍
> 获取项目介绍列表 /project/detail?category_id=100

- **请求地址:** /project/detail
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
    	"category_id" : 100, // 项目ID
        "title": "", // 项目介绍标签title
        "desc": "", // 备注
        "content": "", // 项目介绍内容(富文本)
        "sort": 0, // 排序
    },
    ...
]
```

### 2.3 项目行情
> 获取项目行情列表 /project/time_price?category_id=100

- **请求地址:** /project/time_price
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
        "project_id": 100, // 项目ID
        "name": "美元", // 项目行情标签名
        "current_url": "ico/time_price/rdn/usdt", // 行情实时数据连接
        "k_line_data_url": "ico/currencies/rdn/usdt", // k线图数据连接
    },
    ...
]
```

### 2.4 市场行情
> 获取项目市场行情列表 /project/market?category_id=100

- **请求地址:** /project/market
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
        "project_id": 100, // 项目ID
        "name": "美元", // 市场行情标签名
        "url": "ico/markets/raiden/eth", // 市场行情数据来源连接
    },
    ...
]
```

### 2.4 项目浏览器
> 获取项目浏览器列表 /project/explorer?category_id=100

- **请求地址:** /project/explorer
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
        "project_id": 100, // 项目ID
        "name": "", // 项目浏览器名
        "img": "", // 项目浏览器图标
        "url": "", // 项目浏览器跳转连接
    },
    ...
]
```

### 2.5 项目钱包
> 获取项目钱包列表 /project/wallet?category_id=100

- **请求地址:** /project/wallet
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
        "project_id": 100, // 项目ID
        "name": "", // 项目钱包名
        "img": "", // 项目钱包图标
        "url": "", // 项目钱包跳转连接
    },
    ...
]
```

### 2.5 项目标签
> 获取项目标签列表 /project/tag?category_id=100
> 获取可用标签列表 /tag/main?get_key
> 如果是新增可用标签列表以外的标签,则需要向/tag/main中POST一条数据,取返回结果中的id

#### 2.5.1 获取项目标签列表
- **请求地址:** /project/tag
- **请求参数:** [get]
- **返回数据:**
```
[
    {
        "project_id": 100, // 项目ID
        "tag_id": 1, // 标签ID
        "tag_info": {
        	"name": "业内大佬" // 标签名称
        }
    },
    ...
]
```

#### 2.5.2 修改项目标签列表
- **请求地址:** /project/tag
- **请求参数:** [post] category_id,tag_ids
- **请求数据:**
```
{
	"category_id": "100", // 项目ID
    "tag_ids": [], // 标签列表id
}
```

### 2.6 项目媒体
> 获取项目钱包列表 /project/media?category_id=100

- **请求地址:** /project/media
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
        "project_id": 100, // 项目ID
        "name": "", // 项目媒体名
        "img": "", // 项目媒体图标
        "url": "", // 项目媒体跳转连接
        "desc": "", // 项目媒体描述
    },
    ...
]
```


* * *

## 3. ICO测评
### 3.1 ICO测评管理
- **请求地址:** /ico_assess/main
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
    	"project_id": 100, // 项目ID,如果没有可以则为0
        "ico_id": "2", // ICO ID
        "assess_status": "已上线", // 项目状态
        "website": "https://ethereum.org/", // 项目官网
        "url": "evaluating/#/detail?id=1", // 测评文章跳转连接
        "title": "菩提测评", // 测评列表
        "img": "", // 封面图片
        "desc": "Kyber Network 是一个具备高流动性的数字资产", // 测评简介
        "enable": 1, // 是否启用, 默认1(是)
        "risk_level_name": "中", // 风险等级
        "risk_level_color": "#33FFCC", // 等级颜色
        "ico_score": "7.9", // 项目评分
        "content": "", // 测评内容(富文本)
        "sort":0, // 测评排序
        "is_top": 0, // 置顶排序
        "white_paper_url": "", // 项目白皮书连接
        "recommend_level_name": "中", // 推荐等级
        "recommend_level_color": "#33FFCC" // 推荐等级颜色
    },
    ...
]
```

### 3.2 ICO测评 - 项目结构
> 获取ICO测评的项目结构列表 /ico_assess/structure?ico_assess_id=1

- **请求地址:** /ico_assess/structure
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
    	"ico_article_id": 1, // ico测评ID
        "percentage": 50, // 结构比率
        "color_name": "粉色", // 结构比率名称
        "color_value": "#EC87BF", // 比率颜色
        "desc": "5亿用于公开发行", // 结构比率说明
    },
    ...
]
```

### 3.3 ICO测评 - 发行情况
> 获取ICO测评的发行情况列表 /ico_assess/issue_info?ico_assess_id=1

- **请求地址:** /ico_assess/issue_info
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
    	"ico_article_id": 1, // ico测评ID
        "crowdfunding_start_at": "2017-09-10 00:00:00", // 众筹开始时间
        "crowdfunding_end_at": "2017-09-21 00:00:00", // 众筹结束时间
        "ico_circulation": "1000000000", // ico总发行量
        "ico_amount": "500000000", // ico量
        "ico_accept": "BTC/ETH", // 接受币种
        "ico_crowfunding_amount": "30000000", // 众筹金额
        "ico_price": "0.012", // 价格
        "url": "NULL",
        "ico_crowfunding_amount_unit": "美元" // 众筹金额单位
    },
    ...
]
```

### 3.4 ICO测评 - 项目分数分析
> 获取ICO测评的项目分数分析列表 /ico_assess/project_analyse?ico_assess_id=1

- **请求地址:** /ico_assess/project_analyse
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
    	"ico_article_id": 1,
        "name": "商业模式/市场前景", // 类型
        "desc": "描述", // 测评观点
        "score": "7", // 评分
    },
    ...
]
```

### 3.5 ICO测评标签
#### 3.5.1 获取ICO测评标签列表
> 获取项目标签列表 /ico_assess/tag?ico_assess_id=1
> 获取可用标签列表 /tag/main?get_key
> 如果是新增可用标签列表以外的标签,则需要向/tag/main中POST一条数据,取返回结果中的id

- **请求地址:** /ico_assess/tag
- **请求参数:** [get]
- **返回数据:**
```
[
    {
        "ico_assess_id": 1, // ICO测评id
        "tag_id": 1, // 标签ID
        "tag_info": {
        	"name": "业内大佬" // 标签名称
        }
    },
    ...
]
```

#### 3.5.2 修改ICO测评标签列表
- **请求地址:** /ico_assess/tag
- **请求参数:** [post] ico_assess_id,tag_ids
- **请求数据:**
```
{
	"ico_assess_id": "100", // ICO测评ID
    "tag_ids": [], // 标签列表id
}

* * *

## 4. 资讯
### 4.1 资讯管理
- **请求地址:** /article/main
- **请求参数:** [get, post, put, delete]
- **返回数据:**
```
[
    {
    	"title": "图文内容", // 资讯标题
        "img": null, // 资讯封面
        "desc": null, // 资讯简介
        "content": "", // 资讯内容(富文本)
        "category_id": 101, // 所属项目ID
        "sort": 0, // 排序
        "is_top": 0, // 置顶排序
        "is_hot": 0,
        "is_sole": 0, // 是否独家
        "is_scroll": 1, // 标记为首页滚动
        "status": 1, // 文章状态-1,审核不通过,0待审核,1审核通过;默认1
        "enable": 1, // 是否启用, 默认您启用
        "type": 2, // 资讯类型 1.快讯,2,图文,3视频,4下载
        "click_rate": 1, // 点击量
        "video": null, // 视频连接
        "url": null // 跳转连接
    },
    ...
]
```

### 4.2 资讯标签
#### 4.2.1 获取资讯标签列表
> 获取项目标签列表 /article/tag?article_id=1
> 获取可用标签列表 /tag/main?get_key
> 如果是新增可用标签列表以外的标签,则需要向/tag/main中POST一条数据,取返回结果中的id

- **请求地址:** /article/tag
- **请求参数:** [get]
- **返回数据:**
```
[
    {
        "article_id": 1, // 资讯id
        "tag_id": 1, // 标签ID
        "tag_info": {
        	"name": "业内大佬" // 标签名称
        }
    },
    ...
]
```

#### 4.2.2 修改资讯标签列表
- **请求地址:** /article/tag
- **请求参数:** [post] article_id,tag_ids
- **请求数据:**
```
{
	"article_id": "100", // 资讯ID
    "tag_ids": [], // 标签列表id
}
```

### 4.3 资讯栏目关联
> 将一篇资讯关联到多个栏目

#### 4.3.1 获取资讯关联的栏目列表
- **请求地址:** /article/cc_category?article_id=134
- **请求参数:** [get] article_id
- **返回数据:**
```
[
    {
    "id": 134,
    "title": "监管部门为比特币期货“亮绿灯”", // 资讯标题
    "cc_category": [
        {
            "id": 2,
            "article_id": 134, // 资讯id
            "category_id": 101, // 项目id
            "category": {
                "id": 101,
                "name": "bodhi",  // 项目名称
                "long_name": "bodhi" // 项目长名称
            }
        }
        ...
       ]
    }
    ...
]
```

#### 4.3.2 修改资讯关联的栏目列表
> 获取可用项目列表 /project/main?get_key

- **请求地址:** /article/cc_category
- **请求参数:** [post] article_id, category_ids
- **请求数据:**
```
{
	"article_id": "100", // ICO测评ID
    "category_ids": [], // 项目列表id
}
```

* * *

## 5. 其他
### 5.1 标签集合管理
- **请求地址:** /tag/main[?get_key]
- **请求参数:** [get, post, put, delete] get_key // 获取列比哦啊
- **返回数据:**
```
[
    {
    	"name": "图文内容", // 标签名
        "desc": null, // 备注
    },
    ...
]
```

### 5.2 ico管理
- **请求地址:** /ico/main[?get_key]
- **请求参数:** [get, post, put, delete] get_key // 获取列比哦啊
- **返回数据:**
```
[
    {
    	"name": "量子链", // ico名称
        "long_name": "量子链", // ico长名称
        "en_name": "qtum", // ico 英文名称
        "en_long_name": "qtum", // ico 英文长名称
        "unit": "QTUM", // ico单位
        "img": "", // ico 图片
        "web_site": "https://qtum.org", // ico官网
        "desc": null, // ico描述
        "sort": 0, // 排序
        "key": "qtum" // ico api key
    },
    ...
]
```

### 5.2 文件上传
#### 5.2.1 图片上传
- **请求地址:** /upload/img
- **请求参数:** [post]
- **返回数据:**
```
{
	"img": "", // 图片上传访问地址
}
```

#### 5.2.2 视频上传
- **请求地址:** /upload/video
- **请求参数:** [post]
- **返回数据:**
```
{
	"video": "", // 视频上传访问地址
}
```

### 5.3 百度编辑器 serverUrl
> <font color=red>暂不支持单图上传, iframe跨域问题</font>

- **请求地址:** /laravel-u-editor-server/server
