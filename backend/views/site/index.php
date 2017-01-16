<?php
use backend\assets\AppAsset;
use yii\helpers\Url;

AppAsset::addCss($this, '@web/css/sass/panel.css');

$this->title = '';

?>

<div class="panel-box">
	<!--设备管理-->
	<div class="col-sm-6 col-md-4 marTop10">
		<div class="panel-content text-center">
			<div class="panel-title-box">
				<div class="panel-title">
					<i class="glyphicon glyphicon-phone"></i>
					<em>设备管理</em>
				</div>
			</div>
			<div class="table-content">
				<a href="#">
					<div class="col-sm-6 bor-right">
						<h4>养号设备</h4>
						<span>0</span>
					</div>
				</a>
				<a href="#">
					<div class="col-sm-6">
						<h4>任务设备</h4>
						<span>0</span>
					</div>
				</a>
				<a href="#">
					<div class="col-sm-6 bor-right">
						<h4>休眠设备</h4>
						<span>0</span>
					</div>
				</a>
				<a href="#">
					<div class="col-sm-6">
						<h4>异常设备</h4>
						<span>0</span>
					</div>
				</a>
			</div>
		</div>
	</div>
	<!--任务管理-->
	<div class="col-sm-6 col-md-4 marTop10">
		<div class="panel-content text-center">
			<div class="panel-title-box">
				<div class="panel-title">
					<i class="fa fa-th-list"></i>
					<em>任务管理</em>
				</div>
			</div>
			<div class="table-content">
				<a href="<?=Url::to(['job/new-index'])?>">
					<div class="col-sm-6 bor-right">
						<h4>新增任务</h4>
						<span><?=$jobNum['new']?></span>
					</div>
				</a>
				<a href="<?=Url::to(['job/awaiting-index'])?>">
					<div class="col-sm-6">
						<h4>待执行任务</h4>
						<span><?=$jobNum['awaiting']?></span>
					</div>
				</a>
				<a href="<?=Url::to(['job/executing-job'])?>">
					<div class="col-sm-6 bor-right">
						<h4>执行中任务</h4>
						<span><?=$jobNum['executing']?></span>
					</div>
				</a>
				<a href="<?=Url::to(['job/complete-job'])?>">
					<div class="col-sm-6">
						<h4>今日完成任务</h4>
						<span><?=$jobNum['complete']?></span>
					</div>
				</a>
			</div>
		</div>
	</div>
	<!--用户管理-->
	<div class="col-sm-6 col-md-4 marTop10">
		<div class="panel-content text-center">
			<div class="panel-title-box">
				<div class="panel-title">
					<i class="fa fa-users"></i>
					<em>用户管理</em>
				</div>
			</div>
			<div class="table-content">
				<a href="<?=Url::to(['user/index'])?>">
					<div class="col-sm-6 bor-right">
						<h4>新增用户</h4>
						<span><?=$userNum['new']?></span>
					</div>
				</a>
				<a href="<?=Url::to(['user/index'])?>">
					<div class="col-sm-6">
						<h4>总用户</h4>
						<span><?=$userNum['total']?></span>
					</div>
				</a>
				<a href="#">
					<div class="col-sm-6 bor-right">
						<h4>&nbsp;</h4>
						<span>&nbsp;</span>
					</div>
				</a>
				<a href="#">
					<div class="col-sm-6">
						<h4>&nbsp;</h4>
						<span>&nbsp;</span>
					</div>
				</a>
			</div>
		</div>
	</div>
	<!--消息管理-->
	<div class="col-sm-6 col-md-4 marTop30">
		<div class="panel-content text-center">
			<div class="panel-title-box">
				<div class="panel-title">
					<i class="fa fa-commenting"></i>
					<em>消息管理</em>
				</div>
			</div>
			<div class="table-content">
				<ul class="information-content">
					<?php
					if (!empty($noticeModel)) {
						$i = 0;
						foreach ($noticeModel as $item) {
							if ($i >4) {
								$index = Url::to(['notice/index']);
								$str = "<li><a href='{$index}'>";
								$str .= "<span>查看更多</span>";
								$str .= '</li></a>';
								echo $str;
								break;
							}
							$view = Url::to(['notice/view', 'id' => $item['notice_id']]);
							$time = date('Y/m/d H:i', $item['created_time']);
							$str = "<li><a href='{$view}'>";
							$str .= "<span>{$item['description']}</span>";
							$str .= "<em>{$time}</em>";
							$str .= '</li></a>';
							echo $str;

							$i++;
						}
					}
					?>
					<!--<li>
						<a href="">
							<span>您有一条新任务，请尽快审核</span>
							<em>2016/8/5 10:30</em>
						</a>
					</li>-->
				</ul>
			</div>
		</div>
	</div>
	<!--资金管理-->
	<div class="col-sm-6 col-md-4 marTop30">
		<div class="panel-content text-center">
			<div class="panel-title-box">
				<div class="panel-title">
					<i class="fa fa-bar-chart"></i>
					<em>资金管理</em>
				</div>
			</div>
			<div class="table-content">
				<a href="#">
					<div class="col-sm-6 bor-right">
						<h4>昨日收入</h4>
						<span>0</span>
					</div>
				</a>
				<a href="#">
					<div class="col-sm-6">
						<h4>当期收入</h4>
						<span>0</span>
					</div>
				</a>
				<a href="#">
					<div class="col-sm-6 bor-right">
						<h4>累计收入</h4>
						<span>0</span>
					</div>
				</a>
				<a href="#">
					<div class="col-sm-6">
						<h4>&nbsp;</h4>
						<span>&nbsp;</span>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>