<?php
declare(strict_types=1);

namespace Clitoser\Clitoser;


class ConnectToSer
{
    private $addr;
    private $interface;
    private $name;
    private $method;
    private $params;
    private static $instance;

    public function getArgs($addr, $interface){
        $this->addr = $addr;
        $this->interface = $interface;
    }

    public function get($name, $method, $params){
        $this->name = $name;
        $this->method = $method;
        $this->params = $params;
    }

    public static function getInstance()
    {
        if (isset($instance)) {
            return $instance;
        }
        if (!isset(self::$instance) || (self::$instance === null)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function client(){
        $fp = stream_socket_client($this->addr[$this->name], $errno, $errstr);
        if (!$fp) {
            throw new \Exception("stream_socket_client fail errno={$errno} errstr={$errstr}");
        }
        //传入数据
        $data = [
            'interface' => $this->interface[$this->name],
            'version'   => '1.0.0',
            'method'    => $this->method,
            'params'    => $this->params,
            'logid'     => uniqid(),
            'spanid'    => 0,
        ];
        $data = json_encode($data, JSON_UNESCAPED_UNICODE)."\r\n";
        fwrite($fp, $data);
        $result = fread($fp, 1024);
        fclose($fp);
        return $result;
    }
}