# Api Proxy

A simple api proxy for Laravel.



## 用法

```php
\ApiProxy
  //返回值类型：object / array / json
  ::setReturnAs('object')
  //get / post / put / ...
  ->post($url, $params);
```

