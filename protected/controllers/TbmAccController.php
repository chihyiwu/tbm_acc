<?php

class TbmAccController extends RController
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
		$model=new TbmAcc;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['TbmAcc']))
		{
			$model->attributes=$_POST['TbmAcc'];
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

		if(isset($_POST['TbmAcc']))
		{
			$model->attributes=$_POST['TbmAcc'];
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
		$dataProvider=new CActiveDataProvider('TbmAcc');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TbmAcc('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TbmAcc']))
			$model->attributes=$_GET['TbmAcc'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TbmAcc the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TbmAcc::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TbmAcc $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tbm-acc-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionSalarytrans() 
        {                                   
            //第一次匯款帳戶總額
            $sum_acc = 0;
            //第二次匯款帳戶總額
            $sum_memo = 0;
            //使用中的匯款帳戶
            $trans_acc_ary = array();
            //帳戶統一編號
            $trans_taxid = array();
            //匯款帳戶流水號
            $trans_id = array();
            //給付薪資員工
            $emp_item = array();                        
            //員工銀行帳戶
            $tbs_emp = array();           
            //員工匯款帳戶
            $emp_bank_acc = '';          
            //員工匯款帳戶姓名
            $emp_bank_name = '';
            //員工匯款帳戶身分證字號
            $emp_bank_idno = '';            
            //公用變數
            $param = array();
            //員工薪資總額
            $emp_value = 0;
            //帳戶平均額
            $emp_value_avg = 0;
            //員工薪資大於22800總額
            $emp_value_over22800 = 0;
            //員工薪資小於22800總額
            $emp_value_low22800 = 0;
            //第一次分配平均額
            $emp_value_first_avg = 0;
            //第二次發放11個帳戶的平均額
            $remain_avg = 0;
            //基本分配金額22,800
            $first_salary = '';
            //結算薪資的年月日
            $tmp = strtotime("-1 month", time());
            $qry_date = date('Ym',$tmp);
            //匯款薪資的年月日
            $qry_remit_date = date('Ymd');            
            //確認是否處理無帳戶員工
            $checkbox = 0;
            //匯款帳戶編號
            $tbm_acc_sbr = array();
            //儲存員工帳戶不存在
            $emp_bankacc_notexist = array();
            //第一次分配薪資帳戶編號
            $trans_acc = 0;            
            //員工帳戶
            $emp_acc = array();          
            //員工帳戶名稱
            $racive = array();
            //取得帳戶號碼
            $bankacc = $this->getTransAcc();
            //取得薪資帳戶暱稱
            $nickname = $this->getNick();
            //顯示帳戶資料畫面陣列
            $colAry = array();
            //顯示欄位資料陣列
            $col = array();
            //顯示標頭資料陣列
            $title = array();
            //匯款帳戶
            $qry_trans = "";
            //分配順序
            $qry_num = 0;
            
                       
            if(isset($_POST['qry_date'])) $qry_date = $_POST['qry_date'];
            
            if(isset($_POST['qry_remit_date'])) $qry_remit_date = $_POST['qry_remit_date'];
            
            if(isset($_POST['checkbox'])) $checkbox = $_POST['checkbox'];
            
            if(isset($_POST['qry_trans'])) $qry_trans = $_POST['qry_trans'];
            
            if(isset($_POST['qry_num'])) $qry_num = $_POST['qry_num'];
            
            //切割年月
            //$qry_date = substr($qry_date,0,6);                                                                               
            //由年月以及項目903, 取得需給付薪資的員工
            $emp_item = TbmEmpSalary::model()->findAllByAttributes(array('daymonth'=>$qry_date,'itemno'=>'903'));                      
            //取得正在使用中的匯款帳戶
            $trans_acc_ary = TbmAcc::model()->findAllByAttributes(array('opt1'=>1));
            
            //公用變數
            $param = TbmParam::model()->findByAttributes(array('param'=>'first_salary'));   
            //第一次分配額    
            if($param !=NULL) {
            
                $first_salary = $param->pvalue;
            }
            //使用中的匯款帳戶不為空                
            if($trans_acc_ary != '') {
                    
                foreach ($trans_acc_ary as $key => $lot) {
                    //帳戶不為空,取得資訊
                    if(($lot->sbr) !='') {
                        //key值對應匯款帳戶號碼
                        $tbm_acc_sbr[$key] = $lot->sbr;
                        //匯款帳戶號碼對應統一編號
                        $trans_taxid[$lot->sbr] = $lot->taxid;
                        //匯款帳戶號碼對應流水號
                        $trans_id[$lot->sbr] = $lot->id;
                    }
                }              
            }   
            //計算給付員工薪資
            for($i=0;$i<count($emp_item); $i++) {
                            
                if(isset($emp_item[$i]['value'])) {
                    //員工薪資總額
                    $emp_value = round($emp_item[$i]['value'] + $emp_value,0);
                }
                
                if($emp_item[$i]['value'] >=$first_salary) {
                    //員工薪資超過22800者,以22800計算總額
                    $emp_value_over22800 = $first_salary + $emp_value_over22800;
                }
                
                if($emp_item[$i]['value'] < $first_salary) {
                    //員工薪資小於22800者,以員工薪資計算總額
                    $emp_value_low22800 = round($emp_item[$i]['value'] + $emp_value_low22800,0);
                }                
            }
            
            //第一次分配平均額 = (超過22,800的薪資計算總額 + 小於22,800的薪資計算總額) / 使用中的匯款帳戶數 
            $emp_value_first_avg = ($emp_value_over22800 + $emp_value_low22800) / count($trans_acc_ary);
            
            //帳戶平均額 = 員工薪資總額 / 使用中的匯款帳戶
            if($trans_acc_ary !=NULL) {
            
                $emp_value_avg = round($emp_value / count($trans_acc_ary),0);
            }else {
                
                Yii::app()->user->setFlash('error'," 薪資帳戶搜尋結果為空！"); 
            }            
            
             //第二次發放11個帳戶的平均額 = 帳戶平均額 - 第一次分配平均額
            $remain_avg = $emp_value_avg - $emp_value_first_avg; 
            
            //員工銀行帳戶                      
            $tbs_emp = TbsEmp::model()->findAll();
             
            foreach ($tbs_emp as $lot) {
                
                if($lot->bankacc !='' && $lot->bankname !='' && $lot->bankidno !='') {
                    //員工編號對應員工匯款帳戶
                    $emp_bank_acc[$lot->empno] = $lot->bankacc;
                    //員工編號對應員工匯款帳戶姓名
                    $emp_bank_name[$lot->empno] = $lot->bankname;
                    //員工編號對應員工匯款帳戶身分證字號
                    $emp_bank_idno[$lot->empno] = $lot->bankidno;
                }                             
            }
          
            //是否勾選無帳戶(0 = 沒勾選,1 = 有勾選)
            if($checkbox==0) {
                //針對員工匯款帳戶不存在
                foreach ($emp_item as $key => $lot) {
                    
                    if(!isset($emp_bank_acc[$lot->empno])) {

                        array_push($emp_bankacc_notexist, $lot->empno.','.$lot->empname);                        
                      
                        unset($emp_item[$key]);                      
                    }                   
                }
                //判斷員工帳戶不存在的資料不為空且筆數大於0
                if($emp_bankacc_notexist != '' && count($emp_bankacc_notexist)>0) {
                
                    foreach ($emp_bankacc_notexist as $value) {
                        
                        Yii::app()->user->setFlash('error',  Yii::app()->user->getFlash('error').CVarDumper::dumpAsString($value).' 因查無員工薪資帳戶資料，故不分配處理！'.'<br>');
                    }                    
                }
                //員工名單重新排序
                sort($emp_item);                
            }else {
                //針對員工匯款帳戶不存在
                foreach ($emp_item as $key => $lot) {

                    if(!isset($emp_bank_acc[$lot->empno])) {

                        array_push($emp_bankacc_notexist, $lot->empno.','.$lot->empname);                                                                                     
                    }                   
                }
                //判斷員工帳戶不存在的資料不為空且筆數大於0
                if($emp_bankacc_notexist != '' && count($emp_bankacc_notexist)>0) {
                
                    foreach ($emp_bankacc_notexist as $value) {
                        
                        Yii::app()->user->setFlash('error',  Yii::app()->user->getFlash('error').CVarDumper::dumpAsString($value).' 查無員工薪資帳戶資料！請在文字檔查證處理'.'<br>');
                    }                    
                }
                $checkbox++;
            }
          
            //匯入管理部+營業部薪資
            if (isset($_POST['import'])) {
                
                if(($_POST['qry_date']) !=NULL) {
                    
                    $check_manage = FALSE;
                    //先匯管理部，再匯營業部           
                    //管理部薪資匯入
                    $sql_manage_num = "SELECT count(*) as num FROM `tbm_emp_m_item` WHERE `daymonth`='$qry_date' ";
                    //執行SQL語法並返回結果的第一行
                    $count_manage = Yii::app()->db->createCommand($sql_manage_num)->queryRow();

                    $total_manage = 0;

                    if(isset($count_manage)) {
                        //取得管理部薪資數量
                        $total_manage = $count_manage['num'];
                    }
                        //判斷管理部薪資筆數是否大於1
                        if($total_manage < 1) {

                            Yii::app()->user->setflash('error',"匯入資料失敗！薪資年月 $qry_date 查無管理部員工薪資");
                        }else {
                            //確認管理部薪資是否匯入成功
                            $check_manage = TRUE;

                            $model = TbmEmpMItem::model();
                            //開始資料庫交易。
                            $transaction = $model->dbConnection->beginTransaction();
                            $valid = TRUE;
                            $errorMsg = '';

                            $emp_m_item_model = array();
                            //限制傳回的資料筆數為10,000
                            $limit = 10000;
                            //略過筆數
                            $offet = 0;
                            //try-catch 例外處理 (exception handling) 為控制程式發生錯誤後的機制
                            //catch 捕捉例外物件
                            //throw 丟出例外物件
                            try {
                                // 開始匯入前，先刪除年月之資料
                                $data = TbmEmpSalary::model()->deleteAllByAttributes(array('daymonth'=>$qry_date));

                                while($total_manage >0) {
                                    //搜尋管理部薪資
                                    $emp_m_item_model = TbmEmpMItem::model()->findAllByAttributes(array('daymonth'=>$qry_date),array('limit'=>$limit,'offset'=>$offet)); 
                                    sort($emp_m_item_model);
                                    //新增資料至員工薪資資料庫
                                    $sqlStart = "INSERT INTO `tbm_emp_salary` (`id`, `daymonth`, `empno`, `empname`, `itemno`, `value`, `eachmonth`, `memo`, 
                                                 `opt1`, `opt2`, `opt3`, `cemp`, `uemp`, `ctime`, `utime`, `ip`) VALUES ";

                                    $sql = $sqlStart;
                                    //宣告記錄迴圈次數
                                    $i = 0;
                                    //迴圈判斷每筆寫入
                                    foreach ($emp_m_item_model as $key => $items) {

                                        if($valid) {
                                            //DEFAULT 設定欄位的預設值
                                            $sql = $sql."(DEFAULT, '$items->daymonth', '$items->empno', '$items->empname', '$items->itemno', ";
                                            $sql = $sql."'$items->value', '$items->eachmonth', '$items->memo', '$items->opt1', '$items->opt2', ";
                                            $sql = $sql."'$items->opt3', '$items->cemp', '$items->uemp', '$items->ctime', '$items->utime', '$items->ip'),";

                                        }else{

                                            $valid =FALSE;
                                            break;
                                        }
                                        //判斷迴圈次數是否大於1千筆資料,如果超過則進入
                                        if($i++ > 1000) {
                                            //替换字串，尾端字串,改為;
                                            $sql = substr_replace($sql, ";", -1);
                                            // CDbCommand對象有兩個方法execute()用于非查詢SQL執行，而query()，通俗的講就是用于SELECT查詢
                                            // execute()返回的是INSERT, UPDATE and DELETE操作受影響的記錄行數
                                            // query()返回一個CDbDataReader對象，使用CDbDataReader對象可以遍曆匹配結果集中的所有記錄。
                                            $result = Yii::app()->db->createCommand($sql)->execute();
                                            //usleep 延遲是以微秒為單位，1秒 = 1,000,000 微秒
                                            usleep(500);   
                                            $sql = $sqlStart;
                                            $i = 0;
                                        }
                                    }
                                    //替换字串，尾端字串,改為;
                                    $sql = substr_replace($sql, ";", -1);
                                    $result = Yii::app()->db->createCommand($sql)->execute();
                                    //刪除變數
                                    unset($emp_m_item_model);
                                    $total_manage = $total_manage - $limit;
                                    $offet = $offet + $limit; 
                                }

                                if($valid && $result>0){
                                    $check_manage = TRUE;
                                    $transaction->commit();    
                                }
                                else{
                                    $check_manage = FALSE;
                                    $errorMsg = "$items->empname $items->itemno 管理部薪資匯入失敗 !<br>";
                                    foreach ($items->getErrors() as $e) $errorMsg = $errorMsg. $e[0] .'<br>';
                                    $transaction->rollback();
                                }
                            }catch(Exception $e) {

                                $transaction->rollback();    
                                throw $e;
                            }                            
                        }
                    //管理部薪資匯入為True,則執行
                    //營業部薪資匯入
                    if($check_manage) {

                        $check_emp = FALSE;

                        $sql_num = "SELECT count(*) as num FROM `tbm_emp_item` WHERE `daymonth`='$qry_date' ";
                        //執行SQL語法並返回結果的第一行
                        $count = Yii::app()->db->createCommand($sql_num)->queryRow();

                        $total = 0;

                        if(isset($count)) {

                            $total = $count['num'];
                        }

                            if($total < 1) {

                                Yii::app()->user->setflash('error',"匯入資料失敗！薪資年月 $qry_date 查無營業部員工薪資");
                            }else {
                                //確認營業部薪資是否匯入成功
                                $check_emp = TRUE;

                                $model = TbmEmpItem::model();
                                //開始資料庫交易。
                                $transaction = $model->dbConnection->beginTransaction();
                                $valid = TRUE;
                                $errorMsg = '';

                                $emp_item_model = array();
                                //限制傳回的資料筆數為10,000
                                $limit = 10000;
                                //略過筆數
                                $offet = 0;
                                //try-catch 例外處理 (exception handling) 為控制程式發生錯誤後的機制
                                //catch 捕捉例外物件
                                //throw 丟出例外物件
                                try {

                                    while($total >0) {

                                        $emp_item_model = TbmEmpItem::model()->findAllByAttributes(array('daymonth'=>$qry_date),array('limit'=>$limit,'offset'=>$offet)); 
                                        sort($emp_item_model);
                                        $sqlStart = "INSERT INTO `tbm_emp_salary` (`id`, `daymonth`, `empno`, `empname`, `itemno`, `value`, `eachmonth`, `memo`, 
                                                     `opt1`, `opt2`, `opt3`, `cemp`, `uemp`, `ctime`, `utime`, `ip`) VALUES ";

                                        $sql = $sqlStart;
                                        $i = 0;

                                        foreach ($emp_item_model as $key => $items) {

                                            if($valid){
                                                //DEFAULT 設定欄位的預設值
                                                $sql = $sql."(DEFAULT, '$items->daymonth', '$items->empno', '$items->empname', '$items->itemno', ";
                                                $sql = $sql."'$items->value', '$items->eachmonth', '$items->memo', '$items->opt1', '$items->opt2', ";
                                                $sql = $sql."'$items->opt3', '$items->cemp', '$items->uemp', '$items->ctime', '$items->utime', '$items->ip'),";

                                            }else{

                                                $valid =FALSE;
                                                break;
                                            }

                                            if($i++ > 1000) {
                                                //替换字串的子串
                                                $sql = substr_replace($sql, ";", -1);
                                                // CDbCommand對象有兩個方法execute()用于非查詢SQL執行，而query()，通俗的講就是用于SELECT查詢
                                                // execute()返回的是INSERT, UPDATE and DELETE操作受影響的記錄行數
                                                // query()返回一個CDbDataReader對象，使用CDbDataReader對象可以遍曆匹配結果集中的所有記錄。
                                                $result = Yii::app()->db->createCommand($sql)->execute();
                                                //usleep 延遲是以微秒為單位，1秒 = 1,000,000 微秒
                                                usleep(500);   
                                                $sql = $sqlStart;
                                                $i = 0;
                                            }
                                        }
                                        //最後一將最後一個,號改成;號
                                        $sql = substr_replace($sql, ";", -1);
                                        $result = Yii::app()->db->createCommand($sql)->execute();
                                        //刪除變數
                                        unset($emp_item_model);
                                        $total = $total - $limit;
                                        $offet = $offet + $limit; 
                                    }

                                    if($valid && $result>0){
                                        $check_emp = TRUE;
                                        $transaction->commit();    
                                    }
                                    else{
                                        $check_emp = FALSE;
                                        $errorMsg = "$items->empname $items->itemno 營業部薪資匯入失敗 !<br>";
                                        foreach ($items->getErrors() as $e) $errorMsg = $errorMsg. $e[0] .'<br>';
                                        $transaction->rollback();
                                    }
                                }catch(Exception $e) {

                                    $transaction->rollback();    
                                    throw $e;
                                }                            
                            }   
                    }
                    //判斷營業部薪資是否匯入成功 
                    if($check_emp) {

                            Yii::app()->user->setFlash('success', "營業部薪資匯入成功！");
                    }else{                  
                            Yii::app()->user->setFlash('error', "匯入薪資年月 $qry_date 營業部薪資失敗！");
                    }
                }else
                    Yii::app()->user->setflash('error', '年月不得為空值。');
            }
            
            //按下匯出文件檔
            if(isset($_POST['export'])) 
            {   
                if(($_POST['qry_remit_date']) !=NULL) {
                    
                    //判斷分配資訊是否存在
                    //已存在就先刪除,後新增
                    $this->checkdate($qry_date);
                    //員工帳戶身分證
                    $bank_idno = array();
                    //員工帳戶姓名
                    $bank_name = array();
                    //薪資金額
                    $amt = '';               
                    //姓名的長度
                    $name_length = '';
                    //總金額
                    $amt_total = 0;
                    //總筆數
                    $emp_count = 0;
                    //總金額的長度
                    $amt_total_length = '';
                    //總筆數的長度
                    $emp_count_length = '';                

                    //第一次分配
                    foreach ($emp_item as $key => $row) {

                        //第一次匯款帳戶總額 > 第一次分配平均額 ，則帳戶往下+1，統計帳戶歸零
                        if($sum_acc > $emp_value_first_avg) {

                            $trans_acc++;

                            $sum_acc = 0;
                        }
                        //帳戶號碼
                        $trans = $bankacc[$trans_acc];
                        //帳戶暱稱
                        $nick  = $nickname[$trans_acc];
                        //員工編號
                        $empno = $row->empno;
                        //員工姓名
                        $empname = $row->empname;
                        //員工匯款帳戶                   
                        $emp_acc = $emp_bank_acc[$empno];                                
                        //員工匯款帳戶姓名
                        $racive = $emp_bank_name[$empno];
                        //員工薪資
                        $salary_value = $row->value;
                        //匯款日
                        $date = $qry_date;
                        //次數
                        $num = 1;
                        //剩餘給付薪資
                        $salary_remain = 0;

                        // 判斷員工給付薪資是否超過22,800，超過則先給付22800
                        // 剩餘薪資，放入第二次分配處理 
                        if($salary_value >= $first_salary) {
                            //剩餘給付薪資 = 給付薪資 - 22,800
                            $salary_remain = $salary_value - $first_salary;
                            $salary_value = $first_salary;
                        }else {
                            //員工薪資不滿或等於22,800，放入第二次分配處理    
                            $salary_remain = 0;
                        }
                        //薪資作業資料庫 
                        $model = new TbmAccTrans;          

                        if($salary_value == $first_salary) {                  

                            $model = $this->modelcol($model, $trans_acc, $trans, $nick, $empno, $empname, $emp_acc,$racive,$salary_value,$date,$num);                                

                            $model->save();                

                            //第一次分配帳戶號碼 
                            $row->opt2 = $trans_acc; 
                            //剩餘薪資
                            $row->memo = $salary_remain;                
                            //第一次匯款帳戶總額 = 員工薪資累加                               
                            $sum_acc = $salary_value + $sum_acc;              
                            //將資料回存Tbm_emp_item
                            $row->save();                     
                        }               
                    }

                    //第一次分配小於22800的處理
                    foreach ($emp_item as $key => $row) {

                        //第一次匯款帳戶總額 > 22,800平均分配帳戶的平均額，則帳戶往下+1 
                        if($sum_acc > $emp_value_first_avg) {

                            $trans_acc++;                  

                            $sum_acc = 0;
                        }
                        //帳戶號碼
                        $trans = $bankacc[$trans_acc];
                        //帳戶暱稱
                        $nick  = $nickname[$trans_acc];
                        //員工編號
                        $empno = $row->empno;
                        //員工姓名
                        $empname = $row->empname;
                        //員工匯款帳戶                   
                        $emp_acc = $emp_bank_acc[$empno];                                
                        //員工匯款帳戶姓名
                        $racive = $emp_bank_name[$empno];
                        //員工薪資
                        $salary_value = $row->value;
                        //匯款日
                        $date = $qry_date;
                        //次數
                        $num = 1;
                        //剩餘給付薪資
                        $salary_remain = 0;

                        //薪資作業資料庫 
                        $model = new TbmAccTrans;          

                        if($salary_value < $first_salary) {                  

                            $model = $this->modelcol($model, $trans_acc, $trans, $nick, $empno, $empname, $emp_acc,$racive,$salary_value,$date,$num);                                

                            $model->save();                

                            //第一次分配帳戶號碼 
                            $row->opt2 = $trans_acc; 
                            //剩餘薪資
                            $row->memo = $salary_remain;                
                            //第一次匯款帳戶總額                                
                            $sum_acc = $salary_value + $sum_acc;              
                            //將資料回存Tbm_emp_item
                            $row->save();                     
                        }               
                    }           

                    //第二次分配帳戶號碼，使用中的匯款帳戶數-1(0~帳戶數-1)
                    $sec_trans_acc = count($trans_acc_ary)-1;
                    //記錄循環次數    
                    $key = 0;
                    //計算第二次分配帳戶總額&初始化
                    $acc_ary_sum = array();

                    for ($i = 0; $i < count($trans_acc_ary); $i++) {

                        $acc_ary_sum[$i] = 0;
                    }

                    //第二次分配
                    foreach ($emp_item as $key => $row) {                        
                        //當員工剩餘薪資大於0，進行第二次分配，且第二次分配帳戶不為負號
                        if($row->memo >0) {

                            //第一次分配帳戶 等於第二次分配帳戶 或者 第二次匯款帳戶總額 大於 第二次發放平均額
                            if($row->opt2 == $sec_trans_acc OR $sum_memo > $remain_avg) { 
                                //帳戶號碼對應帳戶分配總額
                                $acc_ary_sum[$sec_trans_acc] = $sum_memo;                                                           

                                $sec_trans_acc--;

                                $sum_memo = 0;
                            }
                            //第二次分配帳戶編號小於0的時候，從紀錄第二次分配帳戶總額找出小於第二次發放平均額帳戶與帳戶號碼
                            if($sec_trans_acc < 0 ){

                                foreach ($acc_ary_sum as $key => $sum) {

                                    if($sum < $remain_avg) {

                                        $sum_memo = $sum;

                                        $sec_trans_acc = $key;                                                                
                                    }
                                }                        
                            }     
                            //帳戶號碼
                            $trans = $bankacc[$sec_trans_acc];
                            //帳戶暱稱
                            $nick  = $nickname[$sec_trans_acc];
                            //員工編號
                            $empno = $row->empno;
                            //員工姓名
                            $empname = $row->empname;
                            //員工匯款帳戶
                            $emp_acc = $emp_bank_acc[$empno];                                             
                            //員工匯款帳戶姓名
                            $racive = $emp_bank_name[$empno];
                            //匯款日
                            $date = $qry_date;
                            //次數
                            $num = 2;
                            //員工剩餘薪資
                            $salary_value = $row->memo; 
                            //第二次匯款帳戶總額
                            $sum_memo = $salary_value + $sum_memo;                        

                            //薪資作業資料庫
                            $model = new TbmAccTrans;

                            $model = $this->modelcol2($model,$sec_trans_acc, $trans, $nick, $empno, $empname, $emp_acc,$racive, $salary_value,$date,$num);

                            $model->save();                                  
                        } 
                    }

                    //當第二次匯款帳戶已經滿額，無法塞入，需強制塞入            
                    for ($i = $key ; $i < count($emp_item)-1; $i++) {                

                        if($emp_item[$i]['memo'] >0) {

                            if($sec_trans_acc >0) {

                                if($row->opt2 == $sec_trans_acc OR ($sum_memo > $remain_avg)) { 

                                    $sec_trans_acc--;

                                    $sum_memo = 0;
                                }

                                //例外分配帳戶編號
                                $sec_trans_acc--;    
                                //帳戶號碼
                                $trans = $bankacc[$sec_trans_acc];
                                //帳戶暱稱
                                $nick  = $nickname[$sec_trans_acc];
                                //員工編號
                                $empno = $emp_item[$i]['empno'];
                                //員工姓名
                                $empname = $emp_item[$i]['empname'];
                                //員工匯款帳戶
                                 $emp_acc = $emp_bank_acc[$empno];                                                 
                                //員工匯款帳戶姓名
                                $racive = $emp_bank_name[$empno];
                                //匯款日
                                $date = $qry_date;
                                //次數
                                $num = 2;
                                //員工薪資扣除基本分配額後，剩餘薪資
                                $salary_value = $emp_item[$i]['memo'];

                                //薪資作業資料庫
                                $model = new TbmAccTrans;

                                $model = $this->modelcol2($model, $sec_trans_acc, $trans, $nick, $empno, $empname, $emp_acc,$racive, $salary_value,$date,$num);

                                $model->save();

                            }else{    
                                $sec_trans_acc = count($trans_acc_ary);
                            }
                        }
                    }

                    //查詢tbm_acc_trans資料庫
                    $sql = " SELECT * FROM tbm_acc_trans "
                         . " WHERE date ='$qry_date' and type_trans = '1'"                      
                         . " ORDER BY trans_acc_id,num,id ";                               
                    //將資料庫資料建立陣列
                    $tbm_acc_trans_Ary = Yii::app()->db->createCommand($sql)->queryAll();

                    //第一行最後補空白格
                    $strSpace_FirstRow_Final = '';
                    //儲存第一行補最後79個空白格
                    for($i=1;$i<=79;$i++) {

                        $strSpace_FirstRow_Final = $strSpace_FirstRow_Final.' ';
                    }                
                    //第二行其他後埔空白格
                    $strSpace_SecondRow_Other = '';
                    //儲存第二行補其他後16個空白格
                    for($i=1; $i<=16; $i++) {

                        $strSpace_SecondRow_Other = $strSpace_SecondRow_Other.' ';
                    }
                    //第二行Y之後補齊空白格
                    $strSpace_SecondRow_FinalbyY = '';
                    //儲存第二行Y補之後空白格到130格
                    for($i=1; $i<=23; $i++) {

                        $strSpace_SecondRow_FinalbyY = $strSpace_SecondRow_FinalbyY.' ';
                    }
                    //第三行最後補空白格
                    $strSpace_ThirdRow_Final = '';
                    //儲存第三行總筆數之後補滿空白格到130格
                    for($i=1; $i<=78; $i++) {

                        $strSpace_ThirdRow_Final = $strSpace_ThirdRow_Final.' ';
                    }
                    //連結路徑
                    $webroot = Yii::getPathOfAlias('webroot');
                    //帳戶資料夾路徑
                    $path_dir = $webroot.'/' . "protected" . '/' . "tmp" . '/'."Salary";
                    //如果資料夾不存在就建立
                    if(!file_exists($path_dir)) {

                        mkdir($path_dir,0777);
                    }

                    //判斷資料庫不為空 且 筆數 >0 輸出資訊否則回傳失敗訊息
                    if(count($tbm_acc_trans_Ary) >0) {
                        //for loop 銀行帳戶
                        foreach($tbm_acc_sbr as $trans_acc) {                                                         
                            //for loop 兩次分配                                                                          
                            for($num = 1; $num<=2; $num++) {

                                //資料夾路徑                                                               
                                $path = $path_dir.'/'.$trans_id[$trans_acc];                                   
                                //匯款帳戶對應統一編號
                                $taxid = $trans_taxid[$trans_acc];                                   
                                //文字檔路徑
                                $txtfile = $path.'/'.'PCCUT'.$num.'.txt';
                                //開啟檔案，要是沒有檔案將建立一份
                                $open = fopen($txtfile,'w+');                           

                                //第一行寫入
                                //【區別碼#長度1#一律擺"1"】,【企業編號（1）#長度3#本行給予的企業編號"000"】,【企業編號（2）#長度1#一律擺"F"】,【分行代號#長度4#本行分行之代號"8157"】,【日期#長度8#yyyymmdd】
                                //【存提代號#長度1#2表示存款】,【摘要#長度3#097表示存摺摘要欄不印】,【磁片來源#長度5#CUST前四碼為代號,後一碼補空白】,【性質別#長度1#一律擺1】
                                //【公司統一編號#長度10#統一編號8碼+補2格空白】,【公司帳號#長度14#彰銀之帳號14位】,【空白欄#長度79#補滿空白，使每行長度均為130】
                                fwrite($open,'1000F8157'.$qry_remit_date.'2097CUST 1'.$taxid.'  '.$trans_acc.$strSpace_FirstRow_Final."\r\n"); //寫入  

                                //儲存相同薪資帳戶與循環次數的陣列資料
                                $result = array();
                                //當數字為1時進入分配
                                if($num == 1) {                                       

                                    $result = TbmAccTrans::model()->findAllByAttributes(array('type_trans'=>1,'trans_acc'=>$trans_acc,'date'=>$qry_date,'num'=>1));                                                                                                
                                    //排序
                                    sort($result);

                                    //資料不為空且筆數大於0
                                    if($result !='' && count($result)>0) {
                                        //資料for loop
                                        for($i=0; $i<count($result); $i++) {
                                            //第二行補金額前的0
                                            $strSpace_SecondRow_amt = '';
                                            //薪資金額
                                            $amt = $result[$i]['amt'];
                                            //薪資金額前面補0，str_pad($array,總長度,'補齊的字串',由左邊補)
                                            $strSpace_SecondRow_amt = str_pad($amt,12,'0',STR_PAD_LEFT);
                                            //員工匯款帳戶身分證字號
                                            $bank_idno = $emp_bank_idno[$result[$i]['empno']];
                                            //員工匯款帳戶姓名
                                            $bank_name = $emp_bank_name[$result[$i]['empno']];
                                            //將員工帳戶姓名轉編碼
                                            $bank_name = iconv("UTF-8","big5//TRANSLIT",$bank_name);
                                            //其他
                                            $other = "其他";
                                            //將其他轉編碼
                                            $other = iconv("UTF-8", "big5", $other);                                        
                                            //總金額
                                            $amt_total = $amt_total + $result[$i]['amt'];
                                            //總筆數
                                            $emp_count = count($result);                                       
                                            //第二行補姓名後的空白格
                                            $strSpace_SecondRow_name = '';                                            
                                            //計算員工帳戶姓名長度(1個字 = 字串長度2)
                                            $name_length = strlen($bank_name);
                                            ///姓名補空白格                                       
                                            $strSpace_SecondRow_name = str_pad($bank_name,20,' ',STR_PAD_RIGHT);

                                            //第二行寫入
                                            //【區別碼#長度1#一律擺"2"】,【企業編號（1）#長度3#本行給予的企業編號"000"】,【企業編號（2）#長度1#一律擺"F"】,【分行代號#長度4#本行分行之代號"8157"】,【日期#長度8#yyyymmdd】
                                            //【存提代號#長度1#2表示存款】,【摘要#長度3#097表示存摺摘要欄不印】,【空白欄#長度5#補滿5格空白】,【銀行帳號#長度14#彰銀之帳號14位數】,【金額#長度14#12位與小數2位，共14位】
                                            //【狀況代號#長度2#一律擺"99"】,【交易註記（1）#長度10#客戶自行使用,填寫"其他"佔4格空白,需補滿6格空白】,【交易註記（2）#長度10#客戶自行使用,不然補滿10格空白】
                                            //【身份證字號#長度10#開戶登記身分證字號】,【專用資料區#長度20#供客戶自行使用,填入開戶登記姓名】,【身份證檢核記號#長度1#需檢核身份證，則填入Y,不需檢查為N】,【幣別#長度2#台幣為空白，美金為01】,【空白欄#長度21#滿空白，使每行長度均為130】
                                            fwrite($open,'2000F8157'.$qry_remit_date.'2097     '.$result[$i]['emp_acc'].$strSpace_SecondRow_amt.'00'.'99'.$other.$strSpace_SecondRow_Other.$bank_idno.$strSpace_SecondRow_name.'Y'.$strSpace_SecondRow_FinalbyY."\r\n");                                    
                                        }
                                    }
                                    //總金額前面補0，str_pad($array,總長度,'補齊的字串',由左邊補)
                                    $amt_total_length = str_pad($amt_total,14,'0',STR_PAD_LEFT);
                                    //總筆數前面補0，str_pad($array,總長度,'補齊的字串',由左邊補)
                                    $emp_count_length = str_pad($emp_count,10,'0',STR_PAD_LEFT);

                                    //第三行寫入
                                    //【區別碼#長度1#一律擺"3"】,【企業編號（1）#長度3#本行給予的企業編號"000"】,【企業編號（2）#長度1#一律擺"F"】,【分行代號#長度4#本行分行之代號"8157"】,【日期#長度8#yyyymmdd】
                                    //【存提代號#長度1#2表示存款】,【摘要#長度3#097表示存摺摘要欄不印】,【空白欄#長度5#補滿5格空白】,【總金額#長度16#14位與小數2位】,【總筆數#長度10#明細之總筆數】
                                    //【未成交總金額#長度16#空白（此欄回饋時才用）】,【未成交總筆數#長度10#空白（此欄回饋時才用）】,【空白欄#長度52#需補滿空白，使每行長度均為130】
                                    fwrite($open,'3000F8157'.$qry_remit_date.'2097     '.$amt_total_length.'00'.$emp_count_length.$strSpace_ThirdRow_Final."\r\n");
                                    //關閉檔案
                                    fclose($open);

                                }else {

                                    $result = TbmAccTrans::model()->findAllByAttributes(array('type_trans'=>1,'trans_acc'=>$trans_acc,'date'=>$qry_date,'num'=>2));                               
                                    //排序
                                    sort($result);

                                    //資料不為空且筆數大於0
                                    if($result != '' && count($result)>0) {
                                        //資料for loop
                                        for($i=0; $i<count($result); $i++) {
                                            //第二行補金額前的0
                                            $strSpace_SecondRow_amt = '';
                                            //薪資金額
                                            $amt = $result[$i]['amt'];
                                            //薪資金額前面補0，str_pad($array,總長度,'補齊的字串',由左邊補)
                                            $strSpace_SecondRow_amt = str_pad($amt,12,'0',STR_PAD_LEFT);
                                            //員工匯款帳戶身分證字號
                                            $bank_idno = $emp_bank_idno[$result[$i]['empno']];
                                            //員工匯款帳戶姓名
                                            $bank_name = $emp_bank_name[$result[$i]['empno']];                                   
                                            //將員工帳戶姓名轉編碼
                                            $bank_name = iconv("UTF-8","big5//TRANSLIT",$bank_name);
                                            //其他
                                            $other = "其他";
                                            //將其他轉編碼
                                            $other = iconv("UTF-8", "big5", $other);
                                            //總金額
                                            $amt_total = $amt_total + $result[$i]['amt'];
                                            //總筆數
                                            $emp_count = count($result);
                                            //第二行補姓名後的空白格
                                            $strSpace_SecondRow_name = '';                                            
                                            //計算員工帳戶姓名長度(1個字 = 字串長度2)
                                            $name_length = strlen($bank_name);
                                            ///姓名補空白格                                   
                                            $strSpace_SecondRow_name = str_pad($bank_name,20,' ',STR_PAD_RIGHT);

                                            //第二行寫入
                                            //【區別碼#長度1#一律擺"2"】,【企業編號（1）#長度3#本行給予的企業編號"000"】,【企業編號（2）#長度1#一律擺"F"】,【分行代號#長度4#本行分行之代號"8157"】,【日期#長度8#yyyymmdd】
                                            //【存提代號#長度1#2表示存款】,【摘要#長度3#097表示存摺摘要欄不印】,【空白欄#長度5#補滿5格空白】,【銀行帳號#長度14#彰銀之帳號14位數】,【金額#長度14#12位與小數2位，共14位】
                                            //【狀況代號#長度2#一律擺"99"】,【交易註記（1）#長度10#客戶自行使用,填寫"其他"佔4格空白,需補滿6格空白】,【交易註記（2）#長度10#客戶自行使用,不然補滿10格空白】
                                            //【身份證字號#長度10#開戶登記身分證字號】,【專用資料區#長度20#供客戶自行使用,填入開戶登記姓名】,【身份證檢核記號#長度1#需檢核身份證，則填入Y,不需檢查為N】,【幣別#長度2#台幣為空白，美金為01】,【空白欄#長度21#滿空白，使每行長度均為130】
                                            fwrite($open,'2000F8157'.$qry_remit_date.'2097     '.$result[$i]['emp_acc'].$strSpace_SecondRow_amt.'00'.'99'.$other.$strSpace_SecondRow_Other.$bank_idno.$strSpace_SecondRow_name.'Y'.$strSpace_SecondRow_FinalbyY."\r\n");                                    
                                        }
                                    }
                                    //總金額前面補0，str_pad($array,總長度,'補齊的字串',由左邊補)
                                    $amt_total_length = str_pad($amt_total,14,'0',STR_PAD_LEFT);
                                    //總筆數前面補0，str_pad($array,總長度,'補齊的字串',由左邊補)
                                    $emp_count_length = str_pad($emp_count,10,'0',STR_PAD_LEFT);

                                    //第三行寫入
                                    //【區別碼#長度1#一律擺"3"】,【企業編號（1）#長度3#本行給予的企業編號"000"】,【企業編號（2）#長度1#一律擺"F"】,【分行代號#長度4#本行分行之代號"8157"】,【日期#長度8#yyyymmdd】
                                    //【存提代號#長度1#2表示存款】,【摘要#長度3#097表示存摺摘要欄不印】,【空白欄#長度5#補滿5格空白】,【總金額#長度16#14位與小數2位】,【總筆數#長度10#明細之總筆數】
                                    //【未成交總金額#長度16#空白（此欄回饋時才用）】,【未成交總筆數#長度10#空白（此欄回饋時才用）】,【空白欄#長度52#需補滿空白，使每行長度均為130】
                                    fwrite($open,'3000F8157'.$qry_remit_date.'2097     '.$amt_total_length.'00'.$emp_count_length.$strSpace_ThirdRow_Final."\r\n");
                                    //關閉檔案
                                    fclose($open);                                    
                                }
                                //總金額計算歸零
                                $amt_total = 0;
                            }                            
                        }
                        Yii::app()->user->setFlash('success',"產製成功！請自行下載");
                    }else
                        Yii::app()->user->setFlash('error',"產製失敗，以日期 $qry_date 查無資料！");
                }else                 
                    Yii::app()->user->setflash('error', '匯款日不得為空值。');
            }                                 
      
            //查詢帳戶明細
            if(isset($_POST['qrytrans'])) 
            {                
                if(($_POST['qry_date']) !=NULL) {
                    
                    $sql = " SELECT * FROM tbm_acc_trans "
                           ." WHERE (date ='$qry_date' and type_trans='1') and (trans_acc ='$qry_trans' and num = '$qry_num') "                      
                           ." ORDER BY id";                 
                    //將資料庫資料建立陣列查詢                                       
                    $colAry = Yii::app()->db->createCommand($sql)->queryAll();                                     
                    //匯款帳戶標題欄位
                    $col    = $this->gettranscol();
                    //匯款帳戶標題中文
                    $title  = $this->gettranstitle();                                                                                                  
                    //判斷$colAry 不為空 且 筆數 >0 輸出成功資訊否則回傳失敗訊息
                    if($colAry !=null && count($colAry)>0) {                               

                        Yii::app()->user->setFlash('success',"查詢成功！共計 ".count($colAry)."筆資料！");
                    }else                                                                 
                        Yii::app()->user->setFlash('error',"查詢失敗，以日期 $qry_date 查無資料！");
                }else
                    Yii::app()->user->setflash('error', '年月不得為空值。');
            }
            
            //查詢帳戶分配
            if(isset($_POST['qrytrans_alloc'])) 
            {   
                //日期不得為空值
                if(($_POST['qry_date']) !=NULL) {
                
                    //第一次分配次數總額
                    $first_count_total = '';
                    //第一次分配金額總額
                    $first_sum_total = '';
                    //第二次分配次數總額
                    $second_count_total = '';
                    //第二次分配金額總額
                    $second_sum_total = '';
                    //總分配金額總額
                    $trans_sum_total = '';

                    $sql1 = " SELECT a.`id`,a.`sender`, a.`sbr`, a.`nick`, COUNT( b.`amt` ) as first_count , SUM( b.`amt` ) as first_sum FROM `tbm_acc` AS a,  `tbm_acc_trans` AS b "
                          ." WHERE `date` ='$qry_date' AND `type_trans` = '1' AND b.`num` =  '1' AND a.`nick` = b.`nick` "                      
                          ." GROUP BY a.`id`, b.`trans_acc` ASC ";                                                                                                     

                    $sql2 = " SELECT a.`id` , a.`sender`, b.`trans_acc` , b.`nick` , COUNT( b.`amt` ) AS second_count, SUM( b.`amt` ) AS second_sum FROM  `tbm_acc` AS a,  `tbm_acc_trans` AS b "
                           ." WHERE b.`date` = '$qry_date' AND b.`type_trans` =  '1' AND b.`num` =  '2' AND a.`nick` = b.`nick` GROUP BY b.`trans_acc` ORDER BY `id`";

                    $sql3 = " SELECT SUM(amt) as trans_sum FROM `tbm_acc_trans` "
                          ." WHERE date ='$qry_date' AND type_trans = '1' "                      
                          ." GROUP BY trans_acc ORDER BY `trans_acc_id` ";

                    //將第一次分配資料建立陣列查詢                                       
                    $colAry = Yii::app()->db->createCommand($sql1)->queryAll();
                    
                    //將第二次分配資料建立陣列查詢
                    $ary = Yii::app()->db->createCommand($sql2)->queryAll();                   

                    //將總分配資料建立陣列查詢
                    $ary_sum = Yii::app()->db->createCommand($sql3)->queryAll();
                    //將第二次分配欄位塞進$colAry
                    for($i=0; $i<count($colAry); $i++) {

                        for($j=0; $j<count($ary); $j++) {
                            $colAry[$j]['id'] = $ary[$j]['id'];
                            $colAry[$j]['sender'] = $ary[$j]['sender'];
                            $colAry[$j]['sbr'] = $ary[$j]['trans_acc'];
                            $colAry[$j]['nick'] = $ary[$j]['nick'];
                            $colAry[$j]['second_count'] = $ary[$j]['second_count'];
                            $colAry[$j]['second_sum'] = $ary[$j]['second_sum'];
                        }                    
                    }
                    //將總分配欄位塞進$colAry
                    for($i=0; $i<count($colAry); $i++) {

                        for($k=0; $k<count($ary_sum); $k++) {

                            $colAry[$k]['trans_sum'] = $ary_sum[$k]['trans_sum'];
                        }    
                    }
                    //計算各分配數總額
                    for($i=0; $i<count($colAry); $i++) {
                        if(isset($colAry[$i]['first_count']) && $colAry[$i]['first_count'] !='') {
                            $first_count_total = $colAry[$i]['first_count'] + $first_count_total;                        
                            $first_sum_total = $colAry[$i]['first_sum'] + $first_sum_total;                        
                        }
                        $second_count_total = $colAry[$i]['second_count'] + $second_count_total;
                        $second_sum_total = $colAry[$i]['second_sum'] + $second_sum_total;
                        $trans_sum_total = $colAry[$i]['trans_sum'] + $trans_sum_total;                                                    
                    }
                    //塞進colAry最後一排，數字採用千位分組
                    for($p = count($colAry); $p >= count($colAry); $p--) {

                        $colAry[$p]['first_count'] =$first_count_total;
                        $colAry[$p]['first_sum'] = number_format($first_sum_total,0);
                        $colAry[$p]['second_count'] = $second_count_total;
                        $colAry[$p]['second_sum'] = number_format($second_sum_total,0);
                        $colAry[$p]['trans_sum'] = number_format($trans_sum_total,0);
                    }

                    //欄位名稱
                    $col    = $this->getcol();
                    //欄位標頭
                    $title  = $this->gettitle();

                    //判斷$colAry 不為空 且 筆數 >0 輸出成功資訊否則回傳失敗訊息
                    if($colAry !=null && count($colAry)>0) {                               

                        Yii::app()->user->setFlash('notice',"查詢帳戶分配請注意年月");
                    }else                                                                 
                        Yii::app()->user->setFlash('error',"查詢失敗，以日期 $qry_date 查無資料！");
                }else
                        Yii::app()->user->setflash('error', '年月不得為空值。');
            }
           
                $this->render('salarytrans',array(
                              'qry_date' =>$qry_date,
                              'qry_remit_date' =>$qry_remit_date,
                              'checkbox' =>$checkbox,
                              'colAry' =>$colAry,
                              'col' =>$col,
                              'title' =>$title,
                              'qry_trans' =>$qry_trans,
                              'qry_num' =>$qry_num,
                            ));
        }
        
        /**
        *  薪資帳戶編號對應帳戶號碼
        *  暫無使用
        */
        private function getAvaliableAcc($ary_acc_sum, $value, $acc_avg) 
        {           
            $key = FALSE;
            foreach ($ary_acc_sum as $index=>$acc_remain) {
                if($acc_remain < $acc_avg)
                    $key = $index;
            }            
            return $key;
        }
        
        /**
        * 判斷資料存在，如存在則刪除
        * // 1. 判斷年月是否存在
          // 2. 若存在, 刪除             
         * @param type $qry_date --年月 
         **/
        private function checkdate($qry_date) 
        {           
            $ary = TbmAccTrans::model()->findAllByAttributes(array('date'=>$qry_date,'type_trans'=>1));

            if(count($ary)>0) {
                
                TbmAccTrans::model()->deleteAllByAttributes(array('date'=>$qry_date,'type_trans'=>1));                
            }
            return $ary;            
        } 
               
        /**
        * 薪資作業資料庫欄位        
        * @param type $model -- tbm_acc_trans
        * @param type $trans_acc -- 第一次分配流水號
        * @param type $trans -- 匯款帳戶
        * @param type $nick -- 戶名暱稱
        * @param type $empno -- 員工編號
        * @param type $empname -- 員工姓名
        * @param type $emp_acc -- 員工匯款帳戶
        * @param type $racive -- 員工匯款帳戶名稱
        * @param type $salary_value -- 匯款金額
        * @param type $date -- 匯款日
        * @param type $num -- 次數         
        */
        private function modelcol($model,$trans_acc,$trans,$nick,$empno,$empname,$emp_acc,$racive,$salary_value,$date,$num) 
        {
            //匯款帳戶流水號
            $model->trans_acc_id = $trans_acc;             
            //匯款帳戶
            $model->trans_acc = $trans; 
            //戶名暱稱
            $model->nick = $nick;
            //員工編號
            $model->empno = $empno;
            //員工姓名
            $model->empname = $empname;
            //員工帳戶
            $model->emp_acc = $emp_acc;
            //員工帳戶名稱
            $model->racive = $racive;           
            //匯款金額
            $model->amt = $salary_value;
            //匯款日
            $model->date = $date;       
            //次數
            $model->num = $num;
            
            return $model;
        }
        
        /**
        * 薪資作業資料庫欄位        
        * @param type $model -- tbm_acc_trans
        * @param type $sec_trans_acc -- 第二次分配流水號
        * @param type $trans -- 匯款帳戶
        * @param type $nick -- 戶名暱稱
        * @param type $empno -- 員工編號
        * @param type $empname -- 員工姓名
        * @param type $emp_acc -- 員工帳戶
        * @param type $racive -- 員工帳戶名稱
        * @param type $salary_value -- 匯款金額
        * @param type $date -- 匯款日
        * @param type $num -- 次數         
        */
        private function modelcol2($model,$sec_trans_acc,$trans,$nick,$empno,$empname,$emp_acc,$racive,$salary_value,$date,$num) 
        {
            //匯款帳戶流水號                        
            $model->trans_acc_id = $sec_trans_acc;                           
            //匯款帳戶
            $model->trans_acc = $trans; 
            //戶名暱稱
            $model->nick = $nick;
            //員工編號
            $model->empno = $empno;
            //員工姓名
            $model->empname = $empname;
            //員工帳戶
            $model->emp_acc = $emp_acc;
            //員工帳戶名稱
            $model->racive = $racive;           
            //匯款金額
            $model->amt = $salary_value;
            //匯款日
            $model->date = $date;       
            //次數
            $model->num = $num;
            
            return $model;
        }
        
        /**
        *   設定帳戶分配標題列
        */
        private function getcol()
        {
            $qrycol = array(
                0 =>'id',
                1 =>'sender',
                2 =>'sbr',
                3 =>'nick',
                4 =>'first_count',
                5 =>'first_sum',
                6 =>'second_count',
                7 =>'second_sum',
                8 =>'trans_sum'
            );
            return $qrycol;
        }
        
        /**
        * 帳戶分配標題列轉中文
        * @return array()
        */
        private function gettitle()
        {               
            $title = array(
                'id'=>'流水號',
                'sender'=>'戶名',
                'sbr'=>'帳號',
                'nick'=>'暱稱',
                'first_count'=>'筆數',
                'first_sum'=>'取款條1',
                'second_count'=>'筆數',
                'second_sum'=>'取款條2',
                'trans_sum'=>'總數'
            );                          
            return $title;
        }
        
        /**
        *   設定匯款帳戶標題列
        */
        private function gettranscol()
        {
            $qrytranscol = array(
                0 =>'trans_acc',
                1 =>'nick',
                2 =>'empno',
                3 =>'empname',
                4 =>'emp_acc',
                5 =>'racive',
                6 =>'amt',
                7 =>'date',
                8=>'num'                                  
            );
            return $qrytranscol;
        }
        
        /**
        * 匯款帳戶標題列轉中文
        * @return array()
        */
        private function gettranstitle()
        {            
            $transtitle = array(                   
                'trans_acc'=>'匯款帳戶',
                'nick'=>'戶名暱稱',
                'empno'=>'員工編號',
                'empname'=>'員工姓名',
                'emp_acc'=>'收款帳戶',
                'racive'=>'收款帳戶名稱',
                'amt'=>'金額',
                'date'=>'匯款日',
                'num'=>'次數'                    
            );                          
            return $transtitle;
        }
               
}
