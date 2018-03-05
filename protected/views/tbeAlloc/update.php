<?php
/* @var $this TbeAllocController */
/* @var $model TbeAlloc */

$this->menu=array(
	array('label'=>'明細', 'url'=>array('index')),
	array('label'=>'建立', 'url'=>array('create')),
	array('label'=>'檢視', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'管理', 'url'=>array('admin')),
);
?>

<h1>更新 調撥單資料表 <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>