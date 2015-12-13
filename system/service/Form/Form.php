<?php namespace System\Service\Form;


class Form
{

    //构造函数
    public function __construct()
    {


    }

    public function select($title,$option,$selected){

    }

    /**
     * 文本框
     * @param $title 标题
     * @param $name name
     * @param string $value 值
     * @param array $attr 选项
     */
    public function input($title,$name,$value='',$attr=array(),$tip='')
    {
        $a = '';
        if(!isset($attr['type']))
        {
            $attr['type']='text';
        }
        foreach($attr as $n=>$v)
        {
            $a.=$n.'="'.$value.'" ';
        }
        $html=<<<str
        <div class="form-group">
                    <label class="col-lg-2 control-label">{$title}</label>
                    <div class="col-lg-10">
                        <input type="text" name="{$name}" value="{$value}" $a class="form-control">
                        <span class="help-block m-b-none">$tip</span>
                    </div>
        </div>
str;
        echo $html;
    }

    /**
     * 单选框
     * @param $title 标题
     * @param $name name
     * @param $options 值
     * @param string $selected
     * @param string $tip
     */
    public function radio($title,$name,$options,$selected='',$tip='')
    {
        $radio = '';
        foreach($options as $value=>$t)
        {
            $sed = $selected==$value?' checked="" ':'';
            $radio.=<<<str
            <label class="radio-inline">
                <input type="radio" name="{$name}" value="{$value}" {$sed}>$t
            </label>
str;
        }
        $html=<<<str
       <div class="form-group">
                    <label class="col-sm-2 control-label">$title</label>

                    <div class="col-sm-10">
                        {$radio}
                        <span class="help-block m-b-none">$tip</span>
                    </div>
                </div>
str;
        echo $html;
    }

    /**
     * 提交按钮
     */
    public function submit()
    {
        $html=<<<str
        <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
                <button type="submit" id="submitBtn" class="btn btn-primary">确定</button>
                <button type="button" onclick="location.href=window.history.back()" class="btn btn-default">返回</button>
            </div>
        </div>
str;
        echo $html;
    }


}