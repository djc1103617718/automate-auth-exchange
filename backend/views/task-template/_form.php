
<div style="position:relative; left:50%;width:100%;text-align: center;">


    <div style="position: relative; right:50%;width:100% ">
        <h1 style="color: #0d6aad">创建任务</h1>
        <div style="margin-top: 30px;">
            <label style="display: inline-block;color:#0d6aad/*#31b0d5*/;font-size: 20px" class="control-label" >选择App平台: </label>
            <div class="btn-group" style="">
                <button type="button" class="btn btn-primary">请选择任务平台...</button>
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <?php
                    if ($appList) {
                        foreach ($appList as $key => $item) {
                            echo "<li><a value='$key' class = 'selectApp' href='#'>$item</a></li>";
                            echo "<li class='divider'></li>";
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <hr style="margin-top: 30px;border-top: solid #d2d6de 1px;"/>
        <div id="appActionContainer" style="padding-top: 20px;">

        </div>
    </div>
</div>

