<?php
/* @var $this TbmAccController */
/* @var $model TbmAcc */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tbm-acc-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
		<?php echo $form->textField($model,'type',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sender'); ?>
		<?php echo $form->textField($model,'sender',array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'sender'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'nick'); ?>
		<?php echo $form->textField($model,'nick',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'nick'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sbr'); ?>
		<?php echo $form->textField($model,'sbr',array('size'=>16,'maxlength'=>16)); ?>
		<?php echo $form->error($model,'sbr'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'taxid'); ?>
		<?php echo $form->textField($model,'taxid',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'taxid'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'salary'); ?>
		<?php echo $form->textField($model,'salary',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'salary'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remit'); ?>
		<?php echo $form->textField($model,'remit',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'remit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'breakeven'); ?>
		<?php echo $form->textField($model,'breakeven',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'breakeven'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'memo'); ?>
		<?php echo $form->textField($model,'memo',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'memo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'opt1'); ?>
		<?php echo $form->textField($model,'opt1',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'opt1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'opt2'); ?>
		<?php echo $form->textField($model,'opt2',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'opt2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'opt3'); ?>
		<?php echo $form->textField($model,'opt3',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'opt3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cemp'); ?>
		<?php echo $form->textField($model,'cemp',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'cemp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ctime'); ?>
		<?php echo $form->textField($model,'ctime'); ?>
		<?php echo $form->error($model,'ctime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'uemp'); ?>
		<?php echo $form->textField($model,'uemp',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'uemp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'utime'); ?>
		<?php echo $form->textField($model,'utime'); ?>
		<?php echo $form->error($model,'utime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ip'); ?>
		<?php echo $form->textField($model,'ip',array('size'=>15,'maxlength'=>15)); ?>
		<?php echo $form->error($model,'ip'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->