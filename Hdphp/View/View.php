<?php namespace Hdphp\View;

use Hdphp\View\Compile;
use Exception;

//视图处理
class View
{

    //模板变量集合
    protected static $vars = array();

    //模版文件
    public $tpl;

    //编译文件
    public $compile;

    /**
     * 解析模板
     * @param string $tpl 模板
     * @param int $expire 过期时间
     * @param bool|true $show 显示或返回
     * @return bool|string
     * @throws Exception
     */
    public function make($tpl = '', $expire = 0, $show = true)
    {
        //缓存有效
        if ($expire > 0 && $content = Cache::dir('Storage/view/cache')->get($_SERVER['REQUEST_URI'])) {
            if ($show) {
                die($content);
            } else {
                return $content;
            }
        }

        //模板文件
        if (!$this->tpl = $this->getTemplateFile($tpl)) {
            return false;
        }

        //编译文件
        $this->compile = 'Storage/view/compile/' . md5($this->tpl) . '.php';

        //编译文件
        $this->compileFile();

        //释放变量到全局
        if (!empty(self::$vars)) {
            extract(self::$vars);
        }

        //获取解析结果
        ob_start();
        require($this->compile);
        $content = ob_get_clean();

        if ($expire > 0) {
            //缓存
            if (!Cache::dir('Storage/view/cache')->set($_SERVER['REQUEST_URI'], $content, $expire)) {
                throw new Exception("创建缓存失效");
            }
        }

        if ($show) {
            echo $content;
            exit;
        } else {
            return $content;
        }
    }

    //获取显示内容
    public function fetch($tpl = '')
    {
        return $this->make($tpl, 0, false);
    }

    /**
     * 获取模板文件
     * @param $file 模板文件
     * @return bool|string
     * @throws Exception
     */
    private function getTemplateFile($file)
    {
        if (!is_file($file)) {
            if (defined('MODULE')) {
                //模块视图文件夹
                $file = MODULE_PATH . '/View/' . CONTROLLER . '/' . ($file ?: ACTION) . C('view.prefix');
            } else {
                //路由中使用回调函数执行View::make()时，因为没有MODULE
                $file = C('view.path') . '/' . $file . C('view.prefix');
            }
        }

        //判断文件
        if (is_file($file)) {
            return $file;
        } else {
            if (DEBUG) {
                throw new Exception("模板不存在:$file");
            } else {
                return false;
            }
        }
    }

    /**
     * 验证缓存文件
     * @return mixed
     */
    public function isCache()
    {
        return Cache::dir('Storage/view/cache')->get($_SERVER['REQUEST_URI']);
    }

    /**
     * 编译文件
     */
    private function compileFile()
    {
        $status = DEBUG || !file_exists($this->compile) ||
            !is_file($this->compile) || (filemtime($this->tpl) > filemtime($this->compile));
        if ($status) {
            //创建编译目录
            $dir = dirname($this->compile);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            //执行文件编译
            $compile = new Compile($this);
            $content = $compile->run();
            file_put_contents($this->compile, $content);
        }
    }

    /**
     * 分配变量
     * @param $name 变量名
     * @param string $value 值
     * @return $this
     */
    public function with($name, $value = '')
    {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                self::$vars[$k] = $v;
            }
        } else {
            self::$vars[$name] = $value;
        }

        return $this;
    }

    /**
     * 错误页面
     *
     * @param string $message 提示内容
     * @param string $url 跳转URL
     * @param int $time 跳转时间
     * @param string $tpl 模板文件
     */
    public function error($message = '出错了', $url = '', $time = 2)
    {
        if (IS_AJAX) {
            Response::ajax(array('status' => 0, 'message' => $message));
        } else {
            $url = U($url);
            $url = $url ? "window.location.href='" . $url . "'" : "window.location.href='" . __HISTORY__ . "'";

            $this->with(array('message' => $message, 'url' => $url, 'time' => $time));
            $this->make(Config::get('view.error'));
        }

        exit;
    }

    /**
     * 成功页面
     *
     * @param string $message 提示内容
     * @param string $url 跳转URL
     * @param int $time 跳转时间
     * @param string $tpl 模板文件
     */
    public function success($message = '操作成功', $url = '', $time = 2)
    {
        if (IS_AJAX) {
            $this->ajax(array('status' => 1, 'message' => $message));
        } else {
            $url = U($url);
            $url = $url ? "window.location.href='" . $url . "'" : "window.location.href='" . __HISTORY__ . "'";

            $this->with(array('message' => $message, 'url' => $url, 'time' => $time));
            $this->make(Config::get('view.success'));
        }
        exit;
    }

    /**
     * Ajax输出
     *
     * @param        $data 数据
     * @param string $type 数据类型 text html xml json
     */
    public function ajax($data, $type = "JSON")
    {
        $type = strtoupper($type);
        switch ($type) {
            case "HTML" :
            case "TEXT" :
                $_data = $data;
                break;
            case "XML" :
                //XML处理
                header('Content-Type: application/xml');
                $_data = Xml::create($data);
                break;
            default :
                //JSON处理
                header('Content-Type: application/json');
                $_data = json_encode($data);
        }
        echo $_data;
        exit;
    }
}