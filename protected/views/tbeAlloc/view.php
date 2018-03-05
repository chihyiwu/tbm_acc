<?php
/* @var $this TbeAllocController */
/* @var $model TbeAlloc */

$this->menu=array(
	array('label'=>'明細', 'url'=>array('index')),
	array('label'=>'建立', 'url'=>array('create')),
	array('label'=>'更新', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'刪除', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'管理', 'url'=>array('admin')),
);
?>

<h1>View TbeAlloc #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'ano',
		'adate',
		'make_no',
		'make_emp',
		're_no',
		're_emp',
		'posted',
		'pno',
		'unit',
		'ptalk',
		'pclass',
		'cname',
		'format',
		'num',
		'iware',
		'iwname',
		'oware',
		'owname',
		'avgcost',
		'memo',
		'pass',
		'cost',
		'fno',
		'total',
		'opt1',
		'opt2',
		'opt3',
		'cemp',
		'ctime',
		'uemp',
		'utime',
		'ip',
	),
)); ?>
