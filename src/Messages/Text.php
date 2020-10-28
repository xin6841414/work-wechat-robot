<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-16 016
 * Time: 16:29.
 */

namespace Xin6841414\WorkWechatRobot\Messages;

use Xin6841414\WorkWechatRobot\Exceptions\InvalidArgumentException;

class Text extends Message
{
    protected $msgType = 'text';

    protected $message;

    protected $content;

    protected $canContentAt = true;

    protected $atAll = false;

    protected $mentionedMobileList = [];

    protected $mentionedList = [];

    public function __construct($config)
    {
        parent::__construct($config);
        $this->configNotify($config);
    }

    public function getMessageType()
    {
        return $this->msgType;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        if (strlen($content) > 5120) {
            throw new InvalidArgumentException('消息内容最长2048个字符, 最长支持约1700个汉字');
        }
        $this->content = $content;
    }

    /**
     * userid的列表，提醒群中的指定成员(@某个成员)，@all表示提醒所有人，如果开发者获取不到userid，可以使用mentioned_mobile_list.
     *
     * @param string|array $person
     *
     * @return Text
     */
    public function setMentionedList($person)
    {
        $list = is_array($person) ?? [$person];
        if ($this->atAll) {
            $list = array_merge($list, ['@all']);
        }
        $this->mentionedList = $list;

        return $this;
    }

    /**
     * 手机号列表，提醒手机号对应的群成员(@某个成员)，@all表示提醒所有人.
     *
     * @param $mobile
     *
     * @return Text
     */
    public function setMentionedMobileList($mobile)
    {
        $list = is_array($mobile) ?? [$mobile];
        if ($this->atAll) {
            $list = array_merge($list, ['@all']);
        }
        $this->mentionedMobileList = $list;

        return $this;
    }

    /**
     * @return array 获取消息主题内容
     */
    public function getBody()
    {
        $tmp['content'] = $this->content;
        $tmp['mentioned_list'] = $this->mentionedList;
        $tmp['mentioned_mobile_list'] = $this->mentionedMobileList;

        return array_filter($tmp);
    }
}
