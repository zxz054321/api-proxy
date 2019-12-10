# Api Proxy

A simple api proxy for Laravel.



## 用法

```php
use AbelHalo\ApiProxy\ApiProxy;

$proxy = new ApiProxy;

// 返回值类型：object / array / string / json
$proxy->setReturnAs('object');

// 开启请求日志
$proxy->logger->enable();

// 请求方法：get / post / put / ...
$proxy->post($url, $params);
```

