<?php namespace hdphp\view;

class Compile
{
    //视图对象
    private $view;

    //模板编译内容
    private $content;

    //构造函数
    function __construct(&$view)
    {
        $this->view = $view;

    }

    /**
     * 运行编译
     * @return string
     */
    public function run()
    {
        //模板内容
        $this->content = file_get_contents($this->view->tpl);

        //解析标签
        $this->tags();

        //解析全局变量与常量
        $this->globalParse();

        //创建令牌代码
        $this->createToken();

        //模板继承替换
        $this->blade();
        //保存编译文件
        return $this->content;
    }

    /**
     * 解析blade标签
     */
    private function blade()
    {
        //检测是否包含extend
        if (!preg_match('/<!--block_/', $this->content)) {
            return;
        }
        //将blade模板的blockshow替换到显示模板的{{parent}}区域
        preg_match_all('/<!--blockshow_(.*?)-->(.*?)<!--endblockshow_\1-->/isU', $this->content, $blockshow);
        foreach ((array)$blockshow[1] as $k => $v) {
            $this->content = str_replace('<!--parent_'.$v.'-->',$blockshow[2][$k],$this->content);
            //删除 显示模板的parent 区域
            $this->content = str_replace($blockshow[0][$k],'',$this->content);
        }

        //找到所有block块替换blade模板中的blade区域
        preg_match_all('/<!--block_(.*?)-->(.*?)<!--endblock_\1-->/isU', $this->content, $blocks);
        foreach ((array)$blocks[1] as $k => $v) {
            $this->content = str_replace('<!--blade_'.$v.'-->',$blocks[2][$k],$this->content);
            //删除 block 区域
            $this->content = str_replace($blocks[0][$k],'',$this->content);
        }
    }

    /**
     * 解析全局变量与常量
     */
    private function globalParse()
    {
        //处理{{}}
        $this->content = preg_replace('/(?<!@)\{\{(.*?)\}\}/i', '<?php echo \1?>', $this->content);

        //处理@{{}}
        $this->content = preg_replace('/@(\{\{.*?\}\})/i', '\1', $this->content);
    }

    /**
     * 创建令牌
     */
    private function createToken()
    {
        if (C('app.token_on')) {
            //获取令牌
            if (!isset($_SESSION[C('app.token_name')])) {
                $_SESSION[C('app.token_name')] = md5(time() . mt_rand(1, 999));
            }
            //表单添加令牌
            if (preg_match_all('/<form.*?>(.*?)<\/form>/is', $this->content, $matches, PREG_SET_ORDER)) {

                foreach ($matches as $id => $m) {
                    $php = "<input type='hidden' name='" . C('app.token_name') . "' value='" . $_SESSION[C('app.token_name')] . "'";
                    $this->content = str_replace($m[1], $m[1] . $php, $this->content);
                }
            }
        }
    }

    /**
     * 解析标签
     */
    private function tags()
    {
        //标签库
        $tags = Config::get('view.tags');
        $tags[] = 'Hdphp\View\HdphpTag';

        //解析标签
        foreach ($tags as $class) {
            $obj = new $class();
            $this->content = $obj->parse($this->content, $this->view);
        }
    }
}