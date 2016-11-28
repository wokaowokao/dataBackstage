var table = 'user';
var url = '/index.php/User/getUserData';
//var info = _info.table;
var colums = [_colums.user];
//console.log(colums);
colums[0][4].formatter = function(){return '<span class="do change_r">修改权限</span><span class="do change_p">重置密码</span><span class="do change_d">删除</span>'};
//数据表格样式
var height = 600;
//控制数据表格数据刷新
function reload(name){
    $('#dg').datagrid('reload', {
        table : table,
        name : name,
    });
}
//获取数据
function queryList(){
    $('#dg').datagrid({
        title : _title,
        iconCls : 'icon-ok',
        height : height,
        pageSize : 20,
        pageList : [ 10, 20, 30, 50, 100, 200, 400 ],
        nowrap : false,
        striped : true,
        collapsible : true,
        queryParams : {
            table : table,
        },
        url : url,
        loadMsg : '数据装载中......',
        singleSelect : true,
        columns : colums,
        showFooter : true,// 显示页脚
        pagination : true
    });
}
//修改用户权限时 勾选权限
function selectRules(r){
	var flag = 0;
	var r = r.split(',');
	for(var a in r){
		if(r[a] == '666'){
			$('.layui-layer').find('input[name="rules[]"][value="666"]').attr('checked',true);
			flag = 1;
			break;
		}
	}
	if(!flag){
		for(var b in r){
			$('.layui-layer').find('input[name="rules[]"][value="'+r[b]+'"]').attr('checked',true);
		}
	}
}
$(function(){
    queryList();
    $('#add').click(function(){
    	//添加用户层
		layer.open({
		  type: 1,
		  title: '<span class="_layer_title">添加用户</span>',
		  skin: 'layui-layer-rim', //加上边框
		  area: ['520px', '370px'], //宽高
		  content: $('#addHtml').html()
		});
    })
    //删除用户
    $('body').on('click','.change_d',function(){
        var id = + $(this).parents('tr').find('td[field="id"] div').html();
        var url = '/index.php/User/del/uid/'+id;
        layer.confirm('您是否确认要删除此用户？', {
            btn: ['是的','取消'] //按钮
        }, function(){
            location.href = url;
        }, function(){
            layer.closeAll();
        });
    })
    //重置密码
    $('body').on('click','.change_p',function(){
    	var id = + $(this).parents('tr').find('td[field="id"] div').html();
    	var url = '/index.php/User/initPD/uid/'+id;
    	location.href = url;
    })
    //修改权限
    $('body').on('click','.change_r',function(){
    	var id = + $(this).parents('tr').find('td[field="id"] div').html();
    	var rules = $(this).parents('tr').find('td[field="rules"] div').html();
    	$('#initRules').find('input[name="uid"]').val(id);
    	layer.open({
		  	type: 1,
		  	title: '<span class="_layer_title">修改用户权限</span>',
		  	skin: 'layui-layer-rim', //加上边框
		  	area: ['520px', '320px'], //宽高
		  	content: $('#initRules').html()
		});
		selectRules(rules);
    })
    $('#search').click(function(){
    	var name = $('#searchStr').val();
    	reload(name);
    })
})
