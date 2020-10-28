<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-16 016
 * Time: 16:41.
 */

namespace Xin6841414\WorkWechatRobot\Messages;

abstract class Message
{
    public static $msgTypes = ['text', 'markdown', 'image', 'new'];
    protected $config;
    protected $msgType;
    protected $content;
    protected $canContentAt = false;
    protected $mentionedList = false;
    protected $mentionedMobileList = false;

    abstract public function getMessageType();

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function configNotify($config)
    {
        if ($this->mentionedList !== false) {
            if (strpos($config['notify_user_ids'], ',') === false) {
                $notifyUsers = [(string) $config['notify_user_ids']];
            } else {
                $notifyUsers = explode(',', $config['notify_user_ids']);
            }
            $this->mentionedList = $notifyUsers;
        }
        if ($this->mentionedMobileList !== false) {
            if (strpos($config['notify_mobiles'], ',') === false) {
                $notifyMobiles = [(string) $config['notify_mobiles']];
            } else {
                $notifyMobiles = explode(',', $config['notify_mobiles']);
            }
            $this->mentionedMobileList = $notifyMobiles;
        }
    }

    /**
     * @return mixed 获取消息主题内容
     */
    public function getBody()
    {
        $tmp['content'] = $this->content;

        return $tmp;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * 在content中使用<@userid>扩展语法来@群成员，仅在text/markdown消息类型支持
     *
     * @param $userId
     *
     * @return $this
     */
    public function contentAt($userId)
    {
        if ($this->canContentAt) {
            $result = '';
            if (is_array($userId)) {
                foreach ($userId as $user) {
                    $result .= '<@'.$user.'>';
                }
            } else {
                $result = '<@'.$userId.'>';
            }
            $this->content = $this->content.$result;
        }

        return $this;
    }

    public function sendAt($type, $list = [], $atAll = false)
    {
        if ($type == 1 && $this->mentionedList !== false) {
            if ($atAll) {
                $result = array_merge($list, ['@all']);
            } else {
                $result = $list;
            }
            $this->mentionedList = $result;
        } elseif ($type == 2 && $this->mentionedMobileList !== false) {
            if ($atAll) {
                $result = array_merge($list, ['@all']);
            } else {
                $result = $list;
            }
            $this->mentionedMobileList = $result;
        }

        return $this;
    }

    public function combineMessage()
    {
        $message['msgtype'] = $this->msgType;
        $message[$this->msgType] = $this->getBody();

        return $message;
    }
}
