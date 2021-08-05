# 1.整体思路
### 1.创建礼品码

![Image text](https://raw.githubusercontent.com/89trillion-chengchen/job3/master/images/%E6%B5%81%E7%A8%8B%E5%9B%BE1.jpg)
### 2.查看礼品码信息  
![Image text](https://raw.githubusercontent.com/89trillion-chengchen/job3/master/images/%E6%B5%81%E7%A8%8B%E5%9B%BE2.jpg)
### 3.使用礼品码
![Image text](https://raw.githubusercontent.com/89trillion-chengchen/job3/master/images/%E6%B5%81%E7%A8%8B%E5%9B%BE3.jpg)

# 2.接口设计

### （1）创建礼包码 
####请求方法  
```php 
HTTP POST
```
#### 接口地址   
```php 
http://89tr.chengchen.com/GiftCode/creatgiftCode
```
#### 响应
```php 
{
    "status": 200,
    "msg": "ok",
    "data": "code_OPCMgDUP"
}
```
### （2）查看礼包码信息
####请求方法
```php 
HTTP POST
```
#### 接口地址
```php 
http://89tr.chengchen.com/GiftCode/getCodeInfo
```
#### 响应
```php 
{
    "status": 200,
    "msg": "ok",
    "codeInfo": {
        "creatTime": "2021-08-02 17:47:46",
        "admin": "root",
        "description": "奖励",
        "count": "2",
        "begin_time": "2021-07-28 19:48:03",
        "end_time": "2021-08-28 19:48:03.",
        "receivedCount": "1",
        "type": "1",
        "role": "1",
        "content_gold": "67",
        "content_Diamond": "645"
    },
    "useList": {
        "asdasd": "2021-08-02 17:48:40",
        "asdasd888": "2021-08-02 18:18:37"
    }
}
```
### （3）客户端调用 - 验证礼品码
####请求方法
```php 
HTTP POST
```
#### 接口地址
```php 
http://89tr.chengchen.com/GiftCode/useCode
```
#### 响应
```php 
{
    "status": 200,
    "msg": "ok",
    "data": {
        "coin": "67",
        "diamond": "645",
        "props": "十连抽券",
        "hero": "狐狸",
        "soldier": "弓箭手"
    }
}
```

# 3.目录结构

```php 
.
├── README.md
├── classes
│   ├── ctrl
│   │   ├── CtrlBase.php
│   │   ├── GiftCodeCtrl.php
│   ├── service
│   │   ├── AnswerService.php
│   │   ├── BaseService.php
│   │   ├── CacheService.php
│   │   ├── GiftCodeService.php
│   ├── unitTest
│   │   ├── GiftCodeServiceTest.php
├── report
└── webroot
    └── index.php

```
## 3.1 逻辑分层
  ```php

    classes/ctrl是请求控制层

    classes/service是业务控制层

    classes/unitTest是测试层

    webroot/index.php是入口
  ```
## 4.运行和测试
### 4.1 如何部署运行服务
  ```php
使用docker运行容器，容器包含 nginx、php、php-fpm

配置文件根目录为项目根目录webroot，运行端口为8000
  ```
### 4.2 如何测试接口
  ```php
  终端进入 report 目录
  运行 locust 
  ```