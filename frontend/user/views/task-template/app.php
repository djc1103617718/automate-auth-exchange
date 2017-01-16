<?php
use yii\helpers\Url;

$this->title = '';
?>

<div class="app">
    <?= $this->render('_form', [
        'appList' => $appList
    ]) ?>
</div>


<script type="text/javascript">
    $(function() {
        $(document).on('click', '.selectApp', function () {
            var selected = $(this).attr('value');
            $.ajax({
                url: "<?= Url::to(['task-template/app-ajax'])?>",
                type: 'get',
                cache: false,
                data: {
                    app_id: selected
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data.message);
                    if (data.code == 'true') {
                        var url = "<?= Url::to(['task-template/job-create'])?>";
                        var actionLink = '';
                        for (var i = 0; i < data.data.length; i++) {
                            actionLink += "<p><a class='btn btn-primary' style='width:300px;' href=" + url + '&id=' + data.data[i]['action_id'] + ">" + data.data[i]['action_name'] + "</a></p>";
                        }
                        $('#appActionContainer').empty();
                        $('#appActionContainer').append(actionLink);
                    } else {
                        alert(data.message);
                    }
                },
                error: function (data) {
                    alert('error !');
                    if (data.code == 'false') {

                    }
                }
            })
        });
    })


</script>
