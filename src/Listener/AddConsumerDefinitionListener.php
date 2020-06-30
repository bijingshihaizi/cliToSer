<?php
declare(strict_types=1);

namespace CliToSer\Listener;

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
use CliToSer\ConnectToSer;
use CliToSer\GetArgs;

class AddConsumerDefinitionListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            BootApplication::class,
        ];
    }

    /**
     * Automatic create proxy service definitions from services.consumers.
     *
     * @param BootApplication $event
     */
    public function process(object $event){
        /** @var Container $container */
        $container = $this->container;
        $consumers = $container->get(ConfigInterface::class)->get('services.consumers', []);
        foreach ($consumers as $consumer){
            if (empty($consumer['name'])) {
                continue;
            }
            if (isset($consumer['service']) || isset($consumer['id'])){
                $interface[$consumer['name']] = $consumer['service'] ?? $consumer['id'];
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
        return ConnectToSer::getInstance()->getArgs($addr, $interface);
    }
}