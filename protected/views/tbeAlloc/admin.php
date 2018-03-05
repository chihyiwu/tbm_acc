<?php
/* @var $this TbeAllocController */
/* @var $model TbeAlloc */

//$this->menu=array(
//	array('label'=>'明細', 'url'=>array('index')),
//	array('label'=>'建立', 'url'=>array('create')),
//);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#tbe-alloc-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>管理 調撥單資料表</h1>

<?php //echo CHtml::link('進階搜尋','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tbe-alloc-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
                /*
		'id',
                 */
		'ano',
		'adate',
                /*
		'make_no',
		'make_emp',
		're_no',
		're_emp',
		'posted',
                 */ 
		'pno',
		'unit',
                /*
		'ptalk',
                 */ 
		'pclass',
		'cname',
		'format',
		'num',
		'iware',
		'iwname',
		'oware',
		'owname',
		'avgcost',
                /*
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
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
