<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-16 016
 * Time: 15:57.
 */

namespace Xin6841414\WorkWechatRobot;

use GuzzleHttp\Client;
use Xin6841414\WorkWechatRobot\Messages\Message;

class WorkWechatService
{
    protected $config;

    /**
     * @var bool
     */
    protected $atAll = false;

    /**
     * @var Client
     */
    protected $client;

    public function __construct($config, $client = null)
    {
        $this->config = $config;

        if ($client != null) {
            $this->client = $client;

            return;
        }
        $this->client = $this->createClient($config);
    }

    public function createClient($config)
    {
        return new HttpClient($config);
    }

    public function send(Message $message)
    {
        if (!$this->config['enabled']) {
            return false;
        }

        return $this->client->send($message->combineMessage());
    }
}
