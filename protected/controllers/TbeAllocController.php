<?php

class TbeAllocController extends RController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

       /**
       * Rights
       * @return array action filters
       */       
        public function filters()
        {
            return array(
                'rights',
            );
        }

        /**
         * Rights
         * @return array access control rules
         */
        public function accessRules()
        {
            return 'index, suggestedTags';
        }	
        
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TbeAlloc;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TbeAlloc']))
		{
			$model->attributes=$_POST['TbeAlloc'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TbeAlloc']))
		{
			$model->attributes=$_POST['TbeAlloc'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('TbeAlloc');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TbeAlloc('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TbeAlloc']))
			$model->attributes=$_GET['TbeAlloc'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

        public function actionImport() 
        {
                ini_set('memory_limit', '256M');  
                XPHPExcel::init();
                $phpexcel = new PHPExcel;            
                $qry_dates = date('Ymd'); 
                $qry_datee = date('Ymd');             
                //門市區域
                $qry_area  = "";  
                //門市名稱
                $qry_store = "";
                //調撥單號名稱
                $qry_ano   = ""; 

                if(isset($_POST['qry_dates'])) $qry_dates = $_POST['qry_dates'];
                if(isset($_POST['qry_datee'])) $qry_datee = $_POST['qry_datee'];
                if(isset($_POST['qry_area']))  $qry_area  = $_POST['qry_area'];
                if(isset($_POST['qry_store'])) $qry_store = $_POST['qry_store'];
                if(isset($_POST['qry_ano']))   $qry_ano   = $_POST['qry_ano'];

                // 取得表格數
                //欄位名稱,0=>'ano'
                $col    = array();
                //欄位顯示的中文字,'ano'=>'調撥單號'
                $title  = array();
                //負責儲存輸出在畫面上的陣列
                $colAry = array();                
                
                $fileName = '';            
                //店編對應區域名稱 007001->8,007022->7 --取消區域查詢                              
                //$stores   = array();            
                //儲存篩選出來的門市
                $tbsstore = array();
                //儲存篩選出來的門市店編
                $sqlstore = array(); 

                //如果有選門市,就只選出那一家門市 007001,007002
                if($qry_store != '') { 
                    
                    $tbsstore = TbsStore::model()->findAllByAttributes(array('storecode'=>$qry_store));              
                }
                //如果只選區域,就選出區域內的所有門市  007001->8,007022->7  
                elseif($qry_area != '') {
                    
                    $tbsstore = TbsStore::model()->findAllByAttributes(array('area_id'=>$qry_area));            
                }
                //如果都沒有選,就全部選出來
                 else 
                    $tbsstore = TbsStore::model()->findAll();    
            
                foreach ($tbsstore as $store) {               
                    //找出篩選出的門市的區域代碼
                    $area = TbsArea::model()->findByPK($store->area_id);  
                    
                    if($area!=NULL) {
                        //push店編
                        array_push($sqlstore, $store->storecode);
                        //店編對應區域名稱
                        $stores[$store->storecode] = $area->areaname;                                   
                    }                      
                }           

                //如果有選門市或區域就需要sql=AND iware in('007001',007002')  
                if($qry_store != '' OR $qry_area != '') {
                    //sql=sql.in('007001')因為只有一筆的時候沒有' , '  所以直接把店編放進去
                    if(count($sqlstore)>0) {
                        
                        $qryStr = " AND iware in ('$sqlstore[0]'";                    
                        //如果門市 > 1, 就需要 ' , '
                        if(count($sqlstore)>1)
                            
                            for ($i = 1; $i < count($sqlstore); $i++) {
                                
                                $qryStr = $qryStr.",'$sqlstore[$i]'";
                            }
                            
                    $qryStr = $qryStr.")";                   
                    }
                }                      

                //選擇門市，用店編查詢資訊 ex.007001 高雄聯興
                if($qry_store != '') {

                    $tbsstore = TbeComWare::model()->findByAttributes(array('wno'=>$qry_store));
                    //當查詢結果不為空，將[店編]丟到$sqlstore上            
                    if(isset($tbsstore)) {

                        array_push($sqlstore,$tbsstore['wno']);
                    }
                }

                //$qryStr 儲存查詢用語法，用於$sql，查詢店編資料           
                $qryStr = '';  

                if($qry_store != '') {

                    if(count($sqlstore) > 0) {

                        $qryStr = " AND iware in ('$sqlstore[0]'";                    
                        $qryStr = $qryStr.")";  
                    }
                }

                //按下查詢 且筆數 > 0
                if(isset($_POST) AND count($_POST)> 0 ) {                  
                    //按下【上傳】調撥單
                    if(isset($_POST['fileupload'])) {      
                        //檔案的名稱與暫存區名稱不等於空值,取得檔案連結
                        if(isset($_FILES['filename']) && $_FILES['filename']['tmp_name']!='') {  
                            //檔案路徑-置於暫存區
                            $filepath = $_FILES['filename']['tmp_name'];                             
                            //檔案名稱來源
                            $filename = $_FILES['filename']['name'];             
                            $phpreader = new PHPExcel_Reader_Excel2007();
                                //不等於版本2007的時候往下讀取舊版本2003系列 
                                if(!$phpreader->canRead($filepath)) {                                                              

                                    $phpreader = new PHPExcel_Reader_Excel5();                            
                                }
                            //讀取EXCEL    
                            $phpexcel = $phpreader->load($filepath); 

                            //讀取第一張工作表
                            $currentsheet = $phpexcel->getSheet(0);  
                            //取得最大的列數 *垂直的是列(ABCD欄位)
                            $allcolumn = $currentsheet->getHighestColumn();   
                            //取得總行數  *水平的是行(1.2.3.4欄位)
                            $allrow = $currentsheet->getHighestRow();  

                            //產品編號對應費用編號
                            $feeAry  = array();
                            //產品編號查詢
                            $pnoAry  = array();
                            //產品編號對應add1
                            $add1Ary = array();
                            //產品編號對應add2
                            $add2Ary = array();
                            //產品編號對應add3
                            $add3Ary = array();  
                            //TbeComProd資料表內opt1 = 1
                            $prodAry = TbeComProd::model()->findAllByAttributes(array('opt1'=>1));  

                            foreach ($prodAry as $key => $row) {                        
                                //用產品編號feeAry[$row->]取得費用編號。EX.B001003 -> O1704 
                                $feeAry[$row->pno] = $row->feeno;
                                //TbeComProd資料庫的產品編號不等於空值，產品編號->內容。EX.J001001 -> J001001 J001 待製品 組 販售洗空瓶-綠	販售洗空瓶-綠
                                if($row->pno!='')  $pnoAry[$row->pno]  = $row;
                                //add1不等於空值，用產品編號$add1Ary[$row->pno]取得附加產品1。EX.B001003 -> J001002
                                if($row->add1!='') $add1Ary[$row->pno] = $row->add1;
                                //add2不等於空值，用產品編號$add2Ary[$row->pno]取得附加產品2
                                if($row->add2!='') $add2Ary[$row->pno] = $row->add2;
                                //add3不等於空值，用產品編號$add3Ary[$row->pno]取得附加產品3  
                                if($row->add3!='') $add3Ary[$row->pno] = $row->add3;                                                                                                          
                            }             
                            $valid = TRUE;
                            $msg = "上傳檔案失敗，原因如下：<br>";

                            //批次寫入宣告
                            $saveAry = new CDbMultiInsertCommand(new TbeAlloc());
                            //儲存筆數
                            $i = 0;
                            //逐筆判斷。$row = 4 =>前3行是報表Title
                            for($row = 4;$row<=$allrow;$row+1) {       
                                //判斷是否遇到"調撥單號"欄位
                                if(($currentsheet->getCellByColumnAndRow(0,$row)->getValue()) == '調撥單號') { 
                                    //筆數+1
                                    $i ++ ;
                                    //遇到的話,欄位加1欄,設定為調撥單號的編號
                                    $row = $row +1 ;
                                    //new model用於存取調撥資料
                                    $model = new TbeAlloc;
                                    //$model為存取固定前7欄
                                    $model = $this->modelCol($model,$currentsheet,$row);        

                                    $ano = $model->ano;
                                    $this->checkano($ano); 
                                    $row = $row + 2 ;
                                    
                                    //判斷調撥資料第一欄是否為空值 
                                    while (($currentsheet->getCellByColumnAndRow(0,$row)->getValue() != '')) {                                       
                                        // 第3個col必為產品編號, 故先判斷否存在
                                        if(isset($pnoAry[$currentsheet->getCellByColumnAndRow(3, $row)->getValue()])) {
                                            //費用編號，判斷是否存在
                                            if(isset($feeAry[$currentsheet->getCellByColumnAndRow(3, $row)->getValue()])) {

                                                //設定$firstcol為調撥內容資料儲存
                                                $firstcol = new TbeAlloc;
                                                $firstcol = $this->addFeeAndSum($firstcol ,$model, $currentsheet, $row, $feeAry);
                                                $saveAry->add($firstcol);
                                            }else{

                                                $valid = FALSE;
                                                $msg = $msg."第 $row 行, 產品編號：".$currentsheet->getCellByColumnAndRow(3, $row)->getValue()." 費用編號不存在。".'<br>';
                                            }

                                            //判斷由產品編號查詢的add1是否存在。
                                            if(isset($add1Ary[$firstcol->pno])) { 

                                                $addpno = $add1Ary[$firstcol->pno];
                                                
                                                if(isset($pnoAry[$addpno])) {
                                                    
                                                    $valid = $saveAry->add($this->checkProdAdd($addpno,$firstcol, $feeAry, $pnoAry[$addpno]));                                                    
                                                }
                                                else {
                                                    $valid = FALSE;
                                                    $msg = $msg."第 $row 行, 產品編號：".$addpno." 附加產品1不存在。".'<br>';
                                                }
                                            }
                                            //判斷由產品編號查詢的add2是否存在
                                            if(isset($add2Ary[$firstcol->pno])) { 

                                                $addpno = $add2Ary[$firstcol->pno];

                                                if(isset($pnoAry[$addpno]))

                                                    $saveAry->add($this->checkProdAdd($addpno,$firstcol, $feeAry, $pnoAry[$addpno]));
                                                else {
                                                    $valid = FALSE;
                                                    $msg = $msg."第 $row 行, 產品編號：".$addpno." 附加產品2不存在。".'<br>';
                                                }
                                            }                                                  
                                            //判斷由產品編號查詢的add3是否存在
                                            if(isset($add3Ary[$firstcol->pno])) { 

                                                $addpno = $add3Ary[$firstcol->pno];

                                                if(isset($pnoAry[$addpno]))

                                                    $saveAry->add($this->checkProdAdd($addpno,$firstcol, $feeAry, $pnoAry[$addpno]));
                                                else {
                                                    $valid = FALSE;
                                                    $msg = $msg."第 $row 行, 產品編號：".$addpno." 附加產品3不存在。".'<br>';
                                                }
                                            }

                                        }else {
                                            $valid = FALSE;
                                            $msg = $msg."第 $row 行, 產品編號：".$currentsheet->getCellByColumnAndRow(3, $row)->getValue()." 不存在。".'<br>';
                                        }

                                        if($valid)                                        
                                            //+1行繼續讀取調撥單資料
                                            $row = $row +1 ;                                  
                                        else
                                            break;                                
                                    }
                                }else 
                                    $row = $row + 1;                                                                                                                                                                                   
                            } 

                            if($valid) {
                                //transaction  資料庫交易功能，資料庫設定需為InnoDB
                                $transaction = Yii::app()->db->beginTransaction();
                                $valid = $saveAry->execute();

                                if($valid) {
                                    
                                    if($transaction->active)                                    
                                        // commit 儲存所有變動
                                        $transaction->commit();                                
                                        Yii::app()->user->setflash('success',"上傳檔案成功！, 共 $i 筆調撥單");                                        
                                }
                                else {
                                        //rollback 取消所有儲存
                                        $transaction->rollback();
                                        Yii::app()->user->setflash('error',"批次寫入檔案失敗！");
                                }
                            }
                            else
                                Yii::app()->user->setflash('error',$msg);                    
                        }else   //檔案名稱與暫存區名稱不等於空值, 否則跑失敗訊息                 
                             Yii::app()->user->setflash('error','上傳檔案失敗');                             
                    }

                    //按下【調撥單查詢】                   
                    if(isset($_POST['qry'])) {  

                        $sql = " SELECT * FROM tbe_alloc "
                             . " WHERE (adate BETWEEN '$qry_dates' AND '$qry_datee' $qryStr ) OR ano = ('$qry_ano') "                      
                             . " ORDER BY ano,fno,id ";                                                                                                     
                        //將資料庫資料建立陣列查詢                                       
                        $colAry = Yii::app()->db->createCommand($sql)->queryAll();  
                        //報表欄位               
                        $col   = $this->getcol();
                        //報表欄位轉中文
                        $title = $this->gettitle();                                                                                                    
                        //判斷$colAry 不為空 且 筆數 >0 輸出成功資訊否則回傳失敗訊息
                        if($colAry !=null && count($colAry)>0) {                               

                            Yii::app()->user->setFlash('success',"查詢成功！共計 ".count($colAry)."筆資料！");
                        }else                                          

                            if(($qry_ano)!=null) {

                                Yii::app()->user->setFlash('error',"查詢失敗，以調撥單號 $qry_ano 查無資料！");
                            }else
                                Yii::app()->user->setFlash('error',"查詢失敗，以日期區間 $qry_dates ~ $qry_datee 查無資料！");                                                                   
                    }                    
                    
                     //按下【查詢未轉出】 
                    if(isset($_POST['qry_yet'])) {
                                                                                              
                        $sqlStr = " SELECT * FROM tbe_alloc "
                                . " WHERE (adate BETWEEN '$qry_dates' AND '$qry_datee') "                      
                                . " ORDER BY ano,fno,id ";
                        
                        $ary_ano = Yii::app()->db->createCommand($sqlStr)->queryAll();                                                                                                  
                        //查詢費用資料庫結果 
                        $ary_dno = array();                                                                                                                                                                     
                            
                        foreach ($ary_ano as $key => $value) {

                            $ary_dno = TbfFeeOld::model()->findAllByAttributes(array('dno'=>'O'.$value['ano']));
                            //查詢結果大於0，就消除該筆$ary_ano的$key                                                             
                            if(count($ary_dno)>0) {

                                unset($ary_ano[$key]);                                  
                            }   
                        }                                   
                            $colAry = array_values($ary_ano);                                
                            //報表欄位               
                            $col   = $this->getcol();
                            //報表欄位轉中文
                            $title = $this->gettitle();

                            if($colAry !=null && count($colAry)>0) {                               

                                Yii::app()->user->setFlash('success',"查詢成功！共計 ".count($colAry)."筆資料！");
                            }else
                                Yii::app()->user->setFlash('error',"查詢失敗，以日期區間 $qry_dates ~ $qry_datee 查無調撥資料！");
                    }
                    
                    //按下【轉出未轉出】
                    if(isset($_POST['export_yet'])) {
                                                                
                        $sql = " SELECT * FROM tbe_alloc "
                              ." WHERE (adate BETWEEN '$qry_dates' AND '$qry_datee') "                 
                              ." ORDER BY ano,fno,id ";                               
                        //將資料庫資料建立陣列
                        $allocAry = Yii::app()->db->createCommand($sql)->queryAll();  
//                        CVarDumper::dump($allocAry,10,true);
                        //費用報表欄位                    
                        $col   = $this->getfeecol();
                        //費用報表欄位轉中文
                        $title = $this->getfeetitle(TRUE);                                                        

                        if(count($allocAry) > 0) {              
                                                    
                            //先取得判斷值：調撥單號ano，費用編號fno                                                                                 
                            $ano = $allocAry[0]['ano'];                  
                            $adate = $allocAry[0]['adate'];
                            $iware = $allocAry[0]['iware'];                                         
                            $fno = $allocAry[0]['fno'];                                                     
                            //費用編號對應費用名稱 'O1701'=>'營業設備'
                            $fname = $this->getFname();                                                                         
                            $feeAry = array();
                            //費用報表欄位帶數值。ex. 'money'=>1 == '金額'=>1
                            $fee = $this->getfeetitle(FALSE); 
                            //現金
                            $cash = 0;
                            //現金總額
                            $cashtotal =0;                                     
                            //備註
                            $memo = '';
                            
                            $i = 0;
                            while ($i < count($allocAry)) {                                                            
                                //當調撥單號相同時
                                if($allocAry[$i]['ano'] == $ano) {                                
                                    //當費用編號相同時   
                                    if($allocAry[$i]['fno'] == $fno) {                                                                                                                                          

                                        $fee['ano'] = $ano;
                                        $fee['dno'] = "O".$fee['ano'];
                                        $fee['adate'] = $adate;
                                        $fee['iware'] = $iware;
                                        $fee['iwareno'] = $iware;                                                                                                                                                                                                                                                                                                                   
                                        $fee['fno'] = $fno;
                                        $fee['fname'] = $fname[$fee['fno']];
                                        $fee['total'] = round($allocAry[$i]['total'] + $fee['total'],0);
                                        $fee['tax'] = $fee['total'];                                                                     
                                        //$fee['avgcost'] = $fee['total'] / $allocAry[$i]['num'];
                                        //平均成本計算方式 採調撥單成本相加 // 也可修改成平均成本相加
                                        $fee['avgcost'] = round($fee['avgcost'] + $allocAry[$i]['cost'],0);                                                                                                                                                                                                     
                                        //費編相同，儲存含稅金額
                                        $cash = $fee['tax'];
                                        //儲存相同Fno的品名規格+數量
                                        $memo = ($allocAry[$i]['format'].'：'.$allocAry[$i]['num'].'<br>').$memo;
                                        
                                        $i++;
                                    }else {
                                        //遇到不同Fno就push
                                        $fee['ememo'] = $memo;
                                        //將備註清空
                                        $memo = '';
                                        //Fno不同，將每筆含稅金額相加
                                        $cashtotal = $cash + $cashtotal;
                                        
                                        array_push($feeAry, $fee);                                                                                                                                                                                                 
                                        //取得新的fno
                                        $fno = $allocAry[$i]['fno'];                                                                                                              
                                        //預設費用報表標頭                                        
                                        $fee = $this->getfeetitle(FALSE);                                        
                                    }
                                    //分錄備註在push
                                    $fee['ememo'] = $memo;
                                    //現金欄位 = 現金總額 + 費編相同的現金
                                    $fee['cash'] = $cashtotal + $cash;
                                }else {                                                                                                            
                                    //調撥單號不同，現金總額歸零
                                    $cashtotal = 0;
                                    //調撥備註
                                    $memo = '';
                                    
                                    array_push($feeAry, $fee);
                                    //取得新的ano
                                    $ano = $allocAry[$i]['ano'];
                                    //取得新的fno
                                    $fno = $allocAry[$i]['fno'];
                                    //取得新的採購人員編號
                                    $iware = $allocAry[$i]['iware'];                                   
                                    //取得新的adate。//轉民國年
                                    //$adate = $this->rechageFormat($allocAry[$i]['adate']); 
                                    //取得新的adate
                                    $adate = $allocAry[$i]['adate'];                                     
                                    //預設費用報表標頭
                                    $fee = $this->getfeetitle(FALSE); 
                                }
                            }                            
                            array_push($feeAry, $fee);                                                        
                        }else { 
                            Yii::app()->user->setFlash('error',"查詢失敗，以日期區間 $qry_dates ~ $qry_datee 查無資料！");                   
                        }
                        
                        // 先取得判斷值：單據號碼dno，門市編號iware                                                                                 
                        $dno = $feeAry[0]['dno'];         
                        $storecode = $feeAry[0]['iware'];
                        $addfname = '';
                        //門市廣告費總計
                        $sumaddfee = 0;
                        //計算行銷費用2獎
                        $sum2adfee  = 0;
                        //計算行銷費用3獎
                        $sum3adfee = 0;
                       
                        // 門市廣告費-費用編號陣列-用來存費用編號所對應之數量
                        // array(
                        //  'O1704' => 4
                        //  'O1707' => 1
                        //  'O1708' => 1
                        // )
                        $addfeeAry = array();                  
                        // 用來門市有被做成廣告費之費用編號及其對應之費用單號
                        // 所以 'O1708' 沒有被做成廣告費
                        // array(
                        //     'O1704' => O20150203010
                        //     'O1707' => O20150203010
                        // ) 
                        $tmpfeeAry = array();                  
                        // 用來紀錄所有門市有被做成廣告費之費用編號及其對應之費用單號
                        // 所以 'O1708' 沒有被做成廣告費
                        // array(
                        // '007047' => array(
                        //             'O1704' => O20150203010
                        //             'O1707' => O20150203010
                        //              ) 
                        // )
                        $finalfeeAry = array();                            
                        //確認廣告費條件是否成立，不成立就跳過
                        $checkAddFee = TRUE;                    
                        //確認費用金額、含稅金額是否為負；為負值則false跳出。
                        $checkfee = TRUE;
                        //行銷費用二獎的備註
                        $sum2adfeememo = '';
                        //行銷費用三獎的備註
                        $sum3adfeememo = '';
                        
                        $i = 0;
                        //進迴圈判斷處理
                        while ($i < count($feeAry)) { 
                            //當單據號碼相同時, 表示為同一家門市
                            if($feeAry[$i]['dno'] == $dno) {                                                                
                                // 查詢中獎資料,判斷是否有未轉廣告費之產品  //利用店編 + 費用單號為空去查詢tbf_lot_detail
                                if(count($addfeeAry)==0 && $checkAddFee) {

                                    $result = array();
                                    $result_type = array();
                                    $result = TbfLotDetail::model()->findAllByAttributes(
                                            array('storecode'=>$storecode, 'dno'=>''),
                                            array('order'=>'type,fno')
                                            );                                
                                    //判斷資料庫是否存有資料，並建立有廣告費之陣列
                                    if(count($result)>0) {
                                        
                                        $result_fno = array();
                                        $result_type = array();
                                        
                                        foreach ($result as $lot) {

                                                $result_fno = $lot->fno;
                                                
                                                $result_type = $lot->type;
                                    
                                            if(isset($addfeeAry[$result_fno])) {                                               
                                              
                                                $addfeeAry[$result_fno]['total'] = $addfeeAry[$result_fno]['total'] + $lot->num;
                                                
                                                //判斷獎項是否存在，存在則累積，不然就新增    
                                                if(isset($addfeeAry[$result_fno][$result_type])){
                                                    
                                                    $addfeeAry[$result_fno][$result_type] = $addfeeAry[$result_fno][$result_type]+ $lot->num;
                                                }else{
                                                    
                                                    $addfeeAry[$result_fno][$result_type] = $lot->num;
                                                }
                                            }else {                                              
                                                
                                                $addfeeAry[$result_fno]['total'] = $lot->num;                                          
                                                $addfeeAry[$result_fno][$result_type] = $lot->num;
                                            }
//                                            CVarDumper::dump($addfeeAry,10,true);
                                        }
                                        $checkAddFee = FALSE;
                                    }                                                                                                                                                                                                               
                                    else
                                        $checkAddFee = FALSE;                                                                                        
                                } 
                               
                                // 假如有需要做廣告費 (addfeeAry > 0)                                                                                                                                      
                                if(count($addfeeAry) > 0) {
                                  
                                    //若費用單內之費用編號存在(fno-> O1704),表示需要轉成廣告費     
                                    if(isset($addfeeAry[$feeAry[$i]['fno']]['total'])) {                                                                                                            
                                        //當費用編號 = O1704 且數量大於1  
                                        if($feeAry[$i]['fno'] == 'O1704') {
                                            //從調撥資料庫撈取同門市+洗髮精                                                                  
                                            $shampoo = TbeAlloc::model()->findAllByAttributes(array('ano'=>$feeAry[$i]['ano'],'pclass'=>'B002'));                                                                                                                                                                                                                                                                                   
                                            //合計數量
                                            $shampoototal = count($shampoo);
                                            //洗髮精合計數量超過1，將平均成本除以合計數量  
                                            if($shampoototal > 1) {

                                                $feeAry[$i]['avgcost'] = $feeAry[$i]['avgcost'] / $shampoototal;                                            
                                            }
                                        }
                                                                              
                                        //算出廣告費
                                        $feeAry[$i]['adfee'] = round($feeAry[$i]['avgcost'] * $addfeeAry[$feeAry[$i]['fno']]['total'],0);                                   
                                        // 費用金額減去廣告費
                                        $feeAry[$i]['total'] = $feeAry[$i]['total'] - $feeAry[$i]['adfee'];
                                        // 含稅金額減去廣告費
                                        $feeAry[$i]['tax']   = $feeAry[$i]['tax'] - $feeAry[$i]['adfee'];
                                        // 加總此筆費用單之廣告費
                                        $sumaddfee = $sumaddfee + $feeAry[$i]['adfee'];
                                        // 將此筆費用編號暫存至已轉換之費用陣列
                                        $tmpfeeAry[$feeAry[$i]['fno']] = $feeAry[$i]['dno'];
                                        //廣告費備註名稱
                                        $addfname = ($feeAry[$i]['fname'].'：'.$feeAry[$i]['adfee'].'<br>').$addfname;
                                                                            
                                        //計算行銷費用二獎
                                        if(isset($addfeeAry[$feeAry[$i]['fno']]['2'])) {
                                            
                                            $sum2adfee = $sum2adfee + round($feeAry[$i]['avgcost'] * $addfeeAry[$feeAry[$i]['fno']]['2'],0);
                                        }
                                        //計算行銷費用三獎
                                        if(isset($addfeeAry[$feeAry[$i]['fno']]['3'])) {
                                            
                                            $sum3adfee = $sum3adfee + round($feeAry[$i]['avgcost'] * $addfeeAry[$feeAry[$i]['fno']]['3'],0);
                                        }
                                        //行銷費用二獎備註
                                        if(($sum2adfee >0) && isset($addfeeAry[$feeAry[$i]['fno']]['2'])) {
                                                
                                            $sum2adfeememo = $sum2adfeememo."二獎：".$feeAry[$i]['fname'].'*'.$addfeeAry[$feeAry[$i]['fno']]['2'].'<br>';                                            
                                        }
                                        //行銷費用三獎備註
                                        if(($sum3adfee >0) && isset($addfeeAry[$feeAry[$i]['fno']]['3'])) {

                                            $sum3adfeememo = $sum3adfeememo."三獎：".$feeAry[$i]['fname'].'*'.$addfeeAry[$feeAry[$i]['fno']]['3'].'<br>';
                                        }
                                     // 3.2.2 若有未轉廣告費的產品，而沒有叫貨該產品，則該產品叫貨成本為負值
                                     //費用編號不存在，但是有行銷費用需要沖銷
                                    }else{
                                        //洗髮精
                                        $addfee_O1704 = 55;
                                        //髮油
                                        $addfee_O1707 = 80;
                                        //髮雕
                                        $addfee_O1708 = 40;
                                        //髮蠟
                                        $addfee_O1709 = 48;
                                        
                                        switch($addfeeAry[$i]['total']) {
                                        
                                            case($i == 'O1704'):
                                                //算出廣告費
                                                $feeAry[$i+1]['adfee'] = round($addfee_O1704 * $addfeeAry['O1704']['total'],0);
                                                $feeAry[$i+1]['fname'] = '銷售洗髮精';
                                                array_push($feeAry,$feeAry[$i+1]['fname']);
                                                break;
                                            case($i == 'O1707'):
                                                //算出廣告費
                                                $feeAry[$i+1]['adfee'] = round($addfee_O1707 * $addfeeAry['O1707']['total'],0);
                                                $feeAry[$i+1]['fname'] = '銷售髮品-髮油';
                                                break;
                                            case($i == 'O1708'):
                                                //算出廣告費
                                                $feeAry[$i+1]['adfee'] = round($addfee_O1708 * $addfeeAry['O1708']['total'],0);
                                                $feeAry[$i+1]['fname'] = '銷售髮品-髮雕';
                                                break;
                                            case($i == 'O1709'):
                                                //算出廣告費
                                                $feeAry[$i+1]['adfee'] = round($addfee_O1709 * $addfeeAry['O1709']['total'],0);
                                                $feeAry[$i+1]['fname'] = '銷售髮品-髮蠟';
                                                break;
                                        }

                                            // 費用金額減去廣告費
                                            $feeAry[$i+1]['total'] = - $feeAry[$i+1]['adfee'];
                                            // 含稅金額減去廣告費
                                            $feeAry[$i+1]['tax']   = - $feeAry[$i+1]['adfee'];
                                            // 加總此筆費用單之廣告費
                                            $sumaddfee = $sumaddfee + $feeAry[$i+1]['adfee'];
                                            // 將此筆費用編號暫存至已轉換之費用陣列
                                            $tmpfeeAry[$feeAry[$i+1]['fno']] = $feeAry[$i+1]['dno'];
                                            //廣告費備註名稱
                                            $addfname = ($feeAry[$i+1]['fname'].'：'.$feeAry[$i+1]['adfee'].'<br>').$addfname;
                                           
                                            switch($addfeeAry[$feeAry[$i]['fno']]['3']) { 
                                               
                                                case($feeAry[$i]['fno'] == 'O1704'):
                                                  
                                                    $sum3adfee = $sum3adfee + round($addfee_O1704 * $addfeeAry['O1704']['3'],0);
                                                    $sum3adfeememo = $sum3adfeememo."三獎：".$feeAry[$i+1]['fname'].'*'.$addfeeAry['O1704']['3'].'<br>';
                                                    break;
                                                case($feeAry[$i]['fno'] == 'O1707'):
                                                  
                                                    $sum3adfee = $sum3adfee + round($addfee_O1707 * $addfeeAry['O1707']['3'],0);
                                                    $sum3adfeememo = $sum3adfeememo."三獎：".$feeAry[$i+1]['fname'].'*'.$addfeeAry['O1707']['3'].'<br>';
                                                    break;
                                                case($feeAry[$i]['fno'] == 'O1708'):
                                                    
                                                    $sum3adfee = $sum3adfee + round($addfee_O1708 * $addfeeAry['O1708']['3'],0);
                                                    $sum3adfeememo = $sum3adfeememo."三獎：".$feeAry[$i+1]['fname'].'*'.$addfeeAry['O1708']['3'].'<br>';
                                                    break;
                                                case($feeAry[$i]['fno'] == 'O1709'):
                                                   
                                                    $sum3adfee = $sum3adfee + round($addfee_O1709 * $addfeeAry['O1709']['3'],0);
                                                    $sum3adfeememo = $sum3adfeememo."三獎：".$feeAry[$i+1]['fname'].'*'.$addfeeAry['O1709']['3'].'<br>';
                                                    break;
                                           }
                                            
                                           
                                    }
                                }                                                                                             
                                array_push($colAry, $feeAry[$i]);                                                         
                                $i++;                                                   
                            // 當費用單號不同時, 判斷是否有廣告費, 若有則新增一筆, 若無則進入下一家門市    
                            }else {                                                                                                            
                                // 若有廣告費
                                if($sum2adfee >0) {                                                                       
                                    // 新增一筆廣告費欄位，複寫$feeAry[$i-1]行=$ary  fno：B0306   fname：代收款-行銷費用
                                    $ary = $feeAry[$i-1];
                                    //備註贈獎品名+數量                                    
                                    $ary['fno'] = 'B0306';
                                    $ary['fname'] = '代收款-行銷費用';
                                    $ary['total'] = $sum2adfee;
                                    $ary['tax'] = $sum2adfee;                                                               
                                    $ary['ememo'] = $sum2adfeememo;
                                    $ary['avgcost'] = '';
                                    $ary['adfee'] = '';                                  
                                    $ary['cash'] = '';
                                    
                                    array_push($colAry, $ary);                              
                                }
                                if($sum3adfee >0) {
                                    // 新增一筆廣告費欄位，複寫$feeAry[$i-1]行=$ary  fno：B0306   fname：代收款-行銷費用
                                    $ary = $feeAry[$i-1];
                                    //備註贈獎品名+數量                                 
                                    $ary['fno'] = 'B0306';
                                    $ary['fname'] = '代收款-行銷費用';
                                    $ary['total'] = $sum3adfee;
                                    $ary['tax'] = $sum3adfee;                                                               
                                    $ary['ememo'] = $sum3adfeememo; 
                                    $ary['avgcost'] = '';
                                    $ary['adfee'] = '';                                  
                                    $ary['cash'] = '';
                                    
                                    array_push($colAry, $ary);
                                }                           
                                $finalfeeAry[$storecode] = $tmpfeeAry;
                                //取得新的單據號碼
                                $dno = $feeAry[$i]['dno'];    
                                //取得新的店編
                                $storecode = $feeAry[$i]['iware']; 
                                //將廣告費歸零重新計算
                                $sumaddfee = 0;
                                $sum2adfee = 0;
                                $sum3adfee = 0;
                                $addfeeAry = array();   
                                $tmpfeeAry = array();
                                $checkAddFee = TRUE;
                                $checkfee = TRUE;
                                $addfname = '';
                                $sum2adfeememo = '';
                                $sum3adfeememo = '';
                            }                            
                        }
                        // 若筆數全部完成，而且有廣告費
                        if($sum2adfee >0) {                                                                                
                            // 新增一筆廣告費欄位，複寫$feeAry[$i-1]行=$ary  fno：B0306   fname：代收款-行銷費用
                            $ary = $feeAry[$i-1];
                            //備註贈獎品名+數量                          
                            $ary['fno'] = 'B0306';
                            $ary['fname'] = '代收款-行銷費用';
                            $ary['total'] = $sum2adfee;
                            $ary['tax'] = $sum2adfee;                                                               
                            $ary['ememo'] = $sum2adfeememo;
                            $ary['avgcost'] = '';
                            $ary['adfee'] = '';
                            $ary['cash'] = '';
                            
                            array_push($colAry, $ary);                       
                        }
                        if($sum3adfee > 0) {
                            // 新增一筆廣告費欄位，複寫$feeAry[$i-1]行=$ary  fno：B0306   fname：代收款-行銷費用
                            $ary = $feeAry[$i-1];
                            //備註贈獎品名+數量                           
                            $ary['fno'] = 'B0306';
                            $ary['fname'] = '代收款-行銷費用';
                            $ary['total'] = $sum3adfee;
                            $ary['tax'] = $sum3adfee;                                                               
                            $ary['ememo'] = $sum3adfeememo;
                            $ary['avgcost'] = '';
                            $ary['adfee'] = '';
                            $ary['cash'] = '';
                            
                            array_push($colAry, $ary);   
                        }                           
                            $finalfeeAry[$storecode] = $tmpfeeAry;                   
                  
                                foreach ($finalfeeAry as $storeno => $storeary) {
                                    foreach ($storeary as $storefno => $storedno) {
                                        //取得行銷尚未沖銷品項
                                        $model = TbfLotdetail::model()->findAllByAttributes(array('storecode'=>$storeno,'fno'=>$storefno,'dno'=>''),array('order'=>'type,fno'));

                                        // for loop 判斷數值存在並寫入
                                        for($i = 0; $i<count($model); $i++) {

                                            $model[$i]->dno = $storedno;
                                            $this->createData($model[$i]);
                                            $model[$i]->save();
                                        }
                                    }                      
                                }                    

                                //宣告 $dno 用來儲存$colAry的 dno
                                $dno = "";                    
                                foreach($colAry as $tbfdno) {                                              
                                    //當dno 不相等時就刪除費用單資料庫中的dno
                                    if($tbfdno['dno'] != $dno) { 
                                        // 刪除
                                        TbfFeeOld::model()->deleteAllByAttributes(array('dno'=>$tbfdno['dno']));  
                                        //重新取得新的dno
                                        $dno = $tbfdno['dno']; 
                                    }
                                    //$tbfdno 是array陣列無法->，所以宣告$fee =Model
                                    $fee = new TbfFeeOld;                         
                                    //$tbfdno 取得的數值放進去$fee
                                    $fee->attributes = $tbfdno;                        
                                    $fee->save();          
                                }

                                // 5 匯出EXCEL
                                //判斷$colAry 不為空 且 筆數 >0 輸出下載資訊否則回傳失敗訊息   
                                if($colAry !=null && count($colAry)>0) {                                           
                                    //建立EXCEL檔案
                                    $fileName = $this->exportEXCEL($qry_dates,$col,$title,$colAry);  
                                    $clickUrl =  "<a href='".Yii::app()->request->baseUrl. '/' . "protected" . '/' . "tmp" . '/' .$fileName. "'>點我下載</a>";
                                    Yii::app()->user->setFlash('success', "調撥單輸出成功！請點擊下載 ".$clickUrl);                     
                                }else
                                    Yii::app()->user->setFlash('error',"查詢失敗，以日期區間 $qry_dates ~ $qry_datee 查無資料！");
                    }                                                                                                                                                
                    
                    //【轉出費用單】並輸出EXCEL
                    if(isset($_POST['export'])) {                                                               

                        $sql = " SELECT * FROM tbe_alloc "
                              ." WHERE (adate BETWEEN '$qry_dates' AND '$qry_datee') "       
                              ." ORDER BY ano,fno,id ";                               
                        //將資料庫資料建立陣列
                        $allocAry = Yii::app()->db->createCommand($sql)->queryAll();  
//                        CVarDumper::dump($allocAry,10,true);
                        //費用報表欄位                    
                        $col   = $this->getfeecol();
                        //費用報表欄位轉中文
                        $title = $this->getfeetitle(TRUE);                                                        

                        // 2. 轉費用單
                        //判斷$allocAry 不為空 且 筆數 >0 輸出成功資訊否則回傳失敗訊息
                        if(count($allocAry)>0) {              
                                                    
                            //先取得判斷值：調撥單號ano，費用編號fno                                                                                 
                            $ano = $allocAry[0]['ano'];                  
                            $adate = $allocAry[0]['adate'];
                            //將20150203 轉 104/02/03
                            //$adate = $this->rechageFormat($allocAry[0]['adate']); 
                            $iware = $allocAry[0]['iware'];                                         
                            $fno = $allocAry[0]['fno'];                                                     
                            //費用編號對應費用名稱 'O1701'=>'營業設備'
                            $fname = $this->getFname();                                                                         
                            $feeAry = array();
                            //費用報表欄位帶數值。ex. 'money'=>1 == '金額'=>1
                            $fee = $this->getfeetitle(FALSE); 
                            //現金
                            $cash = 0;
                            //現金總額
                            $cashtotal =0;                                     
                            //備註
                            $memo = '';
                            
                            $i = 0;
                            while ($i < count($allocAry)) {                                                            
                                //當調撥單號相同時
                                if($allocAry[$i]['ano'] == $ano) {                                
                                    //當費用編號相同時   
                                    if($allocAry[$i]['fno'] == $fno) {                                                                                                                                          

                                        $fee['ano'] = $ano;
                                        $fee['dno'] = "O".$fee['ano'];
                                        $fee['adate'] = $adate;
                                        $fee['iware'] = $iware;
                                        $fee['iwareno'] = $iware;                                                                                                                                                                                                                                                                                                                   
                                        $fee['fno'] = $fno;
                                        $fee['fname'] = $fname[$fee['fno']];
                                        $fee['total'] = round($allocAry[$i]['total'] + $fee['total'],0);
                                        $fee['tax'] = $fee['total'];                                                                     
                                        //$fee['avgcost'] = $fee['total'] / $allocAry[$i]['num'];
                                        //平均成本計算方式 採調撥單成本相加 // 也可修改成平均成本相加
                                        $fee['avgcost'] = round($fee['avgcost'] + $allocAry[$i]['cost'],0);                                                                                                                                                                                                     
                                        //費編相同，儲存含稅金額
                                        $cash = $fee['tax'];
                                        //儲存相同Fno的品名規格+數量
                                        $memo = ($allocAry[$i]['format'].'：'.$allocAry[$i]['num'].'<br>').$memo;
                                        
                                        $i++;
                                    }else {
                                        //遇到不同Fno就push
                                        $fee['ememo'] = $memo;
                                        //將備註清空
                                        $memo = '';
                                        //Fno不同，將每筆含稅金額相加
                                        $cashtotal = $cash + $cashtotal;
                                        
                                        array_push($feeAry, $fee);                                                                                                                                                                                                 
                                        //取得新的fno
                                        $fno = $allocAry[$i]['fno'];                                                                                                              
                                        //預設費用報表標頭                                        
                                        $fee = $this->getfeetitle(FALSE);                                        
                                    }
                                    //分錄備註在push
                                    $fee['ememo'] = $memo;
                                    //現金欄位 = 現金總額 + 費編相同的現金
                                    $fee['cash'] = $cashtotal + $cash;
                                }else {                                                                                                            
                                    //調撥單號不同，現金總額歸零
                                    $cashtotal = 0;
                                    //調撥備註
                                    $memo = '';
                                    
                                    array_push($feeAry, $fee);
                                    //取得新的ano
                                    $ano = $allocAry[$i]['ano'];
                                    //取得新的fno
                                    $fno = $allocAry[$i]['fno'];
                                    //取得新的採購人員編號
                                    $iware = $allocAry[$i]['iware'];                                   
                                    //取得新的adate。//轉民國年
                                    //$adate = $this->rechageFormat($allocAry[$i]['adate']); 
                                    //取得新的adate
                                    $adate = $allocAry[$i]['adate'];                                     
                                    //預設費用報表標頭
                                    $fee = $this->getfeetitle(FALSE); 
                                }
                            }                            
                            array_push($feeAry, $fee);                                                        
                        }else{ 
                            
                            Yii::app()->user->setFlash('error',"查詢失敗，以日期區間 $qry_dates ~ $qry_datee 查無資料！");                   
                        }
                        // 先取得判斷值：單據號碼dno，門市編號iware                                                                                 
                        $dno = $feeAry[0]['dno'];         
                        $storecode = $feeAry[0]['iware'];
                        $addfname = '';
                        //門市廣告費總計
                        $sumaddfee = 0;
                        //計算行銷費用2獎
                        $sum2adfee  = 0;
                        //計算行銷費用3獎
                        $sum3adfee = 0;
                       
                        // 門市廣告費-費用編號陣列-用來存費用編號所對應之數量
                        // array(
                        //  'O1704' => 4
                        //  'O1707' => 1
                        //  'O1708' => 1
                        // )
                        $addfeeAry = array();                  
                        // 用來門市有被做成廣告費之費用編號及其對應之費用單號
                        // 所以 'O1708' 沒有被做成廣告費
                        // array(
                        //     'O1704' => O20150203010
                        //     'O1707' => O20150203010
                        // ) 
                        $tmpfeeAry = array();                  
                        // 用來紀錄所有門市有被做成廣告費之費用編號及其對應之費用單號
                        // 所以 'O1708' 沒有被做成廣告費
                        // array(
                        // '007047' => array(
                        //             'O1704' => O20150203010
                        //             'O1707' => O20150203010
                        //              ) 
                        // )
                        $finalfeeAry = array();                            
                        //確認廣告費條件是否成立，不成立就跳過
                        $checkAddFee = TRUE;                    
                        //確認費用金額、含稅金額是否為負；為負值則false跳出。
                        $checkfee = TRUE;
                        //行銷費用二獎的備註
                        $sum2adfeememo = '';
                        //行銷費用三獎的備註
                        $sum3adfeememo = '';
                        
                        $i = 0;
                        //當$i 數量小於轉費用的筆數時表示需要進行處理                                                       
                        while ($i < count($feeAry)) { 
                            //當單據號碼相同時, 表示為同一家門市
                            if($feeAry[$i]['dno'] == $dno) {                                                                
                                // 查詢中獎資料,判斷是否有未轉廣告費之產品  //利用店編 + 費用單號為空去查詢tbf_lot_detail
                                if(count($addfeeAry)==0 && $checkAddFee) {

                                    $result = array();
                                    $result_type = array();
                                    $result = TbfLotDetail::model()->findAllByAttributes(
                                            array('storecode'=>$storecode, 'dno'=>''),
                                            array('order'=>'type,fno')
                                            );                                
                                    //判斷資料庫是否存有資料，並建立有廣告費之陣列
                                    if(count($result)>0) {
                                        
                                        $result_fno = array();
                                        $result_type = array();
                                        
                                        foreach ($result as $lot) {
                                            
                                                $result_fno = $lot->fno;
                                             
                                                $result_type = $lot->type;

                                            //當 $addfeeAry 費用編號重複存在則累加數量，不然直接給予儲存數量
                                            /* 2獎 O1704->array('total'=>1,'2'=>1)
                                             *     O1707->1
                                             *     O1708->1
                                             * 3獎 O1704->3
                                             */                                       
                                            if(isset($addfeeAry[$result_fno])) {                                               
                                              
                                                $addfeeAry[$result_fno]['total'] = $addfeeAry[$result_fno]['total'] + $lot->num;                                               
                                                //判斷獎項是否存在，存在則累積，不然就新增    
                                                if(isset($addfeeAry[$result_fno][$result_type])){
                                                    
                                                    $addfeeAry[$result_fno][$result_type] = $addfeeAry[$result_fno][$result_type]+ $lot->num;
                                                }else{
                                                    
                                                    $addfeeAry[$result_fno][$result_type] = $lot->num;
                                                }
                                            }else {                                              
                                                
                                                $addfeeAry[$result_fno]['total'] = $lot->num;                                                                                                                                        
                                                    
                                                $addfeeAry[$result_fno][$result_type] = $lot->num;                                              
                                            }
                                            //CVarDumper::dump($addfeeAry,10,true);
                                        }
                                        $checkAddFee = FALSE;
                                    }                                                                                                                                                                                                               
                                    else
                                        $checkAddFee = FALSE;                                                                                        
                                } 
                                
                                // 假如有需要做廣告費 (addfeeAry > 0)                                                                                                                                      
                                if(count($addfeeAry)>0) {
                                    //若費用單內之費用編號存在(fno-> O1704),表示需要轉成廣告費
                                    if(isset($addfeeAry[$feeAry[$i]['fno']]['total'])) {                                                                                                            
                                        //當費用編號 = O1704 且數量大於1  
                                        if($feeAry[$i]['fno'] == 'O1704') {
                                            //從調撥資料庫撈取同門市+洗髮精                                                                  
                                            $shampoo = TbeAlloc::model()->findAllByAttributes(array('ano'=>$feeAry[$i]['ano'],'pclass'=>'B002'));                                                                                                                                                                                                                                                                                   
                                            //合計數量
                                            $shampoototal = count($shampoo);
                                            //洗髮精合計數量超過1，將平均成本除以合計數量  
                                            if($shampoototal > 1) {

                                                $feeAry[$i]['avgcost'] = $feeAry[$i]['avgcost'] / $shampoototal;                                            
                                            }
                                        }
                                                                              
                                        //算出廣告費
                                        $feeAry[$i]['adfee'] = round($feeAry[$i]['avgcost'] * $addfeeAry[$feeAry[$i]['fno']]['total'],0);                                   
                                        // 費用金額減去廣告費
                                        $feeAry[$i]['total'] = $feeAry[$i]['total'] - $feeAry[$i]['adfee'];
                                        // 含稅金額減去廣告費
                                        $feeAry[$i]['tax']   = $feeAry[$i]['tax'] - $feeAry[$i]['adfee'];
                                        // 加總此筆費用單之廣告費
                                        $sumaddfee = $sumaddfee + $feeAry[$i]['adfee'];
                                        // 將此筆費用編號暫存至已轉換之費用陣列
                                        $tmpfeeAry[$feeAry[$i]['fno']] = $feeAry[$i]['dno'];
                                        //廣告費備註名稱
                                        $addfname = ($feeAry[$i]['fname'].'：'.$feeAry[$i]['adfee'].'<br>').$addfname;
                                                                            
                                        //計算行銷費用二獎
                                        if(isset($addfeeAry[$feeAry[$i]['fno']]['2'])) {
                                            
                                            $sum2adfee = $sum2adfee + round($feeAry[$i]['avgcost'] * $addfeeAry[$feeAry[$i]['fno']]['2'],0);
                                        }
                                        //計算行銷費用三獎
                                        if(isset($addfeeAry[$feeAry[$i]['fno']]['3'])) {
                                            
                                            $sum3adfee = $sum3adfee + round($feeAry[$i]['avgcost'] * $addfeeAry[$feeAry[$i]['fno']]['3'],0);
                                        }
                                        //行銷費用二獎備註
                                        if(($sum2adfee >0) && isset($addfeeAry[$feeAry[$i]['fno']]['2'])) {
                                                
                                            $sum2adfeememo = $sum2adfeememo."二獎：".$feeAry[$i]['fname'].'*'.$addfeeAry[$feeAry[$i]['fno']]['2'].'<br>';                                            
                                        }
                                        //行銷費用三獎備註
                                        if(($sum3adfee >0) && isset($addfeeAry[$feeAry[$i]['fno']]['3'])) {
                                           
                                            $sum3adfeememo = $sum3adfeememo."三獎：".$feeAry[$i]['fname'].'*'.$addfeeAry[$feeAry[$i]['fno']]['3'].'<br>';                                           
                                        }
                                                                        
                                    }
                                    // 判斷[費用金額]或[含稅金額]計算結果, 若為負, 則產生錯誤訊息並跳出
                                    if(($feeAry[$i]['total'] < 0 ) OR ($feeAry[$i]['tax'] < 0 )) {

                                        Yii::app()->user->setFlash('error',"計算失敗，金額不得為負值！");                                   
                                        $checkfee = FALSE;
                                    }                                                                    
                                }                                                                                             
                                array_push($colAry, $feeAry[$i]);                                                         
                                $i++;                                                   
                            // 當費用單號不同時, 判斷是否有廣告費, 若有則新增一筆, 若無則進入下一家門市    
                            }else {                                                                                                            
                                // 若有廣告費
                                if($sum2adfee >0) {                                                                       
                                    // 新增一筆廣告費欄位，複寫$feeAry[$i-1]行=$ary  fno：B0306   fname：代收款-行銷費用
                                    $ary = $feeAry[$i-1];
                                    //備註贈獎品名+數量                                    
                                    $ary['fno'] = 'B0306';
                                    $ary['fname'] = '代收款-行銷費用';
                                    $ary['total'] = $sum2adfee;
                                    $ary['tax'] = $sum2adfee;                                                               
                                    $ary['ememo'] = $sum2adfeememo;
                                    $ary['avgcost'] = '';
                                    $ary['adfee'] = '';                                  
                                    $ary['cash'] = '';
                                    
                                    array_push($colAry, $ary);                              
                                }
                                if($sum3adfee >0) {
                                    // 新增一筆廣告費欄位，複寫$feeAry[$i-1]行=$ary  fno：B0306   fname：代收款-行銷費用
                                    $ary = $feeAry[$i-1];
                                    //備註贈獎品名+數量                                 
                                    $ary['fno'] = 'B0306';
                                    $ary['fname'] = '代收款-行銷費用';
                                    $ary['total'] = $sum3adfee;
                                    $ary['tax'] = $sum3adfee;                                                               
                                    $ary['ememo'] = $sum3adfeememo; 
                                    $ary['avgcost'] = '';
                                    $ary['adfee'] = '';                                  
                                    $ary['cash'] = '';
                                    
                                    array_push($colAry, $ary);
                                }                           
                                $finalfeeAry[$storecode] = $tmpfeeAry;
                                //取得新的單據號碼
                                $dno = $feeAry[$i]['dno'];    
                                //取得新的店編
                                $storecode = $feeAry[$i]['iware']; 
                                //將廣告費歸零重新計算
                                $sumaddfee = 0;
                                $sum2adfee = 0;
                                $sum3adfee = 0;
                                $addfeeAry = array();   
                                $tmpfeeAry = array();
                                $checkAddFee = TRUE;
                                $checkfee = TRUE;
                                $addfname = '';
                                $sum2adfeememo = '';
                                $sum3adfeememo = '';
                            }                            
                        }
                        // 若筆數全部完成，而且有廣告費
                        if($sum2adfee >0) {                                                                                
                            // 新增一筆廣告費欄位，複寫$feeAry[$i-1]行=$ary  fno：B0306   fname：代收款-行銷費用
                            $ary = $feeAry[$i-1];
                            //備註贈獎品名+數量                          
                            $ary['fno'] = 'B0306';
                            $ary['fname'] = '代收款-行銷費用';
                            $ary['total'] = $sum2adfee;
                            $ary['tax'] = $sum2adfee;                                                               
                            $ary['ememo'] = $sum2adfeememo;
                            $ary['avgcost'] = '';
                            $ary['adfee'] = '';
                            $ary['cash'] = '';
                            
                            array_push($colAry, $ary);                       
                        }
                        if($sum3adfee >0) {
                            // 新增一筆廣告費欄位，複寫$feeAry[$i-1]行=$ary  fno：B0306   fname：代收款-行銷費用
                            $ary = $feeAry[$i-1];
                            //備註贈獎品名+數量                           
                            $ary['fno'] = 'B0306';
                            $ary['fname'] = '代收款-行銷費用';
                            $ary['total'] = $sum3adfee;
                            $ary['tax'] = $sum3adfee;                                                               
                            $ary['ememo'] = $sum3adfeememo;
                            $ary['avgcost'] = '';
                            $ary['adfee'] = '';
                            $ary['cash'] = '';
                            
                            array_push($colAry, $ary);   
                        }                           
                        $finalfeeAry[$storecode] = $tmpfeeAry;                   
                        
                        // 將已做成廣告費之費用單號, 回寫至 tbf_lot_detail 中獎產品明細資料庫
                        // 利用 店編 => finalFeeAry
                        // 判斷 $finalFeeAry 是否存有做廣告費的店編，如有，則需要將dno回寫tbf_lot_detail資料庫                  
                        foreach ($finalfeeAry as $storeno => $storeary) {
                            foreach ($storeary as $storefno => $storedno) {
                                
                                $model = TbfLotdetail::model()->findAllByAttributes(array('storecode'=>$storeno,'fno'=>$storefno,'dno'=>''),array('order'=>'type,fno'));                                                                                   
                                // for loop 判斷數值存在並寫入
                                for($i = 0; $i<count($model); $i++) {
                                    
                                    $model[$i]->dno = $storedno;
                                    $this->createData($model[$i]);
                                    $model[$i]->save();
                                }
                            }                      
                        }                    

                        //宣告 $dno 用來儲存$colAry的 dno
                        $dno = "";                    
                        foreach($colAry as $tbfdno) {                                              
                            //當dno 不相等時就刪除費用單資料庫中的dno
                            if($tbfdno['dno'] != $dno) { 
                                // 刪除
                                TbfFeeOld::model()->deleteAllByAttributes(array('dno'=>$tbfdno['dno']));  
                                //重新取得新的dno
                                $dno = $tbfdno['dno']; 
                            }
                            //$tbfdno 是array陣列無法->，所以宣告$fee =Model
                            $fee = new TbfFeeOld;                         
                            //$tbfdno 取得的數值放進去$fee
                            $fee->attributes = $tbfdno;                        
                            $fee->save();          
                        }
                        
                        // 5 匯出EXCEL
                        //判斷$colAry 不為空 且 筆數 >0 輸出下載資訊否則回傳失敗訊息   
                        if($colAry !=null && count($colAry)>0) {                                           
                            //建立EXCEL檔案
                            $fileName = $this->exportEXCEL($qry_dates,$col,$title,$colAry);  
                            $clickUrl =  "<a href='".Yii::app()->request->baseUrl. '/' . "protected" . '/' . "tmp" . '/' .$fileName. "'>點我下載</a>";
                            Yii::app()->user->setFlash('success', "調撥單輸出成功！請點擊下載 ".$clickUrl);                     
                        }else
                            Yii::app()->user->setFlash('error',"查詢失敗，以日期區間 $qry_dates ~ $qry_datee 查無資料！");                       
                    }                                                             
                    
                    //【費用單查詢】
                    if(isset($_POST['qryfee'])) {  

                        $sql = " SELECT * FROM tbf_fee_old "
                               . "WHERE (adate BETWEEN '$qry_dates' AND '$qry_datee' $qryStr )"                      
                               . "ORDER BY dno,fno,id";                                                                                                     
                        //將資料庫資料建立陣列查詢                                       
                        $colAry = Yii::app()->db->createCommand($sql)->queryAll();                                     
                        $col    = $this->getfeecol();
                        //費用報表欄位
                        $title  = $this->getfeetitle(TRUE);                                                                                                  
                        //判斷$colAry 不為空 且 筆數 >0 輸出成功資訊否則回傳失敗訊息
                        if($colAry !=null && count($colAry)>0) {                               

                            Yii::app()->user->setFlash('success',"查詢成功！共計 ".count($colAry)."筆資料！");
                        }else                                                                 
                            Yii::app()->user->setFlash('error',"查詢失敗，以日期區間 $qry_dates ~ $qry_datee 查無資料！");    
                    }
                }                                                                         
                $this->render('import',array(
                            'phpexcel'  =>$phpexcel,
                            'qry_dates' =>$qry_dates,
                            'qry_datee' =>$qry_datee,
                            'qry_area'  =>$qry_area, 
                            'qry_store' =>$qry_store,
                            'qry_ano'   =>$qry_ano,
                            'col'       =>$col,
                            'title'     =>$title,
                            'colAry'    =>$colAry,                 
                            'fileName'  =>$fileName,               
                            ));                      
        }
                
        /**
         * 輸出EXCEL
         * @param type $qry_dates - 開始年-月
         * @param type $col - 欄位
         * @param type $title - 標題列
         * @param type $colAry - 資料列
         */    
        private function exportEXCEL($qry_dates,$col, $title, $colAry)
        {        
                // PHP EXCEL 初始化
                XPHPExcel::init();
                $fileTitle = "JIT $qry_dates Export File";
                $objPHPExcel= XPHPExcel::createPHPExcel();
                $objPHPExcel->getProperties()->setCreator("JIT")
                                             ->setLastModifiedBy("JIT")
                                             ->setTitle($fileTitle)
                                             ->setSubject("")
                                             ->setDescription($fileTitle)
                                             ->setKeywords("office 2007 openxml php")
                                             ->setCategory("Export File");

                // 第一列 填入標題，由第0欄開始
                $column = 0;            
                for ($i = 0; $i < count($col); $i++) {

                    if(isset($title[$col[$i]])) {

                        $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($column,1, (isset($title[$col[$i]]))?
                        $title[$col[$i]]:'',PHPExcel_Cell_DataType::TYPE_STRING);
                        $column++;
                    }
                }            
                // 由第2列開始
                $row = 2;
                for ($j = 0; $j < count($colAry); $j++) {

                    if(isset($colAry[$j][$col[0]])) {
                        // 第幾欄. 由第0欄開始
                        $column = 0; 
                        for ($i = 0; $i < count($col); $i++) {
                            // 若符合篩選欄位. 才進行
                            if(isset($title[$col[$i]])) {

                                //避免(007002變7002) 0被吃掉，所以關鍵 setValueExplicit所有都轉字串                          
                                $objPHPExcel->getActiveSheet()->getCellByColumnAndRow($column, $row)->setValueExplicit((isset($colAry[$j][$col[$i]]))?
                                $colAry[$j][$col[$i]]:'', PHPExcel_Cell_DataType::TYPE_STRING);

                                $column++;
                            }
                        }
                    $row++;
                    }
                }
                //sheet 表名稱
                $objPHPExcel->getActiveSheet()->setTitle($qry_dates.'調撥費用單');
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);
                // Redirect output to a web browser (Excel5)
                $webroot = Yii::getPathOfAlias('webroot');
                //$fileName =$excelname.'-'.time().'.xls';
                $fileName = $qry_dates.time().'.xls';
                $filePath = $webroot . '/' . "protected" . '/' . "tmp" . '/';
                $fileUrl = $filePath.$fileName;
                // If you're serving to IE over SSL, then the following may be needed
                // header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                // header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                // header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                // header ('Pragma: public'); // HTTP/1.0
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save($fileUrl);
                return $fileName;
        }               
    
        /**
        *   設定調撥單標題列
        */
        private function getcol() 
        {
                $qry = array(
                    0 =>'ano',
                    1 =>'adate',
                    2 =>'make_no',
                    3 =>'make_emp',
                    4 =>'re_no',
                    5 =>'re_emp',
                    6 =>'posted',
                    7 =>'pno',
                    8 =>'unit',
                    9 =>'ptalk',
                    10 =>'pclass',
                    11 =>'cname',
                    12 =>'format',
                    13 =>'num',
                    14 =>'iware',
                    15 =>'iwname',
                    16 =>'oware',
                    17 =>'owname',
                    18 =>'avgcost',
                    19 =>'memo',
                    20 =>'pass',
                    21 =>'cost',
                    22 =>'fno',
                    23 =>'total',
                );
                return $qry; 
        }

        /**
        *   設定調撥單標題列轉中文
        */
        private function getTitle() 
        {
                $title = array(
                    'ano'=>'調撥單號',
                    'adate'=>'調撥日期',
                    'make_no'=>'製單人員編號',
                    'make_emp'=>'製單人員',
                    're_no'=>'覆核人員編號',
                    're_emp'=>'覆核人員',
                    'posted'=>'是否過帳',
                    'pno'=>'產品編號',
                    'unit'=>'計量單位',
                    'ptalk'=>'產品說明',
                    'pclass'=>'產品類別',
                    'cname'=>'類別名稱',
                    'format'=>'品名規格',
                    'num'=>'數量',
                    'iware'=>'撥入倉庫',
                    'iwname'=>'撥入倉庫名稱',
                    'oware'=>'撥出倉庫',
                    'owname'=>'撥出倉庫名稱',
                    'avgcost'=>'平均成本',
                    'memo'=>'備註',
                    'pass'=>'是否過帳',
                    'cost'=>'成本',
                    'fno'=>'費用編號',
                    'total'=>'總計'                              
                );
                return $title;
        }
        
        /**
        *   設定費用單標題列
        */
        private function getfeecol()
        {
                $qrycol = array(
                    0 =>'dno',
                    1 =>'adate',
                    2 =>'vno',
                    3 =>'accounthome',
                    4 =>'currency',
                    5 =>'erate',
                    6 =>'money',
                    7 =>'taxclass',
                    8 =>'iware',
                    9 =>'iwareno',
                    10 =>'contact',
                    11 =>'tel',
                    12 =>'bpno',
                    13 =>'opt1',
                    14 =>'opt2',
                    15 =>'memo',
                    16 =>'fno',
                    17 =>'fname',
                    18 =>'total',
                    19 =>'tax',
                    20 =>'account',
                    21 =>'ctitle',
                    22 =>'ememo',
                    23 =>'avgcost',
                    24 =>'adfee',
                    25 =>'cash'
                );
                return $qrycol;
        }

        /**
        * 費用單標題列轉中文, 可傳入變數清空欄位值
        * @param boolean $blank - 是否清空欄位值
        * @return array()
        */
        private function getfeetitle($blank)
        {
                $feetitle = array();

                if($blank)
                    $feetitle = array(
                        'dno'=>'單據號碼',
                        'adate'=>'費用日期',
                        'vno'=>'廠商編號',
                        'accounthome'=>'帳款歸屬',
                        'currency'=>'使用幣別',
                        'erate'=>'匯率',
                        'money'=>'金額',
                        'taxclass'=>'課稅類別',
                        'iware'=>'採購人員編號',
                        'iwareno'=>'所屬部門編號',
                        'contact'=>'聯絡人員',
                        'tel'=>'聯絡電話',
                        'bpno'=>'所屬專案編號',
                        'opt1'=>'自訂欄位一',
                        'opt2'=>'自訂欄位二',
                        'memo'=>'備註',
                        'fno'=>'費用編號',
                        'fname'=>'費用名稱',
                        'total'=>'費用金額',
                        'tax'=>'含稅金額',
                        'account'=>'會計科目編號',
                        'ctitle'=>'(科目名稱)',
                        'ememo'=>'分錄備註',
                        'avgcost'=>'平均成本',
                        'adfee'=>'廣告費',
                        'cash' =>'現金'
                    );
                    else {
                        $feetitle = array(
                        'dno'=>'',    
                        'adate'=>'',
                        'vno'=>'OF001',
                        'accounthome'=>'',
                        'currency'=>'',
                        'erate'=>'',
                        'money'=>'1',
                        'taxclass'=>'4',
                        'iware'=>'',
                        'iwareno'=>'',
                        'contact'=>'',
                        'tel'=>'',
                        'bpno'=>'',
                        'opt1'=>'',
                        'opt2'=>'',
                        'memo'=>'',
                        'fno'=>'',
                        'fname'=>'',
                        'total'=>0,
                        'tax'=>0,
                        'account'=>'',
                        'ctitle'=>'',
                        'ememo'=>'',
                        'avgcost'=>0,
                        //'adfee'=>0
                        'cash'=>''   
                        );
                    }        
                return $feetitle;
        }
        
        /**
        *  費用編號對應費用名稱
        */
        private function getFname() 
        {        
                $fname = array(             
                   'O1701'=>'營業設備',
                   'O1702'=>'其他營業耗材',
                   'O1703'=>'職洗洗髮精',
                   'O1704'=>'銷售洗髮精',
                   'O1705'=>'圍脖紙',
                   'O1706'=>'染膏',
                   'O1707'=>'銷售髮品-髮油',
                   'O1708'=>'銷售髮品-髮雕',
                   'O1709'=>'銷售髮品-髮蠟',
                   'O1710'=>'銷售髮品-髮凍'                             
                );
                return $fname;                
        }    
        
        /**
        * 新增空瓶
        * @param type $pno --產品編號
        * @param type $firstcol --調撥單
        * @param type $feeAry --產品編號對應費用編號
        * @param type $prod --附加產品資訊
        */
        private function checkProdAdd($pno, $firstcol, $feeAry, $prod) 
        {        
                $secondcol = new TbeAlloc;                                       
                //新增空瓶
                $secondcol = $this->additionProd($secondcol, $firstcol, $prod, $feeAry);       

                return $secondcol;
        }
        
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TbeAlloc the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
                $model=TbsTestDb::model()->findByPk($id);

                if($model===null)

                    throw new CHttpException(404,'The requested page does not exist.');

                return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TbsTestDb $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
                if(isset($_POST['ajax']) && $_POST['ajax']==='tbs-test-db-form')
                {
                        echo CActiveForm::validate($model);
                        Yii::app()->end();
                }
	}
        
        /**
        *  民國年/月/日 轉西元日期
        */    
        private function chageFormat($in_date) 
        {        
                // 104/05/06
                $cyear = substr($in_date, 0, 3);
                $year = ((int) $cyear )+1911;
                $mon = substr($in_date, 4, 2);
                $day = substr($in_date, -2);
                // 20150506
                $date = date("Ymd", mktime (0,0,0,$mon ,$day, date($year)));

                return $date;
        }

        /**
        *  西元日期轉 民國/年/月/日 
        */
        private function rechageFormat($in_date, $in_txt="")
        {
                $ch_date = explode("-",$in_date);
                $cyear = substr($in_date,0,4);   
                $ch_date[0] = ((int)$cyear)-1911;
                $ch_date[1] = substr($in_date,4,2);
                $ch_date[2] = substr($in_date, -2);

                $date = '000000';    

                if($in_txt=="") {

                    $date = '00/00/00';
                    if($ch_date[0] > 0 ) $date = $ch_date[0]."/".$ch_date[1]."/".$ch_date[2];
                }
                else {

                    if ($ch_date[0] > 0 ) $date = $ch_date[0]."$in_txt".$ch_date[1]."$in_txt".$ch_date[2];
                }
                return $date;
        }     
        
        /**
        * 調撥單表頭        
        * @param type $firstcol --調撥單
        * @param type $currentsheet --Excel工作表
        * @param type $row --列數       
        */
        private function modelCol($firstcol,$currentsheet,$row) 
        {                
                $firstcol->ano      = $currentsheet->getCellByColumnAndRow(0, $row)->getValue(); //調撥單號
                $firstcol->adate    = $currentsheet->getCellByColumnAndRow(1, $row)->getValue(); //調撥日期
                $firstcol->adate    = $this->chageFormat($firstcol->adate);                      //調撥日期轉換西元
                $firstcol->make_emp = $currentsheet->getCellByColumnAndRow(2, $row)->getValue(); //製單人員                
                $firstcol->re_emp   = $currentsheet->getCellByColumnAndRow(3, $row)->getValue(); //覆核人員
                $firstcol->make_no  = $currentsheet->getCellByColumnAndRow(4, $row)->getValue(); //製單人員編號
                $firstcol->re_no    = $currentsheet->getCellByColumnAndRow(5, $row)->getValue(); //覆核人員編號
                $firstcol->posted   = $currentsheet->getCellByColumnAndRow(6, $row)->getValue(); //是否過帳

                return $firstcol;
        }
        
        /**
        * 調撥單-新增費用編號/合計        
        * @param type $firstcol --調撥單
        * @param type $model --調撥單表頭
        * @param type $currentsheet --Excel工作表
        * @param type $row --列數
        * @param type $feeAry --產品編號對應費用編號       
        */
        private function addFeeAndSum($firstcol,$model,$currentsheet,$row,$feeAry) 
        {                
                $firstcol->ano      = $model->ano; //調撥單號
                $firstcol->adate    = $model->adate; //調撥日期                
                $firstcol->make_emp = $model->make_emp; //製單人員
                $firstcol->re_emp   = $model->re_emp; //覆核人員
                $firstcol->make_no  = $model->make_no; //製單人員編號
                $firstcol->re_no    = $model->re_no; //覆核人員編號        
                $firstcol->posted   = $model->posted; //是否過帳

                $firstcol->pno      = $currentsheet->getCellByColumnAndRow(3, $row)->getValue(); //產品編號
                $firstcol->unit     = $currentsheet->getCellByColumnAndRow(4, $row)->getValue(); //計量單位
                $firstcol->ptalk    = $currentsheet->getCellByColumnAndRow(5, $row)->getValue(); //產品說明
                $firstcol->pclass   = $currentsheet->getCellByColumnAndRow(6, $row)->getValue(); //產品類別
                $firstcol->cname    = $currentsheet->getCellByColumnAndRow(7, $row)->getValue(); //類別名稱
                $firstcol->format   = $currentsheet->getCellByColumnAndRow(8, $row)->getValue(); //品名規格
                $firstcol->num      = $currentsheet->getCellByColumnAndRow(9, $row)->getValue(); //數量 
                $firstcol->iware    = $currentsheet->getCellByColumnAndRow(10, $row)->getValue(); //撥入倉庫
                $firstcol->iwname   = $currentsheet->getCellByColumnAndRow(11, $row)->getValue(); //撥入倉庫名稱
                $firstcol->oware    = $currentsheet->getCellByColumnAndRow(12, $row)->getValue(); //撥出倉庫
                $firstcol->owname   = $currentsheet->getCellByColumnAndRow(13, $row)->getValue(); //撥出倉庫名稱
                $firstcol->avgcost  = $currentsheet->getCellByColumnAndRow(14, $row)->getValue(); //平均成本
                $firstcol->memo     = $currentsheet->getCellByColumnAndRow(15, $row)->getValue(); //備註
                $firstcol->pass     = $currentsheet->getCellByColumnAndRow(16, $row)->getValue(); //是否過帳
                $firstcol->cost     = $currentsheet->getCellByColumnAndRow(17, $row)->getValue(); //成本                
                $firstcol->fno      = $feeAry[$firstcol->pno]; //費用編號        
                $firstcol->total    = $firstcol->num * $firstcol->cost; //總計

                return $firstcol;
        }
        
        /**
        * 調撥單-新增附加產品空瓶        
        * @param type $secondcol --附加產品 
        * @param type $firstcol --調撥單
        * @param type $prod --comprod產品資料庫資訊        
        * @param type $feeAry --產品編號對應費用編號       
        */
        private function additionProd($secondcol,$firstcol,$prod,$feeAry) 
        {                                
                $secondcol->ano      = $firstcol->ano; //調撥單號
                $secondcol->adate    = $firstcol->adate; //調撥日期                
                $secondcol->make_emp = $firstcol->make_emp; //製單人員
                $secondcol->re_emp   = $firstcol->re_emp; //覆核人員
                $secondcol->make_no  = $firstcol->make_no; //製單人員編號
                $secondcol->re_no    = $firstcol->re_no; //覆核人員編號        
                $secondcol->posted   = $firstcol->posted; //是否過帳

                $secondcol->pno      = $prod->pno; //產品編號
                $secondcol->unit     = $prod->unit; //計量單位
                $secondcol->ptalk    = $firstcol->ptalk; //產品說明
                $secondcol->pclass   = $prod->pclass; //產品類別
                $secondcol->cname    = $prod->classname; //類別名稱
                $secondcol->format   = $prod->spec; //品名規格
                $secondcol->num      = $firstcol->num; //數量
                $secondcol->iware    = $firstcol->iware; //撥入倉庫
                $secondcol->iwname   = $firstcol->iwname; //撥入倉庫名稱
                $secondcol->oware    = $firstcol->oware; //撥出倉庫
                $secondcol->owname   = $firstcol->owname; //撥出倉庫名稱
                $secondcol->avgcost  = $prod->purchase; //平均成本
                $secondcol->memo     = $firstcol->memo; //備註
                $secondcol->pass     = $firstcol->pass; //是否過帳
                $secondcol->cost     = $prod->purchase; //成本
                $secondcol->fno      = $feeAry[$firstcol->pno]; //費用編號        
                $secondcol->total    = round($secondcol->num * $secondcol->cost,0); //總計
//                CVarDumper::dump($secondcol->num * $secondcol->cost,10,true);
                return $secondcol;
        }
        
        /**
        * // 1. 判斷調撥單是否存在
           // 2. 若存在, 刪除
           // 3. 判斷費用單是否存在
           // 4. 若存在, 刪除
           // 5. 判斷廣告費是否存在
           // 6. 若存在, 清空         
         * @param type $ano --調撥編號 
         **/
        private function checkano($ano) 
        {           
            $ary = TbeAlloc::model()->findAllByAttributes(array('ano'=>$ano));

            if(count($ary)>0) {
                
                TbeAlloc::model()->deleteAllByAttributes(array('ano'=>$ano));
                
                $arydno = TbfFeeOld::model()->findAllByAttributes(array('dno'=>'O'.$ano));
                
                if(count($arydno)>0) {
                    
                    TbfFeeOld::model()->deleteAllByAttributes(array('dno'=>'O'.$ano));
                    
                    $aryadd = TbfLotDetail::model()->findAllByAttributes(array('dno'=>'O'.$ano));
                        
                    if(count($aryadd)>0) {                                          
                        
                        foreach ($aryadd as $rows) {
                        
                            $rows->dno = '';
                            $rows->save();                         
                        }                        
                    }                                        
                }
            }
            return $ary;            
        }                
}
