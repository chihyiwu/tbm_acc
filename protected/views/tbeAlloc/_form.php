<?php
/* @var $this TbeAllocController */
/* @var $model TbeAlloc */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tbe-alloc-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>


	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'ano'); ?>
		<?php echo $form->textField($model,'ano',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'ano'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'adate'); ?>
		<?php echo $form->textField($model,'adate',array('size'=>9,'maxlength'=>9)); ?>
		<?php echo $form->error($model,'adate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'make_no'); ?>
		<?php echo $form->textField($model,'make_no',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'make_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'make_emp'); ?>
		<?php echo $form->textField($model,'make_emp',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'make_emp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'re_no'); ?>
		<?php echo $form->textField($model,'re_no',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'re_no'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'re_emp'); ?>
		<?php echo $form->textField($model,'re_emp',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'re_emp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'posted'); ?>
		<?php echo $form->textField($model,'posted',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'posted'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pno'); ?>
		<?php echo $form->textField($model,'pno',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'pno'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'unit'); ?>
		<?php echo $form->textField($model,'unit',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'unit'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ptalk'); ?>
		<?php echo $form->textField($model,'ptalk',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'ptalk'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pclass'); ?>
		<?php echo $form->textField($model,'pclass',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'pclass'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cname'); ?>
		<?php echo $form->textField($model,'cname',array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'cname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'format'); ?>
		<?php echo $form->textField($model,'format',array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'format'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'num'); ?>
		<?php echo $form->textField($model,'num',array('size'=>12,'maxlength'=>12)); ?>
		<?php echo $form->error($model,'num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'iware'); ?>
		<?php echo $form->textField($model,'iware',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'iware'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'iwname'); ?>
		<?php echo $form->textField($model,'iwname',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'iwname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'oware'); ?>
		<?php echo $form->textField($model,'oware',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'oware'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'owname'); ?>
		<?php echo $form->textField($model,'owname',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'owname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'avgcost'); ?>
		<?php echo $form->textField($model,'avgcost',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'avgcost'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'memo'); ?>
		<?php echo $form->textField($model,'memo',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'memo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'pass'); ?>
		<?php echo $form->passwordField($model,'pass',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'pass'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cost'); ?>
		<?php echo $form->textField($model,'cost',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'cost'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fno'); ?>
		<?php echo $form->textField($model,'fno',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'fno'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'total'); ?>
		<?php echo $form->textField($model,'total',array('size'=>8,'maxlength'=>8)); ?>
		<?php echo $form->error($model,'total'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'opt1'); ?>
		<?php echo $form->radioButtonList( $model,'opt1', array('1'=>'是','0'=>'否'),array('separator'=>'　')); ?>
		<?php echo $form->error($model,'opt1'); ?>
	</div>

<!--	<div class="row">
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
	</div>-->

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->