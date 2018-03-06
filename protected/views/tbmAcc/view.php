<?php
/* @var $this TbmAccController */
/* @var $model TbmAcc */

$this->breadcrumbs=array(
	'Tbm Accs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'清單', 'url'=>array('index')),
	array('label'=>'建立', 'url'=>array('create')),
	array('label'=>'更新', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'刪除', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'管理', 'url'=>array('admin')),
);
?>

<h1>檢視薪資帳戶 #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type',
		'sender',
		'nick',
		'sbr',
		'taxid',
		'salary',
		'remit',
		'breakeven',
		'memo',
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
