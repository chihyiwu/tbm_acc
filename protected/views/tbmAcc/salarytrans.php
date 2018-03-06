<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<h1>彰銀薪資作業</h1>

<div class="tableBlue">
<?php echo CHtml::beginForm(); ?>
    <table>
        <tr>                                              
            <td>年月
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array( //時間週期選擇器-http://www.yiiframework.com/doc/api/1.1/CJuiDatePicker
                        'name' => 'qry_date',
                        'attribute' => 'qry_date',
                        'language' => 'zh-tw',  
                        'value'  => "$qry_date",
                        'options'=> array(
                                    'dateFormat' =>'yymm', //時間設定顯示:EE表示星期，mm表示月份、dd表示日期，而yyyy是西元
                                    'altFormat' =>'yymm',  //jquery顯示年月下拉式選單
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
            <td width="15%">匯款日
            <?php $this->widget('zii.widgets.jui.CJuiDatePicker',array( //時間週期選擇器-http://www.yiiframework.com/doc/api/1.1/CJuiDatePicker
                        'name' => 'qry_remit_date',
                        'attribute' => 'qry_remit_date',
                        'language' => 'zh-tw',  
                        'value'  => "$qry_remit_date",
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
            <td>
                <?php echo CHtml::submitButton('匯入薪資', array('name'=>'import')); ?>
            </td>
            <td>
                <?php echo CHtml::submitButton('匯出文件', array('name'=>'export')); ?>
            </td>            
<!--            <td>
                無帳戶仍作業<input type="checkbox" name="checkbox" value="1" <?php if($checkbox ==1) echo 'checked = true'; ?>>              
            </td>-->
            <td>匯款帳戶：
                <?php echo CHtml::dropDownList('qry_trans', $qry_trans, CHtml::listData(
                            TbmAcc::model()->findAll(
                            array('order'=>'id ASC','condition'=>'opt1=1')),'sbr', 'sender'),
                            array('empty' => '選擇匯款帳戶', 'options' => array($qry_trans => array('selected' => 'selected')))
                            );
                ?>        
            </td>
            <td>分配順序：
                <?php echo CHtml::dropDownList('qry_num', $qry_num,
                            array(1=>'1',2=>'2')
                            );
                ?>        
            </td>
            <td>
                功能
            </td>
            <td>
                <?php echo CHtml::submitButton('查詢明細', array('name'=>'qrytrans')); ?>
            </td>
            <td>
                <?php echo CHtml::submitButton('查詢帳戶分配', array('name'=>'qrytrans_alloc')); ?>
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

<?php ?>
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
<?php ?>