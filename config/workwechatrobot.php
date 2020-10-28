<?php
/**
 * Created by PhpStorm.
 * User: xin6841414
 * Date: 10-19 019
 * Time: 13:58
 */

return [
    //默认发送的机器人
  'default' => [
      // 是否要开启机器人，关闭则不再发送消息
      'enabled' => env('WORK_WECHAT_GROUP_ROBOT_DEFAULT_ENABLED', true),
      //机器人的key值, 多个key需定义num，随机取一个使用
      'key' => env(
          'WORK_WECHAT_GROUP_ROBOT_DEFAULT_KEY_'.mt_rand(
              1,
              env('WORK_WECHAT_GROUP_ROBOT_DEFAULT_NUM',1)
          ),
          ''
      ),
      //userid的列表，企业内唯一，在管理后台-通讯录查看，一般为姓名拼音例：张三的useid为ZhangSan，提醒群中的指定成员（@某个成员），@all表示提醒所有人， 如果开发者获取不到userid，可以使用notify_mobiles
      //多个用“，”隔开，比如ZhangSan,LiSi
      'notify_user_ids' => env('WORK_WECHAT_GROUP_ROBOT_DEFAULT_USER_ID', ''),
      //手机号列表，提醒手机号对应的群成员(@某个成员)，@all表示提醒所有人
      //多个手机号用“，”隔开 ，比如 13800138000,13900139000
      'notify_mobiles' => env('WORK_WECHAT_GROUP_ROBOT_DEFAULT_MOBILE', ''),
  ],
  'other' => [
      'enabled' => env('WORK_WECHAT_GROUP_ROBOT_OTHER_ENABLED', true),
      'key' => env(
          'WORK_WECHAT_GROUP_ROBOT_OTHER_KEY_'.mt_rand(
              1,
              env('WORK_WECHAT_GROUP_ROBOT_OTHER_NUM',1)
          ),
          ''
      ),
      'notify_user_ids' => env('WORK_WECHAT_GROUP_ROBOT_OTHER_USER_ID', ''),
      'notify_mobiles' => env('WORK_WECHAT_GROUP_ROBOT_OTHER_MOBILE', ''),
  ],
];