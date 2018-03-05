<?php
/* @var $this TbeAllocController */
/* @var $dataProvider CActiveDataProvider */

$this->menu=array(
	array('label'=>'建立', 'url'=>array('create')),
	array('label'=>'管理', 'url'=>array('admin')),
);
?>

<h1>調撥單資料表</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
