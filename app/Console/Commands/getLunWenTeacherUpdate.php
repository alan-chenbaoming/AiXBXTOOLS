<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class getLunWenTeacherUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:getLunWenTeacherUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取老师批改论文记录';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //dd(Cache::set('donghua-lunwen-teacher-check-num', 2));
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://dhujj.ct-edu.com.cn/entity/workspaceStudent/studentPaperOperate_queryPaperSecondData.action',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => 'paperId=ff8080817e2548d0017e71a49d7a3346',
          CURLOPT_HTTPHEADER => array(
            'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="101", "Microsoft Edge";v="101"',
            'Accept: application/json, text/plain, */*',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
            'X-Requested-With: XMLHttpRequest',
            'sec-ch-ua-mobile: ?0',
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.41 Safari/537.36 Edg/101.0.1210.32',
            'sec-ch-ua-platform: "Windows"',
            'Cookie: Hm_lvt_eaa57ca47dacb4ad4f5a257001a3457c=1618931546,1619623848,1619623850,1620830407; SESSION=969e890a-048c-4167-878b-4783e75b3d81; 4c3bbd0e85ba44fc829d9ccef6e2d3b8=WyIzMjM1MjkzMDQxIl0; ab96eea0901840949ab44ac351d5fbb9=WyIyMTMwOTI0NjUiXQ'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        //dd($response,json_decode($response,true));
        $res = json_decode($response,true);


        $num = Cache::get('donghua-lunwen-teacher-check-num', 2);
        $teacherMessageList_count = count($res['teacherMessageList']);
        if ($teacherMessageList_count>$num) {
            Cache::set('donghua-lunwen-teacher-check-num', $teacherMessageList_count);
            $this->sendDD('老师批改了你的论文记得查阅，这是老师第'.count($res['teacherMessageList']).'次批阅，老师说了以下内容：'.$res['teacherMessageList'][$num]['message']);
        }
        //$this->sendDD('检查脚本是否自动执行');
        dd($res);

    }

    private function sendDD($content)
    {
        //
        $data = [
            "msgtype"=> "text",
            "text" => [
                "content"=> $content,
            ],
            "at" => [
                "atMobiles" => [17601260865],
                "isAtAll" => false
            ]
        ];
        $header = [
            'Content-Type: application/json'
        ];
        $url = 'https://oapi.dingtalk.com/robot/send?access_token=c60cb2ae867ad95fcfe8d869b3bda9f397312bb793e022a0e00d36be35e59366';
        $rt = $this->postCurl($data, $url, 30, $header);
    }

    private function postCurl($data, $url, $second = 30,$header=[])
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch,CURLOPT_URL, $url);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        if(is_array($header) && !empty($header)){
            curl_setopt ($ch, CURLOPT_HTTPHEADER, $header);
        }
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return $error;
        }
    }
}
