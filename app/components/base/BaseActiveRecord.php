<?php

/**
 * Базовый класс ActiveRecord
 */
abstract class BaseActiveRecord extends CActiveRecord
{
	
   /**
    * Поле которое содержит дату создания записи
    * @var string
    */
    protected $createdField = 'created_date';
    
   /**
    * Поле которое содержит дату редактирования записи
    * @var string
    */
    protected $updatedField = 'updated_date';

    /**
     * @var string название поля для url alias названия
     */
    protected $slugField = 'slug';

    /**
     * @var string название поля с названием объекта (title, name, etc)
     */
    protected $titleField = 'title';

    static protected $_transaction;
    
    /**
	 * Проставляет дату создания и модификации записи
	 * 
	 * @return bool
     */
    protected function beforeSave()
    {
    	if (!parent::beforeSave()) {
    		return false;
    	}

        if (isset($this->metadata->tableSchema->columns[$this->updatedField])){
            $this->{$this->updatedField} = new CDbExpression('NOW()');
        }
    	if ($this->isNewRecord) {
    		if (isset($this->metadata->tableSchema->columns[$this->createdField])){
            	$this->{$this->createdField} = new CDbExpression('NOW()');
    		}
        }
 
        return true;  
    }

    /**
     * Генерация slug при необходимости
     *
     * @return bool
     */
    public function beforeValidate()
    {
    	if (!parent::beforeValidate()) {
    		return false;
    	}
    	if (isset($this->metadata->tableSchema->columns[$this->slugField],
                 $this->metadata->tableSchema->columns[$this->titleField])
            && empty($this->{$this->slugField})) {

            $this->slug = $this->createUrlName($this->{$this->titleField});
        }
    	return true;
    }
    
    /**
     * Начало транзакции
     * 
     * @return bool true
     */
    public static function start()
    {
    	self::$_transaction = Yii::app()->db->beginTransaction();
    	return true;
    }
    
    /**
     * Откат транзакции
     * 
     * @return bool true
     */
    public static function rollBack()
    {
    	self::$_transaction->rollBack();
    	return true;
    }
    
    /**
     * Коммит транзакции
     * 
     * @return bool true
     */
    public static function commit()
    {
    	self::$_transaction->commit();
    	return true;
    }
}
