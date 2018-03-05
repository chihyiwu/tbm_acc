<?php

/**
 * This is the model class for table "tbe_alloc".
 *
 * The followings are the available columns in table 'tbe_alloc':
 * @property integer $id
 * @property string $ano
 * @property string $adate
 * @property string $make_no
 * @property string $make_emp
 * @property string $re_no
 * @property string $re_emp
 * @property string $posted
 * @property string $pno
 * @property string $unit
 * @property string $ptalk
 * @property string $pclass
 * @property string $cname
 * @property string $format
 * @property string $num
 * @property string $iware
 * @property string $iwname
 * @property string $oware
 * @property string $owname
 * @property string $avgcost
 * @property string $memo
 * @property string $pass
 * @property string $cost
 * @property string $fno
 * @property string $total
 * @property string $opt1
 * @property string $opt2
 * @property string $opt3
 * @property string $cemp
 * @property string $ctime
 * @property string $uemp
 * @property string $utime
 * @property string $ip
 */
class TbeAlloc extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbe_alloc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ano, fno', 'required'),
			array('ano', 'length', 'max'=>11),
			array('adate', 'length', 'max'=>9),
			array('make_no, make_emp, re_no, re_emp, iwname, owname, avgcost, cost, total, cemp, uemp', 'length', 'max'=>8),
			array('posted, pass, opt1, opt2, opt3', 'length', 'max'=>1),
			array('pno', 'length', 'max'=>8),
			array('unit', 'length', 'max'=>2),
			array('ptalk, memo', 'length', 'max'=>255),
			array('pclass', 'length', 'max'=>4),
			array('cname, format', 'length', 'max'=>60),
			array('num', 'length', 'max'=>12),
			array('iware, oware', 'length', 'max'=>6),
			array('fno', 'length', 'max'=>20),
			array('ip', 'length', 'max'=>15),
			array('ctime, utime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ano, adate, make_no, make_emp, re_no, re_emp, posted, pno, unit, ptalk, pclass, cname, format, num, iware, iwname, oware, owname, avgcost, memo, pass, cost, fno, total, opt1, opt2, opt3, cemp, ctime, uemp, utime, ip', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '流水號',
			'ano' => '調撥單號',
			'adate' => '調撥日期',
			'make_no' => '製單人員編號',
			'make_emp' => '製單人員',
			're_no' => '覆核人員編號',
			're_emp' => '覆核人員',
			'posted' => '是否過帳',
			'pno' => '產品編號',
			'unit' => '計量單位',
			'ptalk' => '產品說明',
			'pclass' => '產品類別',
			'cname' => '類別名稱',
			'format' => '品名規格',
			'num' => '數量',
			'iware' => '撥入倉庫',
			'iwname' => '撥入倉庫名稱',
			'oware' => '撥出倉庫',
			'owname' => '撥出倉庫名稱',
			'avgcost' => '平均成本',
			'memo' => '備註',
			'pass' => '是否過帳',
			'cost' => '成本',
			'fno' => '費用編號',
			'total' => '總計',
			'opt1' => '是否使用',
			'opt2' => '備用2',
			'opt3' => '備用3',
			'cemp' => '建立人員',
			'ctime' => '建立時間',
			'uemp' => '修改人員',
			'utime' => '修改時間',
			'ip' => '修改IP',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('ano',$this->ano,true);
		$criteria->compare('adate',$this->adate,true);
		$criteria->compare('make_no',$this->make_no,true);
		$criteria->compare('make_emp',$this->make_emp,true);
		$criteria->compare('re_no',$this->re_no,true);
		$criteria->compare('re_emp',$this->re_emp,true);
		$criteria->compare('posted',$this->posted,true);
		$criteria->compare('pno',$this->pno,true);
		$criteria->compare('unit',$this->unit,true);
		$criteria->compare('ptalk',$this->ptalk,true);
		$criteria->compare('pclass',$this->pclass,true);
		$criteria->compare('cname',$this->cname,true);
		$criteria->compare('format',$this->format,true);
		$criteria->compare('num',$this->num,true);
		$criteria->compare('iware',$this->iware,true);
		$criteria->compare('iwname',$this->iwname,true);
		$criteria->compare('oware',$this->oware,true);
		$criteria->compare('owname',$this->owname,true);
		$criteria->compare('avgcost',$this->avgcost,true);
		$criteria->compare('memo',$this->memo,true);
		$criteria->compare('pass',$this->pass,true);
		$criteria->compare('cost',$this->cost,true);
		$criteria->compare('fno',$this->fno,true);
		$criteria->compare('total',$this->total,true);
		$criteria->compare('opt1',$this->opt1,true);
		$criteria->compare('opt2',$this->opt2,true);
		$criteria->compare('opt3',$this->opt3,true);
		$criteria->compare('cemp',$this->cemp,true);
		$criteria->compare('ctime',$this->ctime,true);
		$criteria->compare('uemp',$this->uemp,true);
		$criteria->compare('utime',$this->utime,true);
		$criteria->compare('ip',$this->ip,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TbeAlloc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
