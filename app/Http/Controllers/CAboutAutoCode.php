<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Rap2hpoutre\FastExcel\FastExcel as Rap2hpoutreFastExcel;

class CAboutAutoCode extends Controller
{
    public $maxlengh = 0;
    public function dianToTuofeng($res = "hotel.room.stock.and.price.get")
    {
        $res = str_replace('.',"_",$res);
        $res = ucfirst($this->camelize($res));
        $this->getOTAProductNoDict();
        return $res;
    }
    public function getExcelToData(){
        $res = ( new  Rap2hpoutreFastExcel() )->import('C:\phpstudy_pro\WWW\AiXBX\public\CAboutAutoCode.xlsx');
        // dd($res);
        $data = [];
        $data['Base_field'] = '';
        $data['detail'] = '';
        foreach ($res as $key => $value) {
            $data['Base_field'] .= $this->makeBaseField($value);
            $data['detail'] .= $this->makeDetail($value);
        }
        // dd($res,$data);
        return $data;
    }

    public function makeDetail($data)
    {
        $m = 'v.vouchers.';
        $file = $data['名称'];
        $file_T = ucfirst($this->camelize($file));
        $file_FX = $this->camelize($file);
        $datatype = $this->checkChangeDateType($data['数据类型']);
        $desc = $data['说明'];

        if ( isset($data['是否必须'])) {
            $is_need = $data['是否必须'];
            $desc = "是否必须:".$is_need."\n        /// ".$desc;
        }

        $base = <<<EOF

          <el-form-item label="$desc">
            <el-input v-model="$m$file_FX" disabled />
          </el-form-item>

EOF;
        // dd($data,$data['说明']);
        return $base;
    }
    public function makeBaseField($data)
    {
        $file = $data['名称'];
        $file_T = ucfirst($this->camelize($file));
        $datatype = $this->checkChangeDateType($data['数据类型']);
        $desc = $data['说明'];

        if ( isset($data['是否必须'])) {
            $is_need = $data['是否必须'];
            $desc = "是否必须:".$is_need."\n        /// ".$desc;
        }

        $base = <<<EOF

        /// <summary>
        /// $desc
        /// </summary>
        [JsonPropertyName("$file")]
        public $datatype $file_T { get; set; }

EOF;

        if($this->maxlengh>0){

    //         $base = <<<EOF

    //         /// <summary>
    //         /// $desc
    //         /// </summary>
    //         [JsonPropertyName("$file")]
    //         [MaxLength($this->maxlengh)]
    //         public $datatype $file_T { get; set; }

    // EOF;
    $base = <<<EOF

    /// <summary>
    /// $desc
    /// </summary>
    [JsonPropertyName("$file")]、
    [StringLength($this->maxlengh, MinimumLength = 1)]
    public $datatype $file_T { get; set; }

EOF;

        }
        // dd($data,$data['说明']);
        return $base;
    }
    public function checkChangeDateType($datatype)
    {
        switch ($datatype) {
            case 'date':
                $datatype = 'DateTime';
                break;
                case 'datetime':
                    $datatype = 'DateTime';
                    break;
                    case 'boolean':
                        $datatype = 'Boolean';
                        break;

            default:
                $datatype = $datatype;
                break;
        }

        $datatype = $this->checkIsArrayTypeAndReturnDatatype($datatype);
        $datatype = $this->checkIsArrayTypeAndReturnDatatype($datatype,'Array');
        $datatype = $this->dataTypefilter($datatype);
        return $datatype;
    }
    public function checkIsArrayTypeAndReturnDatatype($datatype,$needle ='array')
    {
        $needle = $needle.'(';//判断是否包含array(这个字符
        $tmparray = explode($needle,$datatype);
        if(count($tmparray)>1){
            $datatype = str_replace($needle,"",$datatype);
            $datatype = str_replace(")","",$datatype);
            if ($datatype=='int'||$datatype=='string') {
                $datatype = 'List<'.$datatype.'>';
            }else {
                $datatype = 'List<'.ucfirst($this->camelize($datatype)).'>';
            }
        }


        return $datatype;
    }
 /**
　　* 下划线转驼峰
　　* 思路:
　　* step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
　　* step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
　　*/
    public function camelize($uncamelized_words,$separator='_')
    {
        $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
        return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
    }
    public function index()
    {
        // dd(1);
        return view('CAboutAutoCode.setCodeHtml',['data'=>$this->getExcelToData()]);
    }
    //过滤对定义类型有长度限制的数据，并返回其中的长度
    public function dataTypefilter($datatype)
    {
        $dict = [
            'string',
            // 'int',
        ];
        foreach ($dict as $key => $value) {
            $needle = $value.'(';
            if (count(explode($needle,$datatype))>1) {
                $this->maxlengh = str_replace($needle,"",str_replace(")","",$datatype));
                $datatype = $value;
            }else {
                $this->maxlengh = 0;
            }
        }
        return $datatype;
    }

    public function getOTAProductNoDict()
    {
        $json = '{
            "count": 41,
            "page_index": 1,
            "page_size": 50,
            "items": [
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000023",
                    "goods_name": "深圳湾变动有效期价格日历",
                    "goods_shortname": "深圳湾变动有效期价格日历",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000001",
                    "goods_name": "深圳湾OTA自营门票",
                    "goods_shortname": "深圳湾OTA自营门票",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "Y000000002",
                    "goods_name": "深圳湾OTA自营年卡",
                    "goods_shortname": "深圳湾OTA自营年卡",
                    "goods_type_code": "YEAR_CARD",
                    "goods_type_name": "电子年卡"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "X000000003",
                    "goods_name": "深圳湾OTA自营消费券",
                    "goods_shortname": "深圳湾OTA自营消费券",
                    "goods_type_code": "COUPON",
                    "goods_type_name": "消费券"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "V000000004",
                    "goods_name": "深圳湾OTA自营场馆",
                    "goods_shortname": "深圳湾OTA自营场馆",
                    "goods_type_code": "VENUE",
                    "goods_type_name": "场馆"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000005",
                    "goods_name": "门票自营子商品",
                    "goods_shortname": "门票自营子商品",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "X000000006",
                    "goods_name": "消费券自营子商品",
                    "goods_shortname": "消费券自营子商品",
                    "goods_type_code": "COUPON",
                    "goods_type_name": "消费券"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "Y000000022",
                    "goods_name": "深圳湾多人年卡C",
                    "goods_shortname": "深圳湾多人年卡C",
                    "goods_type_code": "YEAR_CARD",
                    "goods_type_name": "电子年卡"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000040",
                    "goods_name": "库存校验",
                    "goods_shortname": "库存校验",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000043",
                    "goods_name": "深圳湾OTA自营门票02",
                    "goods_shortname": "深圳湾OTA自营门票02",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000045",
                    "goods_name": "指定门票详情校验",
                    "goods_shortname": "指定门票详情校验",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000042",
                    "goods_name": "深圳湾自营门票-指定",
                    "goods_shortname": "深圳湾自营门票-指定",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000060",
                    "goods_name": "测试门票变动有效期",
                    "goods_shortname": "测试门票变动有效期",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000059",
                    "goods_name": "测试门票固定有效期",
                    "goods_shortname": "测试门票固定有效期",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000061",
                    "goods_name": "哎呀门票【变动有效期】",
                    "goods_shortname": "哎呀门票【变动有效期】",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000062",
                    "goods_name": "哎呀啊门票【变动有效期】",
                    "goods_shortname": "哎呀门票【变动有效期】",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000063",
                    "goods_name": "凭证有效期测试",
                    "goods_shortname": "凭证有效期测试",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000031",
                    "goods_name": "每个证件号限购三张",
                    "goods_shortname": "每个证件号限购三张",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "T000000064",
                    "goods_name": "变动门票详情",
                    "goods_shortname": "变动门票详情",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "P000000080",
                    "goods_name": "测试套餐【指定】",
                    "goods_type_code": "PACKAGE_GOODS",
                    "goods_type_name": "套餐"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "P000000081",
                    "goods_name": "测试套餐【固定】",
                    "goods_type_code": "PACKAGE_GOODS",
                    "goods_type_name": "套餐"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "P000000082",
                    "goods_name": "测试套餐【变动】",
                    "goods_type_code": "PACKAGE_GOODS",
                    "goods_type_name": "套餐"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "X000000083",
                    "goods_name": "测试消费券【变动】",
                    "goods_shortname": "测试消费券【变动】",
                    "goods_type_code": "COUPON",
                    "goods_type_name": "消费券"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "X000000084",
                    "goods_name": "测试消费券【固定】",
                    "goods_shortname": "测试消费券【固定】",
                    "goods_type_code": "COUPON",
                    "goods_type_name": "消费券"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "X000000033",
                    "goods_name": "深圳湾次卡",
                    "goods_shortname": "深圳湾次卡",
                    "goods_type_code": "COUPON",
                    "goods_type_name": "消费券"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "Y000000085",
                    "goods_name": "测试年卡【变动】",
                    "goods_shortname": "测试年卡【变动】",
                    "goods_type_code": "YEAR_CARD",
                    "goods_type_name": "电子年卡"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "Y000000086",
                    "goods_name": "测试年卡【固定】",
                    "goods_shortname": "测试年卡【固定】",
                    "goods_type_code": "YEAR_CARD",
                    "goods_type_name": "电子年卡"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "V000000089",
                    "goods_name": "测试场馆【指定】",
                    "goods_shortname": "测试场馆【指定】",
                    "goods_type_code": "VENUE",
                    "goods_type_name": "场馆"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "V000000091",
                    "goods_name": "测试场馆【固定】",
                    "goods_shortname": "测试场馆【固定】",
                    "goods_type_code": "VENUE",
                    "goods_type_name": "场馆"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "V000000095",
                    "goods_name": "测试场馆【变动】",
                    "goods_shortname": "测试场馆【变动】",
                    "goods_type_code": "VENUE",
                    "goods_type_name": "场馆"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "Y000000034",
                    "goods_name": "测试年卡01",
                    "goods_shortname": "测试年卡01",
                    "goods_type_code": "YEAR_CARD",
                    "goods_type_name": "电子年卡"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "X000000101",
                    "goods_name": "华山消费券【变动有效期】",
                    "goods_shortname": "华山消费券【变动】",
                    "goods_type_code": "COUPON",
                    "goods_type_name": "消费券"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0001",
                    "supply_scenic_name": "深圳湾业务运营商",
                    "goods_no": "Y000000102",
                    "goods_name": "华山年卡【变动有效期】",
                    "goods_shortname": "华山年卡【变动】",
                    "goods_type_code": "YEAR_CARD",
                    "goods_type_name": "电子年卡"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0002",
                    "supply_scenic_name": "南昌华侨城业务运营商",
                    "goods_no": "P000000017",
                    "goods_name": "南昌代销套餐",
                    "goods_type_code": "PACKAGE_GOODS",
                    "goods_type_name": "套餐"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0002",
                    "supply_scenic_name": "南昌华侨城业务运营商",
                    "goods_no": "T000000009",
                    "goods_name": "南昌代销门票",
                    "goods_shortname": "南昌代销门票",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0002",
                    "supply_scenic_name": "南昌华侨城业务运营商",
                    "goods_no": "P000000070",
                    "goods_name": "南昌代销套餐01",
                    "goods_type_code": "PACKAGE_GOODS",
                    "goods_type_name": "套餐"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0002",
                    "supply_scenic_name": "南昌华侨城业务运营商",
                    "goods_no": "P000000079",
                    "goods_name": "南昌代销套餐03",
                    "goods_type_code": "PACKAGE_GOODS",
                    "goods_type_name": "套餐"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0002",
                    "supply_scenic_name": "南昌华侨城业务运营商",
                    "goods_no": "P000000071",
                    "goods_name": "南昌代销套餐02",
                    "goods_type_code": "PACKAGE_GOODS",
                    "goods_type_name": "套餐"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0002",
                    "supply_scenic_name": "南昌华侨城业务运营商",
                    "goods_no": "X000000068",
                    "goods_name": "南昌代销券01",
                    "goods_shortname": "南昌代销券01",
                    "goods_type_code": "COUPON",
                    "goods_type_name": "消费券"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0002",
                    "supply_scenic_name": "南昌华侨城业务运营商",
                    "goods_no": "X000000069",
                    "goods_name": "南昌代销券02",
                    "goods_shortname": "南昌代销券02",
                    "goods_type_code": "COUPON",
                    "goods_type_name": "消费券"
                },
                {
                    "sale_scenic_no": "S0001",
                    "sale_scenic_name": "深圳湾业务运营商",
                    "supply_scenic_no": "S0002",
                    "supply_scenic_name": "南昌华侨城业务运营商",
                    "goods_no": "T000000065",
                    "goods_name": "南昌代销子商品01",
                    "goods_shortname": "南昌代销子商品01",
                    "goods_type_code": "SCENIC_TICKET",
                    "goods_type_name": "景区门票"
                }
            ]
        }';

        $arr = json_decode($json,true)['items'];
        $dict = [];
        foreach ($arr as $key => $value) {
            $dict[] = $value['goods_no'];
        }
        dd(json_encode($dict));
    }

    public function jsonToC()
    {
        $json = '';
    }
}

