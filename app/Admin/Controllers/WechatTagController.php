<?php

namespace App\Admin\Controllers;

use App\Model\WechatTag;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use GuzzleHttp;
use App\Http\Controllers\Weixin\WechatController;

class WechatTagController extends Controller
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
        $grid = new Grid(new WechatTag);

        $grid->id('Id');
        $grid->tag_id('Tag id');
        $grid->tag_name('Tag name');
        $grid->count('Count');

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
        $show = new Show(WechatTag::findOrFail($id));

        $show->id('Id');
        $show->tag_id('Tag id');
        $show->tag_name('Tag name');
        $show->count('Count');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WechatTag);
        $form->number('tag_id', 'Tag id');
        $form->text('tag_name', 'Tag name');
        $form->number('count', 'Count');
        return $form;
    }

    /**
     * access_token
     */
    public function access_token(){
        $wechat=new WechatController();
        $access_token=$wechat->getWXAccessToken();
        return $access_token;
    }

    /**
     * 用户标签
     */
    public function getWxtag(Content $content){
        $access_token=$this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$access_token;
        $data = json_decode(file_get_contents($url),true);
        $data=$data['tags'];
        return $content
            ->header('微信')
            ->description('标签列表')
            ->body(view('weixin.tags')->with('data',$data));
    }

    /**
     * 创建标签视图
     */
    public function getAddtag(Content $content){
        return $content
            ->header('微信')
            ->description('创建标签')
            ->body(view('weixin.addtags'));
    }

    /**
     * 修改标签视图
     */
    public function getUpdatetags(Content $content){
        $id=$_POST['id'];
        return $content
            ->header('微信')
            ->description('修改标签')
            ->body(view('weixin.updatetags')->with('id',$id));
    }

    /**
     * 创建标签接口
     */
    public function getAddtags(){
        $name=$_POST['name'];
//        $name="测试";

        //获取标签名
        $tag = WechatTag::where(['tag_name' => $name])->first();
        if($tag){
            //标签已存在
        }else{
            //存入数据库&接口创建
            $access_token=$this->access_token();  //获取access_token
            $url = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$access_token;
            $client=new GuzzleHttp\Client(['base_uri'=>$url]);
            $data=[
                "tag" => [
                    "name" => $name
                ]
            ];
            //JSON_UNESCAPED_UNICODE转中文
            $data=$client->request('POST',$url,[
                'body'=>json_encode($data,JSON_UNESCAPED_UNICODE)
            ]);
            $event = json_decode($data->getBody(),true);
            $wechat_tag=[
                'tag_id'=>$event['tag']['id'],
                'tag_name'=>$event['tag']['name'],
                'count'=>0
            ];
            $id = WechatTag::insertGetId($wechat_tag);
        }
        return 1;
    }

    /**
     * 删除标签
     */
    public function getDeletetags(){
        $id=$_POST['id'];
//        $id=100;
        $access_token=$this->access_token();  //获取access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/delete?access_token='.$access_token;
        $client=new GuzzleHttp\Client(['base_uri'=>$url]);
        $data=[
            "tag" => [
                "id" => $id
            ]
        ];
        //JSON_UNESCAPED_UNICODE转中文
        $data=$client->request('POST',$url,[
            'body'=>json_encode($data,JSON_UNESCAPED_UNICODE)
        ]);
        $event = json_decode($data->getBody(),true);
        if($event['errmsg']=='ok'){
            return "ok";
        }else{
            return "no";
        }
    }

    /**
     * 修改标签
     */
    public function getUpdateAddtags()
    {
        $name = $_POST['name'];
        $id = $_POST['id'];
//        $id='102';
//        $name="admin";
        $access_token = $this->access_token();  //获取access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/update?access_token=' . $access_token;
        $client = new GuzzleHttp\Client(['base_uri' => $url]);
        $data = [
            "tag" => [
                "id" => $id,
                "name" => $name
            ]
        ];
        //JSON_UNESCAPED_UNICODE转中文
        $data = $client->request('POST', $url, [
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE)
        ]);
        $event = json_decode($data->getBody(), true);
        if($event['errmsg']=='ok'){
            return "ok";
        }else{
            return "no";
        }
    }
    /**
     * 标签群发页面
     */
    public function tagAll(Content $content){
        $access_token=$this->access_token();
        $url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$access_token;
        $data = json_decode(file_get_contents($url),true);
        $data=$data['tags'];
        return $content
            ->header('微信')
            ->description('标签列表')
            ->body(view('weixin.tagsAll')->with('data',$data));
    }

    /**
     * 标签群发
     */
    public function tagsAllAdd(){
        $openid=$_POST['openid'];
        $media_id=$_POST['media_id'];
        $type=$_POST['type'];
//        print_r($openid);
//        print_r($media_id);die;
//        print_r($type);
        $access_token = $this->access_token();
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$access_token";
        if($type=="text"){
            $data=[
                "filter"=>[
                    "is_to_all"=>true,
                    'tag_id'=>$openid
                ],
                "text"=>[
                    "content"=>"$media_id"
                ],
                "msgtype"=>"text"
            ];
        }else if($type=="mpnews"){
            $data=[
                "filter"=>[
                    "is_to_all"=>true,
                    "tag_id"=>$openid
                ],
                "$type"=>[
                    "media_id"=>"$media_id"
                ],
                "msgtype"=>"$type",
                "send_ignore_reprint"=>0
            ];
        }else{
            $data=[
                "filter"=>[
                    "is_to_all"=>true,
                    "tag_id"=>$openid
                ],
                "mpvideo"=>[
                    "media_id"=>$media_id
                ],
                "msgtype"=>"$type",
            ];
        }
        $json=json_encode($data,JSON_UNESCAPED_UNICODE);
        $obj=new \url();
        $info=$obj->sendPost($url,$json);
        if($info['errcode']==0){
            echo  'ok';
        };
    }
}
