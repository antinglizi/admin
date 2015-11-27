<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    "accepted"         => ":attribute 必须接受。",
    "active_url"       => ":attribute 不是一个有效的URL。",
    "after"            => ":attribute 必须是一个在 :date 之后的日期。",
    "alpha"            => ":attribute 只能由字母组成。",
    "alpha_dash"       => ":attribute 只能由字母、数字、中划线、下划线组成。",
    "alpha_num"        => ":attribute 只能由字母和数字组成。",
    "array"            => ":attribute 必须是一个数组。",
    "before"           => ":attribute 必须是一个在 :date 之前的日期。",
    "between"          => array(
        "numeric" => ":attribute 必须介于 :min - :max 之间。",
        "file"    => ":attribute 必须介于 :min - :max 千字节之间。",
        "string"  => ":attribute 必须介于 :min - :max 个字符之间。",
        "array"   => ":attribute 必须介于 :min - :max 个单元之间。"
    ),
    "confirmed"        => ":attribute 确认不匹配。",
    "date"             => ":attribute 不是一个有效的日期。",
    "date_format"      => ":attribute 不匹配日期格式 :format。",
    "different"        => ":attribute 和 :other 必须不同。",
    "digits"           => ":attribute 必须是 :digits 位的数字。",
    "digits_between"   => ":attribute 必须是介于 :min 和 :max 位的数字。",
    "email"            => ":attribute 电邮格式非法。",
    "exists"           => "已选的属性 :attribute 非法。",
    "image"            => ":attribute 必须是一张图片。",
    "in"               => "已选的属性 :attribute 非法。",
    "integer"          => ":attribute 必须是一个整数。",
    "ip"               => ":attribute 必须是一个有效的IP地址。",
    "max"              => array(
        "numeric" => ":attribute 必须小于 :max 。",
        "file"    => ":attribute 必须小于 :max 千字节。",
        "string"  => ":attribute 必须小于 :max 个字符。",
        "array"   => ":attribute 必须小于 :max 个单元。"
    ),
    "mimes"            => ":attribute 必须是一个 :values 类型的文件。",
    "min"              => array(
        "numeric" => ":attribute 必须大于 :min 。",
        "file"    => ":attribute 必须大于 :min 千字节。",
        "string"  => ":attribute 必须大于 :min 个字符。",
        "array"   => ":attribute 必须大于 :min 个单元。"
    ),
    "not_in"           => "已选的属性 :attribute 非法。",
    "numeric"          => ":attribute 必须是一个数字。",
    "regex"            => ":attribute 字段必填。",
    "required"         => ":attribute 属性需要填写字段。",
    "required_if"      => ":attribute 属性当 :other 为 :value时为必填项。",
    "required_with"    => ":attribute 属性当 :values 存在时为必填项。",
    "required_without" => ":attribute 属性当 :values 不存在时为必填项。",
    "same"             => ":attribute 和 :other 必须匹配。",
    "size"             => array(
        "numeric" => ":attribute 大小必须是 :size 。",
        "file"    => ":attribute 大小必须是 :size 千字节。",
        "string"  => ":attribute 必须是 :size 个字符。",
        "array"   => ":attribute 必须包含 :size 个单元。"
    ),
    "unique"           => ":attribute 已经有人使用。",
    "url"              => ":attribute 格式非法。",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => array(
        'shop_name' => array(
            'max' => '店铺名字必须小于 :max 个字符',
        ),
        'shop_phone' => array(
            'required' => '需要填写手机号',
            'digits' => '手机号必须为 :digits位的数字',
        ),
        'shop_addr' => array(
            'max' => '店铺地址必须小于 :max 个字符',
        ),
        'shop_longitude' => array(
            'required' => '请单击按钮获取店铺地图坐标',
        ),
        'shop_latitude' => array(
            'required' => '请单击按钮获取店铺地图坐标',
        ),
        'shop_keywords' => array(
            'max' => '店铺关键字必须小于 :max 个字符',
        ),
        'shop_brief' => array(
            'max' => '店铺描述必须小于 :max 个字符',
        ),
        'shop_delivery_time' => array(
            'integer' => '配送时间必须为一个合法的整数',
            'digits_between' => '配送时间必须为一个合法的整数',
            'min' => '配送时间不能为负数',
        ),
        'shop_distance' => array(
            'digits_between' => '配送距离必须为一个合法的数字',
            'min' => '配送距离不能为负数',
        ),
        'shop_delivery_fee' => array(
            'digits_between' => '配送费用必须为一个合法的数字',
            'numeric' => '配送费用必须为一个合法的数字',
            'min' => '配送费用不能为负数',
        ),
        'shop_delivery_price' => array(
            'digits_between' => '起送价格必须为一个合法的数字',
            'numeric' => '起送价格必须为一个合法的数字',
            'min' => '起送价格不能为负数',
        ),
        'shop_id' => array(
            'required' => '请选择店铺',
        ),
        'goods_name' => array(
            'max' => '菜品名称必须小于 :max 个字符',
        ),
        'goods_price' => array(
            'digits_between' => '菜品价格必须为一个合法的数字',
            'min' => '菜品价格不能为负数',
        ),
        'goods_stock' => array(
            'digits_between' => '菜品库存必须为一个合法的数字',
            'min' => '菜品库存不能为负数',
        ),
        'goods_brief' => array(
            'max' => '菜品描述必须小于 :max 个字符',
        ),
        'user_name' => array(
            'min' => '用户名长度不能少于 :min个字符',
            'max' => '用户名长度不能超过 :max个字符',
            'unique' => '该用户名已经被占用',
        ),
        'user_phone' => array(
            'unique' => '该手机号已经有人使用',
            'digits' => '手机号必须为 :digits位的数字',
        ),
        'user_pwd' => array(
            'confirmed' => '两次输入的密码不一致',
            'max' => '密码长度不能超过 :max个字符',
            'alpha_dash' => '密码只能由字母、数字、中划线、下划线组成',
        ),
        'user_auth_code' => array(
            'numeric' => '验证码必须为一个数字',
        ),
        'user_nickname' => array(
            'max' => '用户昵称必须小于 :max 个字符',
        ),
        'user_email' => array(
            'email' => '您输入的邮箱格式不正确',
            'unique' => '该邮箱已被使用',
        ),
        'agree_protocol' => array(
            'accepted' => '请您先阅读并同意文蚁用户注册协议',
        ),
    ),

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => array(),

);
