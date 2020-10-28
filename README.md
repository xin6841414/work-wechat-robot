<h1 align="center"> weather </h1>

<p align="center"> 企业微信群机器人通知的 laravel扩展</p>

[![Build Status](https://travis-ci.org/xin6841414/Weather.svg?branch=master)](https://travis-ci.org/xin6841414/Weather)


## 安装

```shell
$ composer require xin6841414/work-wechat-robot -vvv
```


## 配置
```
    php artisan vendor:publish --provider="Xin6841414\WorkWechatRobot\ServiceProvider"
    //或者
    php aritsan vendor:publish 选择相应数字序号
```
### 修改配置文件
- 在生成的`config/workwechatrobot.php`配置文件中结合env添加机器人配置，每个机器人项支持配置多个，所以env文件配置num数要和key数量对应
- 最简配置 `.env` 文件
```php
    WORK_WECHAT_GROUP_ROBOT_DEFAULT_NUM=1
    WORK_WECHAT_GROUP_ROBOT_DEFAULT_KEY_1=151de1af-f525****f5ea2186d4a0 //你的机器人key
```
- 每个key消息频率20次每分钟，消息量大的话尽量多配制几个，防止消息遗漏
- 支持自定义机器人项，现有 `default`和 `other` 两项，可根据自己业务自由加项，切换机器人使用`with($robot)`切换
## 使用

```php
use Xin6841414\WorkWechatRobot\Robot;

$robot = app('workwechatrobot');
//或者 
$robot = app(Robot::class);
```
### 切换机器人
```php
    $robot->with('other');
```
## 1、Text文本
```php
$response = $robot->text('测试消息');
//response
[
    'errcode' => 0，
    'errmsg' => 'ok',
]
```
### 1.1、text文本添加@某人
#### 1.1.1 mentioned模式
- 在 config/workwechatrobot的配置文件的响应的机器人子项（默认default）配置
  - `notify_user_ids`支持用户id，企业内唯一，可通过企业微信管理后台-通讯录查看，一般为姓名拼音大驼峰，如张三：ZhangSan,多个成员用`,`隔开
  - `notify_mobiles`支持手机号，多个用`,`隔开
- 调用`atUserId`方法或者`atMobiles`  与配置文件不冲突会合并
- 调用 `mentionedAtUserIds`和 `mentionedAtMobile`方法必须在`text`方法之后，`send`方法之前
```php
    $user = [
        'ZhangSan',
        'LiSi'
    ];   
    $atAll = false ; //使用@all通知@所有人
    $robot->text('今天天气真好')->mentionedAtUserId($user, $atAll)->send();
```
```php
    $mobile = [
    '13800138000',
    '13900139000'
    ]; 
    $atAll = false;
    $robot->text('今天天气真好')->mentionedAtMobile($mobile, $atAll)->send();  
```
#### 1.1.2 contentAt模式
- text文本支持<@userid> 语法，不支持手机号，
```php
    $user = [
        'ZhangSan',
        'LiSi'
    ]; 
    //或
    $user = 'ZhangSan';
    $robot->text('今天天气真好')->contentAt($user)->send();
    //或自行拼接
    $robot->text('今天天气真好<@ZhangSan>')->send();
```
## 2. markdown类型
### 2.1  获取markdown消息实例
```php
    $markdown = $robot->setMessage('markdown')->getMessage();
```
### 2.2 拼接消息内容
    $title = $markdown->setTitleContent('这是标题', 2)
    //参数2表示标题级别，支持1-6，默认1
    $bold = $markdown->setBoldContent('此处加粗');
    $warp = $markdown->addWarp(); //换行符 \n
    $url = $markdown->setUrlContent('百度', 'https://baidu.com'); //超链接
    $code = $markdown->setOneLineCodeContent('我是一行代码'); //仅支持单行代码，不支持跨行代码段
    $quote = $markdown->setQuoteContent('这是引用文字'); //转引用
    $color = $mardown->setTextColor('我是绿色的字', 1); //这是字体颜色，仅支持仅支持绿(1),灰(2),橙红(3)
### 2.3 发送消息
```php
    $result = $robot->markdown($title.$warp.$bold.$warp.$url)->send();
```
### 2.4 markdown支持contentAt语法，不支持手机号
```php
 $user = [
        'ZhangSan',
        'LiSi'
    ]; 
 //或
    $user = 'ZhangSan';
    $robot->markdown($title.$warp.$ulr)->contentAt($user)->send();
```

    
## 3 图片消息
### 3.1 处理图片转base64，
```php
     
    $base64_data = base64_encode(file_get_contents($file)); //base64头可带可不带；
```
### 3.2 文件md5
 ```php
    $md5 = md5_file($file); //md5值必须是转base64之前的图片计算得来
```
### 3.3 发送

```php
    $result = $robot->image($base64_data, $md5)->send();
```

## 4 news消息
### 4.1 添加一条图文消息
```php

    $title = "中秋节礼品领取";
    $description = '今年中秋节公司有豪礼相送';
    $url = 'www.qq.com';
    $picUrl = 'http://res.mail.qq.com/node/ww/wwopenmng/images/independent/doc/test_pic_msg1.png';
     $news = $robot->addNew($title,  $url, $description = '', $picUrl = '');
      
```
### 4.2 发送
```php

    $news->send();
    //支持多条图文
    $robot->addNew($title1, $url1)->addNew($title2, $url2)->send();
    //最大支持添加8条图文， 大于一条的图文展示效果类似公众号消息
    
```


## 5 文件类型
### 5.1 文件上传
    - 文件素材需要提前上传，上传得到media_id,media_id三天有效
    - media_id在同一企业内应用之间可以共享
```php
    $media_id = '1G6nrLmr5EC3MMb_-zK1dDdzmd0p7cNliYu9V5w7o8K0';
    $robot->file($media_id)->send();
```
### 5.2 文件上传接口
    请求方式：POST（HTTPS）
    请求地址：https://qyapi.weixin.qq.com/cgi-bin/webhook/upload_media?key=KEY&type=TYPE
    
    使用multipart/form-data POST上传文件， 文件标识名为”media”
    参数说明：
    
    参数	必须	说明
    key	是	调用接口凭证, 机器人webhookurl中的key参数
    type	是	固定传file
    POST的请求包中，form-data中媒体文件标识，应包含有 filename、filelength、content-type等信息
    
    filename标识文件展示的名称。比如，使用该media_id发消息时，展示的文件名由该字段控制
    
-  要求文件大小在5B~20M之间


You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/xin6841414/weather/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/xin6841414/weather/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT