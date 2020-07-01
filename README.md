# cliToSer
composer package for swoft and hyperf rpc cli to ser



## 需要添加监听

```
\Clitoser\Clitoser\Listener\AddConsumerDefinitionListener::class,
```



## 调用方法：

**引入命名空间**

```
use Clitoser\Clitoser\ConnectToSer;
```

```
ConnectToSer::getInstance()->get('服务名称（name）', '方法（method）', 'params(参数)');
$res = ConnectToSer::getInstance()->client();
```

**services配置**
```
'consumers' => [
	[
	    // 对应消费者类的 $serviceName
	    'name' => 'demo',
	    'rpcserver' => 'swoft',
	    'service' => \App\Rpc\Lib\Auth\AuthManagerInterface::class,
	    // 这个消费者要从哪个服务中心获取节点信息，如不配置则不会从服务中心获取节点信息
	    'registry' => [
	        'protocol' => 'consul',
	        'address' => 'http://127.0.0.1:8500',
	    ],
	    // 如果没有指定上面的 registry 配置，即为直接对指定的节点进行消费，通过下面的 nodes 参数来配置服务提供者的节点信息
	    'nodes' => [
	        ['host' => '172.26.130.178', 'port' => 8099],
	    ],
	],	
]
```