<?php

namespace App\Admin\Controllers;

use App\Model\WeixinUser;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use GuzzleHttp;
use App\Http\Controllers\Weixin\WechatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class WechatUserController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WeixinUser);

        $grid->id('Id');
        $grid->uid('Uid');
        $grid->FromUserName('FromUserName');
        $grid->CreateTime('CreateTime')->display(function ($time) {
            return date('Y-m-d H:i:s', $time);
        });
        $grid->nickname('Nickname');
        $grid->sex('Sex');
        $grid->headimgurl('Headimgurl')->display(function ($image) {
            return '<img src="' . $image . '">';
        });
        $grid->subscribe_time('Subscribe time')->display(function ($time) {
            return date('Y-m-d H:i:s', $time);
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(WeixinUser::findOrFail($id));

        $show->id('Id');
        $show->uid('Uid');
        $show->FromUserName('FromUserName');
        $show->CreateTime('CreateTime');
        $show->nickname('Nickname');
        $show->sex('Sex');
        $show->headimgurl('Headimgurl');
        $show->subscribe_time('Subscribe time');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WeixinUser);

        $form->number('uid', 'Uid');
        $form->text('FromUserName', 'FromUserName');
        $form->number('CreateTime', 'CreateTime');
        $form->text('nickname', 'Nickname');
        $form->switch('sex', 'Sex');
        $form->text('headimgurl', 'Headimgurl');
        $form->number('subscribe_time', 'Subscribe time');

        return $form;
    }

    /**
     * access_token
     */
    public function access_token()
    {
        $wechat = new WechatController();
        $access_token = $wechat->getWXAccessToken();
        return $access_token;
    }

    /**
     * 微信用户openid 接口
     */
    public function WxUser(Content $content)
    {
        $access_token = $this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $access_token . '&next_openid=';
        $data = json_decode(file_get_contents($url), true);
        $data = $data['data']['openid'];
        return $content
            ->header('微信')
            ->description('标签列表')
            ->body(view('weixin.blackuser')->with('data', $data));

    }

    /**
     * 微信黑名单用户列表
     */
    public function WxBlackUserList(Content $content)
    {
        $access_token = $this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=' . $access_token;
        $client = new GuzzleHttp\Client(['base_uri' => $url]);
        $data = [
            "begin_openid" => ""
        ];
        //JSON_UNESCAPED_UNICODE转中文
        $data = $client->request('POST', $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
        $event = json_decode($data->getBody(), true);
        if (!empty($event['data'])) {
            $event = $event['data']['openid'];
        } else {
            $event = [];
        }
        return $content
            ->header('微信')
            ->description('标签列表')
            ->body(view('weixin.blackuserlist')->with('data', $event));
    }

    /**
     * 拉黑
     */
    public function WxBlackUserAdd()
    {
        $openid = $_POST['openid'];
        $access_token = $this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token=' . $access_token;
        $client = new GuzzleHttp\Client(['base_uri' => $url]);
        $data = array(
            "openid_list" => $openid,
        );
        //JSON_UNESCAPED_UNICODE转中文
        $data = $client->request('POST', $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
        $event = json_decode($data->getBody(), true);
        if ($event['errmsg'] == 'ok') {
            return "ok";
        } else {
            return "no";
        }
    }

    /**
     * 取消拉黑
     */
    public function WxBlackUserOff()
    {
        $openid = $_POST['openid'];
        $access_token = $this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist?access_token=' . $access_token;
        $client = new GuzzleHttp\Client(['base_uri' => $url]);
        $data = array(
            "openid_list" => $openid,
        );
        //JSON_UNESCAPED_UNICODE转中文
        $data = $client->request('POST', $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
        $event = json_decode($data->getBody(), true);
        if ($event['errmsg'] == 'ok') {
            return "ok";
        } else {
            return "no";
        }
    }

    /**
     * 自定义菜单视图
     */
    public function menuAdd(Content $content)
    {
        return $content
            ->header('微信')
            ->description('创建菜单')
            ->body(view('weixin.menuAdd'));
    }

    /**
     * 自定义菜单创建
     */
    public function menuInfo()
    {
        $obj = $_POST['obj'];
        $arr = [];
        foreach ($obj as $k => $v) {
            if ($v['select'] == "" || substr($v['select'], -1) == "1") {
                $arr[] = [
                    //一级菜单
                    'name1' => $v['name'],
                    'select1' => rtrim($v['select'], "1"),
                    'name2' => "",
                    'select2' => ""
                ];
            } else {
                $arr[] = [
                    //二级菜单
                    'name1' => "",
                    'select1' => "",
                    'name2' => $v['name'],
                    'select2' => $v['select']
                ];
            }
        }
        $data = [
            "button" => [
//                [
//                    "name" => "测试功能",
//                    "sub_button" => [
//                        [
//                            "type"  => "pic_sysphoto",      // view类型 跳转指定 URL
//                            "name"  => "系统拍照发图",
//                            "key"   => "rselfmenu_1_0",
//                            "sub_button"=> [ ]
//                        ],
//                    ]
//                ],
            ]
        ];
        print_r($arr);
        foreach ($arr as $kk => $vv) {
//            print_r($vv);
//            if(($vv['select1']==""&&$vv['select2']=="")||$vv['select1']=!""&&$vv['select2']==""){
//                //一级菜单
//                $data['button'][]=[
//                    'type'=>$vv['select1'],
//                    'name'=>$vv['name1']
//                ];
//            }else if($vv['select1']==""&&$vv['select2']!=""){
//                //二级菜单
//                $data['button'][]=[
//                    'name'=>$vv['name1'],
//                    'sub_button'=>[
//                        'type'=>$vv['select2'],
//                        'name'=>$vv['name2']
//                    ]
//                ];
//            }
            if ($vv['name1'] != "" && $vv['select1'] != "" && $vv['name2'] == "" && $vv['select2'] == "") {
                //一级菜单
                $data['button'][] = [
                    'type' => $vv['select1'],
                    'name' => $vv['name1']
                ];
            } else if ($vv['name1'] == "" && $vv['select1'] == "" && $vv['name2'] != "" && $vv['select2'] != "") {
                //二级菜单
                $data['button'][] = [
                    'name' => $arr[0]['name1'],
                    'sub_button' => [
                        'type' => $vv['select2'],
                        'name' => $vv['name2']
                    ]
                ];
            }
        }
        print_r($data);
        exit;
        // 1 获取access_token 拼接请求接口
        $access_token = $this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token;

        //2 请求微信接口
        $client = new GuzzleHttp\Client(['base_uri' => $url]);


        $r = $client->request('POST', $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);

        // 3 解析微信接口返回信息

        $response_arr = json_decode($r->getBody(), true);
        echo '<pre>';
        print_r($response_arr);
        echo '</pre>';
        exit;
        if ($response_arr['errcode'] == 0) {
            echo "菜单创建成功";
        } else {
            echo "菜单创建失败，请重试";
            echo '</br>';
            echo $response_arr['errmsg'];
        }
    }

    /**
     * 文件上传
     */
    public function fileInfo(Content $content)
    {
        return $content
            ->header('微信')
            ->description('临时文件上传')
            ->body(view('weixin.fileinfo'));
    }

    /**
     * 新增临时素材
     */
    public function filePath($fileinfo)
    {
//        dump($fileinfo);exit;
        // 1 获取access_token 拼接请求接口
        $access_token = $this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $access_token . '&type=image';
//        $filePath="./picture/baby.jpg";
        $fileszile=filesize($fileinfo);
        $objmedia=new \CURLFile($fileinfo);
        $arr=[
            'media_id'=>$objmedia,
            'form-data'=>[
                'filename'=>'baby.jpg',
                'filelength'=>$fileszile,
                'content-type'=>"images/jpeg",
            ]
        ];
        $obj=new \url();
        $info=$obj->sendPost($url,$arr);
        return $info;
    }

    //获取素材列表
    public function fodder()
    {
        $access_token = $this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=' . $access_token;
        $data = [
            'type' => 'image',
            'offset' => 0,
            'count' => 2
        ];
        $client = new GuzzleHttp\Client(['base_uri' => $url]);
        $r = $client->request('POST', $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
        $respone_arr = json_decode($r->getBody(), true);
        echo '<pre>';
        print_r($respone_arr);
        echo '</pre>';

    }


    /**
     * 文件上传
     */
    public function upload(Request $request)
    {
        $fileCharater=$request->file('file');
        if($fileCharater->isValid()){
            $ext=$fileCharater->getClientOriginalExtension();
//            var_dump($ext);
            $path=$fileCharater->getRealpath();
//            var_dump($path);
            $filename=md5(time()).".{$ext}";
//            var_dump($filename);exit;
            storage::disk('public')->put($filename,file_get_contents($path));
            $filepath="./upload/$filename";
//            var_dump($filepath);
            $info=$this->filePath($filepath);
//            var_dump($info);
            $arrImg=json_decode($info,true);
//            var_dump($arrImg);
            $media_id=$arrImg['media_id'];
//            var_dump($media_id);
            $time=time()+19438*3;
//            var_dump($time);

            $data=array(
                'media_id'=>$media_id,
                'time'=>$time,
                'filepath'=>$filepath,
            );
//            var_dump($data);exit;
            $this->redis_add($data);
        }
    }

    /**
     * 存入redis
     */
    public function redis_add($data){
        $obj=new \redis();
        $obj->connect('127.0.0.1',6379);
        $id=$obj->incr('id');
        $key="id_$id";

        $obj->hset($key,'id',$id);
        $obj->hset($key,'media_id',$data['media_id']);
        $obj->hset($key,'filepath',$data['filepath']);
        $obj->hset($key,'time',$data['time']);

        $list="per_id";
        $obj->rpush($list,$key);
         print_r($obj->lrange($list,0,-1));
    }

    /**
     * redis 缓存
     */
    public function materialList(Content $content)
    {
        $page=empty($_GET['page'])?1:$_GET['page'];
        $pagenum=1;
        $start=($page-1)*$pagenum;
        $end=$start+$pagenum-1;

        $obj=new \redis();
        $obj->connect('127.0.0.1',6379);
        $list="per_id";
        $acc=$obj->llen($list);
        $z=ceil($acc/$pagenum);
        $arr=$obj->lrange($list,$start,$end);
        $toppage=$page-1<1?1:$page-1;
        $nexpage=$page+1>$z?$z:$page+1;
        $res=array();
        foreach($arr as $v){
            $data=$obj->hgetall($v);
            array_push($res,$data);
        }
        $arrpage=array(
            'first'=>1,
            "nexpage"=>$nexpage,
            "toppage"=>$toppage,
            "weiye"=>$z
        );
//         print_R($res);exit;
//         print_R($data);exit;

        return $content
            ->header('微信')
            ->description('临时素材缓存列表')
            ->body(view('weixin.material')->with('res',$res)->with('data',$arrpage));
    }

    /**
     * 群发页面
     */
    public function MassAll(Content $content){
        $access_token = $this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $access_token . '&next_openid=';
        $data = json_decode(file_get_contents($url), true);
        $data = $data['data']['openid'];
        return $content
            ->header('微信')
            ->description('群发列表')
            ->body(view('weixin.massall')->with('data',$data));
    }

    /**
     * 执行群发
     */
    public function MassAllAdd(){
        $openid=$_POST['openid'];
        $media_id=$_POST['media_id'];
        $type=$_POST['type'];
//        print_r($openid);
//        print_r($media_id);die;
//        print_r($type);
        $access_token = $this->access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=$access_token";
        if($type=="text"){
            $data=[
                "touser"=>$openid,
                "msgtype"=>"text",
                "text"=>[
                    "content"=>"$media_id"
                ]
            ];
        }else if($type=="mpnews"){
            $data=[
                "touser"=>$openid,
                "$type"=>[
                    "media_id"=>"$media_id"
                ],
                "msgtype"=>"$type",
                "send_ignore_reprint"=>0
            ];
        }else if($type=="mpvideo"){
            $data=[
                "touser"=>$openid,
                "$type"=>[
                    "media_id"=>"$media_id",
                    "title"=>"TITLE",
                    "description"=>"DESCRIPTION"
                ],
                "msgtype"=>"$type",
                "send_ignore_reprint"=>0
            ];
        }else{
            $data=[
                "touser"=>$openid,
                "$type"=>[
                    "media_id"=>"$media_id"
                ],
                "msgtype"=>"$type"
            ];
        }
        $json=json_encode($data,JSON_UNESCAPED_UNICODE);
        $obj=new \url();
        $info=$obj->sendPost($url,$json);
        print_r($info);
    }
}