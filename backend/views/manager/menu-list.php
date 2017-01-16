<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '菜单列表';
$this->params['breadcrumbs'][] = $this->title;

?>

<a class="btn btn-success" href="?r=manager/menu-add">添加菜单</a>


<table class="table table-bordered">
    <thead>
    <tr>
        <th class="col-sm-1">id</th>
        <th class="col-sm-1">name</th>
        <th class="col-sm-1">type</th>
        <th class="col-sm-1">request</th>
        <th class="col-sm-1">parent</th>
        <th class="col-sm-1">order</th>
        <th class="col-sm-1">操作</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach( $results as $key =>$val ) { ?>
        <tr class="menu_<?= $val['id'] ?> parent_<?= $val['parent'] ?>">
            <td class="col-sm-1"><?= $val['id'] ?></td>
            <td class="col-sm-1">
                <?php if( $val['type'] == 1 ) {?>
                    <a href="javascript:;" class="more_menu_list" data-level="0" data-id="<?= $val['id'] ?>"><?= $val['name'] ?></a>
                <?php } else {?>
                    <?= $val['name'] ?>
                <?php }?>
            </td>
            <td class="col-sm-1"><?php if( $val['type'] == 0 ) echo '菜单项'; elseif( $val['type'] == 1 ) echo '父菜单栏'; else echo $val['type'];?></td>
            <td class="col-sm-1"><?= $val['request'] ?></td>
            <td class="col-sm-1"><?= $val['parent'] ?></td>
            <td class="col-sm-1"><?= $val['order'] ?></td>
            <td class="col-sm-1"><a href="?r=manager/menu-edit&id=<?= $val['id'] ?>">编辑</a>&nbsp&nbsp&nbsp&nbsp<a href="?r=manager/menu-del&id=<?= $val['id'] ?>">删除</a></td>
        <tr>
        <?php } ?>
    </tbody>

</table>
<script type="text/javascript">
    $(function(){
        $(document).on('click','.more_menu_list',function(){
            var id = $(this).data('id');
            var level = $(this).data('level');
            var this_level = level+1;
            //BFS去除子元素
            var queue = {};queue[0] = id;
            var length = 1;
            var point = 0;
            while( point < length ) {
                $('.parent_'+queue[ point ]).each(function(){
                    queue[ length ] = $(this).data('id');
                    length ++ ;
                    $(this).remove();
                });
                point ++ ;
            }
            var btn =$(this);
            $.ajax({
                url: '/index.php?r=manager/ajax-more-menu',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    _token: '<?php echo Yii::$app->getRequest()->getCsrfToken()?>'
                },
                success: function (data) {
                    for (item in data){
                        var str = '';
                        str += '<tr class="menu_'+data[item]['id']+' parent_'+data[item]['parent']+'" data-id="'+data[item]['id']+'">'+
                            '<td class="col-sm-1">';
                        var i = 1;
                        while(i<this_level) {
                            str += '　　';
                            i++;
                        }
                        str += '┗';
                        str += data[item]['id']+'</td>'+
                            '<td class="col-sm-1">';
                        if( data[item]['type'] == 1 ) {
                            str += '<a href="javascript:;" class="more_menu_list" data-level="'+this_level+'" data-id="'+data[item]['id']+'">';
                            str += data[item]['name'];
                            str += '</a>';
                        }
                        else {
                            str += data[item]['name'];
                        }
                        str += '</td>'+
                            '<td class="col-sm-1">';
                        if( data[item]['type'] == 0 ) str += '菜单项';
                        else if( data[item]['type'] == 1 ) str += '父菜单栏';
                        else str += data[item]['type'];
                        str += '</td><td class="col-sm-1">';
                        if (data[item]['request'] == null) str += '';
                        else str += data[item]['request'];
                        str += '</td><td class="col-sm-1">'+data[item]['parent']+'</td>'+
                            '<td class="col-sm-1">'+data[item]['order']+'</td>'+
                            '<td class="col-sm-1"><a href="?r=manager/menu-edit&id='+data[item]['id']+'">编辑</a>&nbsp&nbsp&nbsp&nbsp'+
                            '<a href="?r=manager/menu-del&id='+data[item]['id']+'">删除</a></td>'+
                            '<tr>';

                        btn.parent().parent().after(str);
                    }
                }
            });
        });
    });
</script>

