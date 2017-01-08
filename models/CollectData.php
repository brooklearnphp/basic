<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "collect_data".
 *
 * @property string $id
 * @property string $title
 * @property string $video_url
 * @property integer $is_download
 * @property string $video_path
 * @property string $create_time
 * @property string $download_begin_time
 * @property string $download_end_time
 */
class CollectData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collect_data';
    }

    const IS_DOWNLOAD_NOT = 0;
    const IS_DOWNLOAD_YES = 1;

    /**
     * 获取下载状态
     * @param null $key
     * @return array
     */
    public static function getISDownload($key = null){
        $data = [
            self::IS_DOWNLOAD_NOT => '未下载',
            self::IS_DOWNLOAD_YES => '已下载'
        ];
        return $key === null ? $data : $data[$key];
    }

    /**
     * 把需要获取的url保存在collectData表中
     * @param Array $preg 获取页面的链接的正则表达式
     * @param string $page 页面内容
     */
    public static function saveData($preg,$page) {
        foreach ($preg as $val){
            $matches = array();
            preg_match_all($val,$page,$matches);
            if (!empty($matches)){
                foreach ($matches[1] as $value){
                    $model = self::findOne(['video_url'=>CollectUrl::HOST_URL.$value]);
                    if (!$model){
                        $model = new self();
                        $model->video_url = CollectUrl::HOST_URL.$value;
                        $model->is_download = self::IS_DOWNLOAD_NOT;
                        $model->create_time = time();
                        $model->save();
                    }
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['video_url', 'is_download', 'create_time'], 'required'],
            [['is_download', 'create_time', 'download_begin_time', 'download_end_time'], 'integer'],
            [['title'], 'string', 'max' => 128],
            [['video_url', 'video_path'], 'string', 'max' => 625],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', '主键id'),
            'title' => Yii::t('app', '标题'),
            'video_url' => Yii::t('app', '视频链接'),
            'is_download' => Yii::t('app', '是否已经下载'),
            'video_path' => Yii::t('app', '视频路径'),
            'create_time' => Yii::t('app', '创建时间'),
            'download_begin_time' => Yii::t('app', '下载开始时间'),
            'download_end_time' => Yii::t('app', '下载结束时间'),
        ];
    }
}
