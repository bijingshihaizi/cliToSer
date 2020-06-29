<?php
declare(strict_types=1);

namespace CliToSer;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
use Hyperf\Di\Container;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Hyperf\RpcClient\ProxyFactory;
use function MongoDB\BSON\fromJSON;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Di\Annotation\Inject;
use cliToSer\ConnectToSer;

class CliToSer
{
    /**
     * @var ContainerInterface
     */
    private $container;

    function __construct(ContainerInterface $contain)
    {
        $this->container = $contain;
    }

    public function process(){
        /** @var Container $container */
        $container = $this->container;
        $consumers = $container->get(ConfigInterface::class)->get('services.consumers', []);
        var_dump($consumers);
        foreach ($consumers as $consumer){
            if (empty($consumer['name'])) {
                continue;
            }
            if (isset($consumer['service']) || isset($consumer['id'])){
                $interface[$consumer['name']] = $consumer['service'] ?? $consumer['id'];
            }
            if (!empty($consumer['version'])){
                $version[$consumer['name']] = $consumer['version'];
            }
            if (!empty($consumer['rpcserver']) && $consumer['rpcserver'] == 'swoft'){
                foreach ($consumer['nodes'] as $v) {
                    $addr[$consumer['name']] = $v['host'] . ':' . $v['port'];
                }
            }
            if (!empty($consumer['rpcserver']) && $consumer['rpcserver'] == 'hyperf'){
                continue;
            }
        }
        ConnectToSer::client($addr, $interface, $version, $method, $params);
    }
}