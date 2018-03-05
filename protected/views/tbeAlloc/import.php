
<h1>調撥費用管理</h1>
<div class="tableBlue">
<?php echo CHtml::beginForm('','post',array('enctype' => 'multipart/form-data')) ?>
    <table style="width:100%">
        <tr>
            <td width="10%">功能</td>
            <td width="10%"><input type="submit" name="fileupload" value="上傳"></td>
            <td width="30%" colspan="2">區間：
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
                ~
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
<!--            <td width="15%">門市：
                <?php echo CHtml::dropDownList('qry_store', $qry_store, 
                            TbsStore::model()->findByRight(True),    //引用TbsStore- models 裡面的findByRight函式
                            array(
                                  'options'=>array($qry_store=>array('selected'=>'selected')),
                                  'prompt'=>'選擇門市',                
                            ));        
                ?>        
            </td>-->
                
            <td width="25%" colspan="2">調入倉庫：
                <?php echo CHtml::dropDownList('qry_store', $qry_store, CHtml::listData(
                            TbeComWare::model()->findAll(
                            array('order'=>'id ASC','condition'=>'opt1=1')),'wno', 'wname'),
                            array('empty' => '選擇倉庫', 'options' => array($qry_store => array('selected' => 'selected')))
                            );
                ?>        
            </td>
            
            <td width="25%" colspan="2">調撥單號：
            <input size="11" maxlength="11" name="qry_ano" id="qry_ano" 
                       value="<?php echo isset($qry_ano)?$qry_ano:''; ?>" type="text" style="font-size: 20px;"/>
            </td>          
        </tr>
        <tr>
            <td colspan="2">        
                <?php echo CHtml::fileField('filename','' , array('style'=>'width=100%;heigh=36px')); ?>
            </td>
            <td>功能</td>
            <td>
                <?php echo CHtml::submitButton('調撥單查詢', array('name'=>'qry')); ?>
            </td>            
            <td>
                <?php echo CHtml::submitButton('轉出費用單', array('name'=>'export')); ?>
            </td>
            <td>
                <?php echo CHtml::submitButton('費用單查詢',array('name'=>'qryfee'))?>
            </td>
            <td>
                <?php echo CHtml::submitButton('查詢未轉出', array('name'=>'qry_yet')); ?>
            </td>
            <td>
                <?php echo CHtml::submitButton('匯出未轉出', array('name'=>'export_yet')); ?>
            </td> 
        </tr>    
    </table>
<?php echo CHtml::endForm(); ?>    
</div>

<div class="tableBlue">
<?php echo CHtml::beginForm('','post',array('enctype' => 'multipart/form-data')) ?>
    <table style="width:100%">
        <tr>
            <td width="10%">活頁簿</td>
            <td width="10%">筆數</td>
            <td width="50%">訊息</td>                 
        </tr>                                
        <tr>
        <td><?php 
                if($phpexcel!=NULL) {
                        $currentsheet = $phpexcel->getSheet(0); //讀取excel工作表
                        $allrow = $currentsheet->getHighestRow();//取得一共有多少行
                        
                            echo '<td>'. ($allrow-1) .'</td>';                                                     
                } 
            ?>
        </td>       
        <td>
            <?php
            foreach(Yii::app()->user->getFlashes() as $key => $message) {     //畫面訊息,訊息提示-http://www.yiiframework.com/wiki/21/how-to-work-with-flash-messages/ 

                echo "<div class='flash-$key'>" . $message . "</div>\n";
            }
        ?>
        </td>
        </tr>    
    </table>
<?php echo CHtml::endForm(); ?>
</div>

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