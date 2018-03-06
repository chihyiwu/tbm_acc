<?php
/* @var $this TbmAccController */
/* @var $model TbmAcc */

$this->breadcrumbs=array(
	'Tbm Accs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'清單', 'url'=>array('index')),
	array('label'=>'建立', 'url'=>array('create')),
	array('label'=>'檢視', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'管理', 'url'=>array('admin')),
);
?>

<h1>Update TbmAcc <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>