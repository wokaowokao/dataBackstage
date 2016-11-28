//全局变量 是否有排序操作
var isSortSelect = 0;
//时间类型
var dateType = 4;
//用与存储排序节点
var orderFieldIndex = '';
//用于存储排序方式
var orderFieldFlag = 0;
//排序字段
var orderField = '';
//个性化查询
var searchStr = '';
//识别激活的字段 在第几行
var specialFlag = 0;
//数据表格样式
var height = 600;
//今天 昨天..的样式 控制
function setClass(_this){
    _this.addClass('active');
}
//删除今天 昨天等样式
function unsetClass(){
    $('.h2 span').removeClass('active');
}
function unsetDate(){
     $('#from').datebox('setValue','');
     $('#to').datebox('setValue','');
}
//排序 初始化
function unsetSort(){
    $('.datagrid-header-row td .field_active').removeClass('field_active');
    $('.datagrid-header-row td .px').remove();
}
//控制数据表格数据刷新
function reload(dateType,field){
    unsetSort();
    $('#dg').datagrid('reload', {
                isSortSelect : isSortSelect,
                from : $('#from').datebox('getValue'),
                to : $('#to').datebox('getValue'),
                orderField : orderField,
                sortType : orderFieldFlag,
                dateType : dateType,
                table : table,
                searchStr : searchStr
            });
}
//点击今天 昨天 。。事件
function clickDate(_self){
    dateType != 5 && dateType != 6 && unsetDate();
    reload(dateType);
    if(orderFieldIndex !== '') {
        setHeadClass(orderFieldIndex,orderFieldFlag);
    }
}
/*//设置数据表格 排序样式
function setHeadClass(_index,flag){
    $('.datagrid-header-row td span').removeClass('field_active');
    var _this = $('.datagrid-header-row td').eq(_index).find('div');
    _this.addClass('field_active');
    if(flag == 1) _this.html(_this.html() + '<span class="jx px">↓</span>');
    if(flag == 0) _this.html(_this.html() + '<span class="sx px">↑</span>');
}*/
//设置数据表格 排序样式
//specialFlag特殊标示如果 为0就是第一行 如果为1就是第二行
function setHeadClass(_index,flag){
    $('.datagrid-header-row td span').removeClass('field_active');
    if(specialFlag){
        var _this = $('.datagrid-header-row').eq(1).find('td').eq(_index).find('div');
    }else{
        var _this = $('.datagrid-header-row td').eq(_index).find('div');
    }
    _this.addClass('field_active');
    if(flag == 1) _this.html(_this.html() + ' <span class="icon-caret-down jx px"></span>');
    if(flag == 0) _this.html(_this.html() + ' <span class="icon-caret-up sx px"></span>');
}
//时间插件样式
function myformatter(date){
    var y = date.getFullYear();
    var m = date.getMonth()+1;
    var d = date.getDate();
    return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
}
function myparser(s){
        if (!s) return new Date();
        var ss = (s.split('-'));
        var y = parseInt(ss[0],10);
        var m = parseInt(ss[1],10);
        var d = parseInt(ss[2],10);
        if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
            return new Date(y,m-1,d);
        } else {
            return new Date();
        }
}
//获取数据
function queryList(){
    $('#dg').datagrid({
        title : _title,
        iconCls : 'icon-ok',
        height : height,
        pageSize : 30,
        pageList : [ 30 ],
        nowrap : false,
        striped : true,
        collapsible : true,
        queryParams : {
            table : table,
            dateType : dateType
        },
        url : url,
        loadMsg : '数据装载中......',
        singleSelect : true,
        columns : colums,
        showFooter : true,// 显示页脚
        pagination : true
    });
}
var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
var base64DecodeChars = new Array(
　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
　　-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
　　52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
　　-1,　0,　1,　2,　3,  4,　5,　6,　7,　8,　9, 10, 11, 12, 13, 14,
　　15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
　　-1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
　　41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);
function base64encode(str) {
　　var out, i, len;
　　var c1, c2, c3;
　　len = str.length;
　　i = 0;
　　out = "";
　　while(i < len) {
    c1 = str.charCodeAt(i++) & 0xff;
    if(i == len)
    {
　　 out += base64EncodeChars.charAt(c1 >> 2);
　　 out += base64EncodeChars.charAt((c1 & 0x3) << 4);
　　 out += "==";
　　 break;
    }
    c2 = str.charCodeAt(i++);
    if(i == len)
    {
　　 out += base64EncodeChars.charAt(c1 >> 2);
　　 out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
　　 out += base64EncodeChars.charAt((c2 & 0xF) << 2);
　　 out += "=";
　　 break;
    }
    c3 = str.charCodeAt(i++);
    out += base64EncodeChars.charAt(c1 >> 2);
    out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
    out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));
    out += base64EncodeChars.charAt(c3 & 0x3F);
　　}
　　return out;
}
$(function(){
    queryList();
    //分不同时间段 查询
    $('#o').click(function(){
        dateType = 1;
        $('#dg').datagrid({pageList : [ 10 ]});
        $('#dg').datagrid({pageSize : 10 });
        //unsetClass 和 setClass写在clickDate方法里 执行异常。。
        unsetClass();
        setClass($(this));
        setTimeout("clickDate($(this))", 10);
    });
    $('#o2').click(function(){
        dateType = 2;
        $('#dg').datagrid({pageList : [ 10 ]});
        $('#dg').datagrid({pageSize : 10 });
        unsetClass();
        setClass($(this));
        setTimeout("clickDate($(this))", 10);
    });
    $('#o3').click(function(){
        dateType = 3;
        $('#dg').datagrid({pageList : [ 10 ]});
        $('#dg').datagrid({pageSize : 10 });
        unsetClass();
        setClass($(this));
        setTimeout("clickDate($(this))", 10);
    });
    $('#o4').click(function(){
        dateType = 4;
        $('#dg').datagrid({pageList : [ 30 ]});
        $('#dg').datagrid({pageSize : 30 });
        unsetClass();
        setClass($(this));
        setTimeout("clickDate($(this))", 10);
    });
    $('#o5').click(function(){
        if($('#from').datebox('getValue') || $('#to').datebox('getValue')){
            dateType = 5;
            $('#dg').datagrid({pageList : [ 10, 20, 30, 50, 100, 200, 400]});
            $('#dg').datagrid({pageSize : 20 });
            unsetClass();
            setClass($(this));
            setTimeout("clickDate($(this))", 10);
        }else{
            alert('请选择日期！');
        }
    });
    //个性化 查询
    $('#o6').click(function(){
        searchStr = $('#searchStr').val();
        setTimeout("clickDate($(this))", 10);
    });
    $('#tips').on('click', function(){
        layer.open({
            type: 1,
            skin: 'layui-layer-lan', //样式类名
            closeBtn: 0, //不显示关闭按钮
            shift: 3,
            shadeClose: true, //开启遮罩关闭
            title:info[1],
            content: info[0]
        });
    })
    $('#download').click(function(){
        var url = searchStr?'/index.php/ExcelExport/excute/table/' + table + '/dateType/' + dateType + /title/ + _title + '/searchStr/'+ base64encode(encodeURIComponent(searchStr)):'/index.php/ExcelExport/excute/table/' + table + '/dateType/' + dateType + /title/ + _title;
        if(dateType == 5){
            var from = $('#from').datebox('getValue')?$('#from').datebox('getValue'):'*';
            var to = $('#to').datebox('getValue')?$('#to').datebox('getValue'):'*';
            url += '/from/' + from + '/to/' + to;
        }
        location.href = url;
    })
    //复杂表头还是普通表头 绑定不同事件
    if(isSecialExcelTitle){
        //未来事件绑定
        $('body').on("click", ".datagrid-header-row td", function(){
            //第二行触发 第一行只有第一个触发
            var rowIndex = $(this).parent().index();
            if(!rowIndex && $(this).index() != 0) return false;
            orderField = $(this).attr('field');
            orderFieldIndex = $(this).index();
            if($(this).find('span.sx').length) orderFieldFlag = 1;
            else orderFieldFlag = 0;
            isSortSelect = 1;
            //记录激活的是哪一行的字段
            specialFlag = rowIndex;
            reload(dateType,orderField);
            setHeadClass(orderFieldIndex,orderFieldFlag,rowIndex);
        });
    }else{
        //未来事件绑定
        $('body').on("click", ".datagrid-header-row td", function(){
            orderField = $(this).attr('field');
            orderFieldIndex = $(this).index() ;
            if($(this).find('span.sx').length) orderFieldFlag = 1;
            else orderFieldFlag = 0;
            //console.log(orderFieldFlag);
            isSortSelect = 1;
            reload(dateType,orderField);
            setHeadClass(orderFieldIndex,orderFieldFlag);
            //_this.html(ht+'<span class="sx">↑</span>');
            //alert($(this).find('span').eq(0).html());
        });
    }
})
