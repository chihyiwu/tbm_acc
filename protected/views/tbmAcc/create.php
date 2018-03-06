<?php
/* @var $this TbmAccController */
/* @var $model TbmAcc */

$this->breadcrumbs=array(
	'Tbm Accs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'明細', 'url'=>array('index')),
	array('label'=>'管理', 'url'=>array('admin')),
);
?>

<h1>Create TbmAcc</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>