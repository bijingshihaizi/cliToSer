<?php
declare(strict_types=1);

namespace Clitoser\Clitoser\Listener;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
use Hyperf\Di\Container;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BootApplication;
use Clitoser\Clitoser\ConnectToSer;

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