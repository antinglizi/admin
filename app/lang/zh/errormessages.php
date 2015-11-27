<?php

/*
 * The list of error code
 * The scope of error code is from -10000 to -18999
 */

return array(

	/*
    |--------------------------------------------------------------------------
    | Error Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the error messages
    |
    | 从-10000到-18999为错误信息提示码 0代表成功
    | 错误码列表，全局唯一，每一个错误码对应一个错误位置
    | 
    | 错误码分类：
    | 
    | 客户端请求错误范围：-10000~-14999 容量5000 
    | 数据库操作错误：-15000~-15999 容量1000
    | 系统错误：-16000~-16999 容量1000
    |
    */
   
    //客户端请求错误
    -10000  =>  "手机号或密码错误或您不是商家或账号已停用，请检查后重新登陆",
    -10001  =>  "请获取验证码之后再进行注册",
    -10002  =>  "输入的验证码与获取的验证码不一致，请正确输入验证码",
    -10003  =>  "输入的验证码已经过期，请重新获取验证码",
    -10004  =>  "手机号码不能为空",
    -10005  =>  "获取验证码失败",
    -10006  =>  "获取验证码失败",
    -10007  =>  "获取验证码失败",
    -10008  =>  "该账号未注册过，请注册新用户",
    -10009  =>  "请获取验证码之后才可以找重置密码",
    -10010  =>  "输入的验证码与获取的验证码不一致，请正确输入验证码",
    -10011  =>  "输入的验证码已经过期，请重新获取验证码",
    -10012  =>  "重置密码时，密码验证错误",
    -10013  =>  "未找到该账户信息，请注册新用户",
    -10014  =>  "新密码不能与旧密码相同",
    -10015  =>  "请获取验证码之后再进行重置密码",
    -10016  =>  "输入的验证码与获取的验证码不一致，请正确输入验证码",
    -10017  =>  "输入的验证码已经过期，请重新获取验证码",
    -10018  =>  "未找到该店铺",
    -10019  =>  "未找到该店铺",
    -10020  =>  "未找到该店铺",
    -10021  =>  "未找到该店铺",
    -10022  =>  "未找到该店铺",
    -10023  =>  "未找到该店铺",
    -10024  =>  "未找到该店铺对应的",
    -10025  =>  "新密码不能与旧密码相同",
    -10026  =>  "旧的密码输入不正确",
    -10027  =>  "未找到账号信息类别",
    -10028  =>  "修改密码时验证密码格式错误",
    -10029  =>  "该手机号与登陆账号的手机号不匹配",
    -10030  =>  "请获取验证码之后再进修改手机号",
    -10031  =>  "输入的验证码与获取的验证码不一致，请正确输入验证码",
    -10032  =>  "输入的验证码已经过期，请重新获取验证码",
    -10033  =>  "手机号的格式不正确",
    -10034  =>  "新手机号不能和旧手机号相同",
    -10035  =>  "该手机号已被使用",
    -10036  =>  "输入的验证码与获取的验证码不一致，请正确输入验证码",
    -10037  =>  "输入的验证码已经过期，请重新获取验证码",
    -10038  =>  "请获取验证码之后再进修改手机号",
    -10039  =>  "未找到该店铺详细信息",
    -10040  =>  "未找到店铺类型",
    -10041  =>  "未找到地理位置信息",
    -10042  =>  "未找到店铺信息类别",
    -10043  =>  "未找到该店铺",
    -10044  =>  "店铺营业状态修改失败",
    -10045  =>  "未找到该店铺",
    -10046  =>  "未找到该菜品",
    -10047  =>  "未找到该店铺",
    -10048  =>  "未找到该菜品",
    -10049  =>  "未找到该菜品",
    -10050  =>  "未找到该菜品",
    -10051  =>  "没有此订单信息或者订单状态已改变，请重新刷新数据",
    -10052  =>  "该账户没有权限进行此操作",
    -10053  =>  "未找到用户对应的手机识别码",
    -10054  =>  "未找到安卓手机推送模板信息",
    -10055  =>  "未找到苹果手机推送模板信息",
    -10056  =>  "暂时不支持此设备类型",
    -10057  =>  "推送消息创建失败",
    -10058  =>  "未找到",
    -10059  =>  "未找到该店铺",
    -10060  =>  "请求方法不正确",
    -10061  =>  "未找到该用户任何店铺",
    -10062  =>  "生成营业报表的条件有误",
    -10063  =>  "用户名是由字母开头的5~21位字母，数字和下划线组成的",
    -10064  =>  "请求方法不正确",
    -10065  =>  "未找到该店铺",
    -10066  =>  "未找到该用户任何店铺",
    -10067  =>  "生成财务报表的条件有误",
    -10068  =>  "未找到该订单的详细信息",
    -10069  =>  "未找到该店铺",
    -10070  =>  "未找到该店铺的订单",

    //数据库操作错误
    -15000  =>  "注册失败，请重新注册一下",
    -15001  =>  "获取验证码失败",
    -15002  =>  "获取验证码失败",
    -15003  =>  "重置密码失败",
    -15004  =>  "报名活动失败",
    -15005  =>  "报名活动失败",
    -15006  =>  "取消活动失败",
    -15007  =>  "密码修改失败",
    -15008  =>  "基本信息修改失败",
    -15009  =>  "修改已验证手机失败",
    -15010  =>  "店铺增加失败",
    -15011  =>  "店铺删除失败",
    -15012  =>  "店铺基本信息修改失败",
    -15013  =>  "店铺营业规则修改失败",
    -15014  =>  "店铺营业信息修改失败",
    -15015  =>  "菜品增加失败",
    -15016  =>  "菜品删除失败",
    -15017  =>  "菜品信息修改失败",
    -15018  =>  "菜品状态修改失败",
    -15019  =>  "订单状态修改失败",

    // "1001"   =>   "该账户没有权限进行此操作",
    // "1002"   =>   "未找到登陆账户信息",
    // "1003"   =>   "未找到该店铺详细信息",
    // "1004"   =>   "未找到店铺类型",
    // "1005"   =>   "未找到地理位置信息",
    // "1006"   =>   "店铺增加失败",
    // "1007"  =>   "店铺删除失败",
    // "1008"  =>   "店铺基本信息修改失败",
    // "1009"  =>   "店铺营业规则修改失败",
    // "1010"  =>   "店铺营业信息修改失败",
    // "1011"  =>   "未找到店铺信息类别",
    // "1012"  =>   "菜品增加失败",
    // "1013"  =>   "店铺营业状态修改失败",
    // "1014"  =>   "未找到该店铺",
    // "1015"  =>   "未找到该菜品",
    // "1016"  =>   "菜品信息修改失败",
    // "1017"  =>   "菜品删除失败",
    // "1018"  =>   "菜品状态修改失败",
    // "1019"  =>   "没有此订单信息或者订单状态已改变，请重新刷新数据",
    // "1020"  =>   "订单状态修改失败",
    // "1021"  =>   "注册失败",
    // "1022"  =>   "手机号不正确",
    // "1023"  =>   "获取验证码失败",
    // "1024"  =>   "未获取验证码",
    // "1025"  =>   "验证码已失效，请重新获取验证码",
    // "1026"  =>   "验证码输入错误，请重新输入验证码",
    // "1027"  =>   "该手机号未注册过，请注册新用户",
    // "1028"  =>   "验证规则使用，直接使用验证规则的错误信息",
    // "1029"  =>   "新密码不能与旧密码相同",
    // "1030"  =>   "密码重置失败",
);