<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-16 016
 * Time: 15:43.
 */

namespace Xin6841414\WorkWechatRobot;

use Xin6841414\WorkWechatRobot\Exceptions\InvalidArgumentException;
use Xin6841414\WorkWechatRobot\Messages\File;
use Xin6841414\WorkWechatRobot\Messages\Image;
use Xin6841414\WorkWechatRobot\Messages\Markdown;
use Xin6841414\WorkWechatRobot\Messages\Message;
use Xin6841414\WorkWechatRobot\Messages\News;
use Xin6841414\WorkWechatRobot\Messages\Text;

class Robot
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var string
     */
    protected $robot = 'default';

    /**
     * @var WorkWechatService
     */
    protected $workWechatService;

    /**
     * @var Message
     */
    protected $message;

    protected $messageAllow = ['text', 'markdown', 'image', 'news', 'file'];

    protected $client;

    public function __construct($config, $client = null)
    {
        $this->config = $config;
        $this->client = $client;
        $this->with();
    }

    public function with($robot = 'default')
    {
        if (!isset($this->config[$robot])) {
            throw new InvalidArgumentException('未知的robot配置名称【'.$robot.'】');
        }
        if (!$this->config[$robot]['key']) {
            throw new InvalidArgumentException('【'.$robot.'】机器人的key不能为空');
        }
        $this->robot = $robot;
        $this->workWechatService = new WorkWechatService($this->config[$robot], $this->client);

        return $this;
    }

    /**
     * 切换消息类型.
     *
     * @param $message
     *
     * @throws InvalidArgumentException
     */
    public function setMessage($message)
    {
        if (!in_array($message, $this->messageAllow)) {
            throw new InvalidArgumentException('不允许的消息类型【'.$message.'】');
        }
        $class = __NAMESPACE__.'\\Messages\\'.ucfirst($message);
        if (class_exists($class)) {
            $this->message = new $class($this->config[$this->robot]);

            return $this;
        }

        throw new InvalidArgumentException('消息类【'.$class.'】不存在');
    }

    /**
     * 获取消息类型.
     *
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    public function getWorkWechatService()
    {
        return $this->workWechatService;
    }

    /**
     * 使用<@userid>语法@某人.
     *
     * @param string|array $user
     *
     * @return $this
     */
    public function contentAt($user)
    {
        $this->message->contentAt($user);

        return $this;
    }

    public function send()
    {
        return $this->workWechatService->send($this->message);
    }

    public function text($content = '')
    {
        if (!$this->message instanceof Text) {
            $this->message = new Text($this->config[$this->robot]);
        }
        $this->message->setContent($content);
        $this->message->combineMessage();

        return $this;
    }

    public function markdown($content = '')
    {
        if (!$this->message instanceof Markdown) {
            $this->message = new Markdown($this->config[$this->robot]);
        }
        $this->message->setContent($content);
        $this->message->combineMessage();

        return $this;
    }

    public function image($base64, $md5)
    {
        if (!$this->message instanceof Image) {
            $this->message = new Image($this->config[$this->robot]);
        }
        $this->message->setBase64($base64);
        $this->message->setMd5($md5);
        $this->message->combineMessage();

        return $this;
    }

    /**
     * 仅添加图文消息不发送
     *
     * @param $title string 标题
     * @param $url string 图片链接地址， http协议头可带可不带
     * @param string $description 图文简单描述
     * @param string $picUrl      图片背景url地址
     *
     * @throws InvalidArgumentException
     *
     * @return $this
     */
    public function addNew($title, $url, $description = '', $picUrl = '')
    {
        if (!$this->message instanceof News) {
            $this->message = new News($this->config[$this->robot]);
        }
        $this->message->addArticle($title, $url, $description, $picUrl);

        return $this;
    }

    public function file($mediaId)
    {
        if (!$this->message instanceof File) {
            $this->message = new File($this->config[$this->robot]);
        }
        $this->message->setMedia($mediaId);

        return $this;
    }

    /**
     * 通过手机号@某人.
     *
     * @param string|array $mobile
     * @param bool         $atAll
     *
     * @return $this
     */
    public function mentionedAtMobile($mobile = [], $atAll = false)
    {
        if (!is_array($mobile)) {
            $mobile = (array) $mobile;
        }
        $config = $this->config[$this->robot]['notify_mobiles'];
        if ($config) {
            $mobilesConfig = strpos(',', $config) === false ? [$config] : explode(',', $config);
            $mobile = array_merge($mobilesConfig, $mobile);
        }
        $this->message
            ->sendAt(2, $mobile, $atAll);

        return $this;
    }

    /**
     * 通过userid@某人.
     *
     * @param string|array $userId
     * @param bool         $atAll
     *
     * @return $this
     */
    public function mentionedAtUserId($userId = [], $atAll = false)
    {
        if (!is_array($userId)) {
            $userId = (array) $userId;
        }
        $config = $this->config[$this->robot]['notify_user_ids'];
        if ($config) {
            $userIdsConfig = strpos(',', $config) === false ? [$config] : explode(',', $config);
            $userId = array_merge($userIdsConfig, $userId);
        }
        $this->message->sendAt(1, $userId, $atAll);

        return $this;
    }
}
