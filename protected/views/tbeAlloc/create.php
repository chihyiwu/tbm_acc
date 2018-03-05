<?php
/* @var $this TbeAllocController */
/* @var $model TbeAlloc */

$this->menu=array(
	array('label'=>'明細', 'url'=>array('index')),
	array('label'=>'管理', 'url'=>array('admin')),
);
?>

<h1>建立 調撥單資料表</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>