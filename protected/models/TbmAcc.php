<?php

/**
 * This is the model class for table "tbm_acc".
 *
 * The followings are the available columns in table 'tbm_acc':
 * @property integer $id
 * @property string $type
 * @property string $sender
 * @property string $nick
 * @property string $sbr
 * @property string $taxid
 * @property string $salary
 * @property string $remit
 * @property string $breakeven
 * @property string $memo
 * @property string $opt1
 * @property string $opt2
 * @property string $opt3
 * @property string $cemp
 * @property string $ctime
 * @property string $uemp
 * @property string $utime
 * @property string $ip
 */
class TbmAcc extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbm_acc';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('sender, sbr, taxid', 'required'),
			array('type, salary, remit, breakeven, opt1, opt2, opt3', 'length', 'max'=>1),
			array('sender', 'length', 'max'=>60),
			array('nick', 'length', 'max'=>30),
			array('sbr', 'length', 'max'=>16),
			array('taxid', 'length', 'max'=>10),
			array('memo', 'length', 'max'=>255),
			array('cemp, uemp', 'length', 'max'=>8),
			array('ip', 'length', 'max'=>15),
			array('ctime, utime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, sender, nick, sbr, taxid, salary, remit, breakeven, memo, opt1, opt2, opt3, cemp, ctime, uemp, utime, ip', 'safe', 'on'=>'search'),
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
			'type' => '類別',
			'sender' => '匯款人戶名',
			'nick' => '戶名暱稱',
			'sbr' => '匯款行',
			'taxid' => '匯款人統編',
			'salary' => '薪資',
			'remit' => '匯款',
			'breakeven' => '保本',
			'memo' => '備註',
			'opt1' => '是否使用',
			'opt2' => '備用2',
			'opt3' => '備用3',
			'cemp' => '建立人員',
			'ctime' => '建立時間',
			'uemp' => '修改人員',
			'utime' => '修改時間',
			'ip' => 'Ip',
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
		$criteria->compare('type',$this->type,true);
		$criteria->compare('sender',$this->sender,true);
		$criteria->compare('nick',$this->nick,true);
		$criteria->compare('sbr',$this->sbr,true);
		$criteria->compare('taxid',$this->taxid,true);
		$criteria->compare('salary',$this->salary,true);
		$criteria->compare('remit',$this->remit,true);
		$criteria->compare('breakeven',$this->breakeven,true);
		$criteria->compare('memo',$this->memo,true);
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
	 * @return TbmAcc the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
