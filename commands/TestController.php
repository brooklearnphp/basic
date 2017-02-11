<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\CollectDataCopy;
use yii\console\Controller;

use Yii;
/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class TestController extends Controller
{
    public function actionWife(){
        set_time_limit(0);
        ini_set('memory_limit','1000M');
        $url = 'http://www.50zw.la/book_43609/';
        $content = CollectDataCopy::getContentByCurl($url);
        $preg = '/<a href=[\'|\"](\d{1,}.html)[\'|\"]>(.{1,40})<\/a>/';
        $matches = [];
        preg_match_all($preg,$content,$matches);
        if(isset($matches[1]) && isset($matches[2])){
            $pregContent = '/<divid=\"htmlContent\"class=\"contentboxclear\">(.*)<\/div><center>/';
            foreach($matches[1] as $key => $value){
                file_put_contents('shui.txt', $matches[2][$key] . "\r\n", FILE_APPEND);
                $contentUrl = $url . $value;
                $content1 = CollectDataCopy::getContentByCurl($contentUrl);
                //ȥ�����л��лس��ո�
                $content1 = str_replace("\r","",$content1);
                $content1 = str_replace("\n","",$content1);
                $content1 = str_replace(" ","",$content1);
                //ƥ������
                $matchesContent = [];
                preg_match($pregContent,$content1,$matchesContent);
                if(isset($matchesContent[0]) && isset($matchesContent[1])){
                    $fileContent = str_replace("<br><br>&nbsp;&nbsp;&nbsp;&nbsp;","\r\n",$matchesContent[1]);
                    $fileContent = str_replace("<br/><br/>&nbsp;&nbsp;&nbsp;&nbsp;","\r\n",$fileContent);
                    $fileContent = str_replace("һ���ס������֡���?�ġ���WwW.50zw.La�������¿죬�޵�������Ѷ���","����С������ôô�ա��Ϲ�����",$fileContent);
                    file_put_contents('shui.txt',$fileContent . "\r\n", FILE_APPEND);
                    file_put_contents('shui.txt',"���Ű���Ŷ��������\r\n"  , FILE_APPEND);
                    echo $matches[2][$key] . "�ɼ����� " . date('Y-m-d H:i:s') . "\r\n";
                }
                unset($matches[1][$key]);
                unset($matches[2][$key]);
                unset($matchesContent);
                unset($content1);
                unset($fileContent);
            }
        }
    }
}