<?php
declare(strict_types=1);

namespace CliToSer;


class ConnectToSer
{
    public static function client($addr, $interface, $version, $method, $params){
        $fp = stream_socket_client($addr, $errno, $errstr);
        if (!$fp) {
            throw new \Exception("stream_socket_client fail errno={$errno} errstr={$errstr}");
        }
        //传入数据
        $data = [
            'interface' => $interface,
            'version'   => $version,
            'method'    => $method,
            'params'    => $params,
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