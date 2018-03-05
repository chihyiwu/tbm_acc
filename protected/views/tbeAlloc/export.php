<?php
$this->menu=array(	 
        array('label'=>'調撥單匯入','url'=>array('import')),
        );
?>

<h1>費用單匯出</h1>

<div class="tableBlue">
<?php echo CHtml::beginForm(); ?>
    <table>
        <tr>
            <td width="15%">開始：
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array( //時間週期選擇器-http://www.yiiframework.com/doc/api/1.1/CJuiDatePicker
                        'name' => 'qry_dates',
                        'attribute' => 'qry_date',
                        'language' => 'zh-tw',  
                        'value'  => "$qry_dates",
                        'options'=> array(
                                    'dateFormat' =>'yymmdd', //時間設定顯示:EE表示星期，mm表示月份、dd表示日期，而yyyy是西元
                                    'altFormat' =>'yymmdd',  //jquery顯示年月下拉式選單
                                    'changeMonth' => true,
                                    'changeYear' => true,
                                    'yearRange'=>'2013:2020',
                                    ),
                                    'htmlOptions'=>array(
                                    'style'=>'width:100px;'
                                    ),
                        )); 
            ?>
            　至 ：
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array( //時間週期選擇器-http://www.yiiframework.com/doc/api/1.1/CJuiDatePicker
                        'name' => 'qry_datee',
                        'attribute' => 'qry_date',
                        'language' => 'zh-tw',  
                        'value'  => "$qry_datee",
                        'options'=> array(
                                    'dateFormat' =>'yymmdd', //時間設定顯示:EE表示星期，mm表示月份、dd表示日期，而yyyy是西元
                                    'altFormat' =>'yymmdd',  //jquery顯示年月下拉式選單
                                    'changeMonth' => true,
                                    'changeYear' => true,
                                    'yearRange'=>'2013:2020',
                                    ),
                                    'htmlOptions'=>array(
                                    'style'=>'width:100px;'
                                    ),
                        )); 
            ?>                
            </td>                
            <td width="15%">區域：    
            <?php echo  CHtml::dropDownList('qry_area', $qry_area,
            TbsArea::model()->findByRight(True),   //引用TbsArea- models 裡面的findByRight函式
            array(
                  'options'=>array($qry_area=>array('selected'=>'selected')),
                  'ajax'=>array(
                  'type'=>'post',
                  'url'=>  CController::createUrl('tbsCom/dynamicstores',array('update'=>'qry_area')),    //引用tbsCom- Controller 裡面的dynamicstores函式 
                  'update'=>'#qry_store',           
            )));    
            ?>        
            </td>
            <td width="15%">門市：
            <?php echo CHtml::dropDownList('qry_store', $qry_store, 
                        TbsStore::model()->findByRight(True),    //引用TbsStore- models 裡面的findByRight函式
                        array(
                              'options'=>array($qry_store=>array('selected'=>'selected')),
                              'prompt'=>'選擇門市',                
                        ));        
            ?>        
            </td>
            <td width="20%">調撥單號：
            <input size="11" maxlength="11" name="qry_ano" id="qry_ano" 
                       value="<?php echo isset($qry_ano)?$qry_ano:''; ?>" type="text" style="font-size: 20px;"/>
            </td>
            <td>功能</td>
            <td>
                <?php echo CHtml::submitButton('查詢', array('name'=>'qry')); ?>
            </td>
            <td>
                <?php echo CHtml::submitButton('查詢費用單',array('name'=>'qryfee'))?>
            </td>
            <td>
                <?php echo CHtml::submitButton('匯出費用單EXCEL', array('name'=>'export')); ?>
            </td>            
       </tr>
     </table>
<?php echo CHtml::endForm(); ?>
</div>

<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {     //畫面訊息,訊息提示-http://www.yiiframework.com/wiki/21/how-to-work-with-flash-messages/ 
        
        echo "<div class='flash-$key'>" . $message . "</div>\n";
    }
?>

<?php if(isset($fileName) && $fileName!='') : ?>
<div align="center">
        <a href="<?php echo Yii::app()->request->baseUrl; ?>/protected/tmp/<?php echo $fileName; ?>">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/download01.jpg">
        </a>;
 </div>

<?php else : ?>
<div class="tableBlue">
    <table width="5120">
        <tr>
            <?php 
                for ($i = 0; $i < count($col); $i++) : ?>
                <td width="20"><?php echo ($title[$col[$i]])?$title[$col[$i]]:''; ?></td>
            <?php endfor; ?>
        </tr> 
        
            <?php for ($j = 0; $j < count($colAry); $j++) : ?>
        <tr>              
                 <?php for ($i = 0; $i < count($col); $i++) : ?>
                <td ><?php echo (isset($colAry[$j][$col[$i]]))?($colAry[$j][$col[$i]]):''; ?></td>
                <?php endfor; ?>
        </tr>    
            <?php endfor; ?>
    </table>
</div>
<?php endif; ?>
