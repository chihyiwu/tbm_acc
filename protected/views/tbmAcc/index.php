<?php
/* @var $this TbmAccController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tbm Accs',
);

$this->menu=array(
	array('label'=>'建立', 'url'=>array('create')),
	array('label'=>'管理', 'url'=>array('admin')),
);
?>

<h1>Tbm Accs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
