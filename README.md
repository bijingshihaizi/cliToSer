# cliToSer
composer package for swoft and hyperf rpc cli to ser



## 需要添加监听

```
\CliToSer\Listener\AddConsumerDefinitionListener::class,
```



## 调用方法：

**引入明明空间**

```
use CliToSer\ConnectToSer;
```

```
ConnectToSer::getInstance()->get('服务名称（name）', '方法（method）', 'params(参数)');
$res = ConnectToSer::getInstance()->client();
```