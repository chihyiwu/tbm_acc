<?php
/* @var $this TbmAccController */
/* @var $model TbmAcc */

$this->breadcrumbs=array(
	'Tbm Accs'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'清單', 'url'=>array('index')),
	array('label'=>'建立', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tbm-acc-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>彰銀薪資帳戶管理</h1>

<?php echo CHtml::link('進階搜尋','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tbm-acc-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'type',
		'sender',
		'nick',
		'sbr',
		'taxid',
		/*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
