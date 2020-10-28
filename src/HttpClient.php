<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-19 019
 * Time: 13:52.
 */

namespace Xin6841414\WorkWechatRobot;

use GuzzleHttp\Client;
use Xin6841414\WorkWechatRobot\Exceptions\HttpException;
use Xin6841414\WorkWechatRobot\Exceptions\InvalidGatewayException;

class HttpClient implements SendClient
{
    /**
     * @var SendClient
     */
    protected $client;
    /**
     * @var string
     */
    protected $hookUrl = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send';

    protected $config;

    public function __construct($config)
    {
        $this->config = $config;
        $this->client = $this->createClient();
    }

    protected function createClient()
    {
        return new Client();
    }

    public function getRobotUrl()
    {
        return $this->hookUrl.'?key='.$this->config['key'];
    }

    public function send($params): array
    {
//        dd($params, json_encode($params));
        try {
            $request = $this->client->post($this->getRobotUrl(), [
                'body'    => json_encode($params),
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);
            $result = $request->getBody()->getContents();
            $resultArr = json_decode($result, true);
            if ($resultArr['errcode'] !== 0) {
                throw new InvalidGatewayException($resultArr['errmsg']);
            }

            return $resultArr;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
