<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-21 021
 * Time: 16:43.
 */

namespace Xin6841414\WorkWechatRobot\Messages;

class File extends Message
{
    protected $msgType = 'file';
    protected $mediaId;

    public function getMessageType()
    {
        return 'file';
    }

    public function getBody()
    {
        $content['media_id'] = $this->mediaId;

        return $content;
    }

    public function setMedia($mediaId)
    {
        $this->mediaId = $mediaId;

        return $this;
    }
}
