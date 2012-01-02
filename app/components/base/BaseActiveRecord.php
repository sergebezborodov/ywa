<?php

/**
 * Базовый класс ActiveRecord
 */
abstract class BaseActiveRecord extends CActiveRecord {

    const ERROR_VALIDATE = 10;
	
   /**
    * Поле которое содержит дату создания записи
    * @var string
    */
    protected $_createdField = 'created_date';
    
   /**
    * Поле которое содержит дату редактирования записи
    * @var string
    */
    protected $_updatedField = 'updated_date';
    
    protected $_lastErrorMessage = null;
 
    static protected $_transaction;
    
    /**
	 * Проставляет дату создания и модификации записи
	 * 
	 * @return bool
     */
    protected function beforeSave() {
    	if (!parent::beforeSave()) {
    		return false;
    	}

        if (isset($this->metadata->tableSchema->columns[$this->_updatedField])){
            $this->{$this->_updatedField} = new CDbExpression('NOW()');
        }
    	if ($this->isNewRecord) {
    		if (isset($this->metadata->tableSchema->columns[$this->_createdField])){
            	$this->{$this->_createdField} = new CDbExpression('NOW()');
    		}
        }
 
        return true;  
    }
    
    public function beforeValidate() {
    	if (!parent::beforeValidate()) {
    		return false;
    	}
    	if (isset($this->metadata->tableSchema->columns['slug'], $this->metadata->tableSchema->columns['title']) 
    		&& empty($this->slug)){
    		$this->slug = $this->createUrlName($this->title);
        }
    	return true;
    }
    
    /**
     * Устанавливает последнее сообщение об ошибке
     * 
     * @param string $message
     * @return bool false
     */
    public function onError($message = null) {
    	$this->_lastErrorMessage = $message;
    	return false;
    }
    
    /**
     * Возвращает последнее сообщение об ошибке
     * @return string
     */
    public function getLastErrorMessage() {
    	if (empty($this->_lastErrorMessage)) {
    		$this->_lastErrorMessage = 'Ошибка в процессе выполнения';
    	}
    	return $this->_lastErrorMessage;
    }
    
    
    /**
     * Начало транзакции
     * 
     * @return bool true
     */
    public static function start() {
    	self::$_transaction = Yii::app()->db->beginTransaction();
    	return true;
    }
    
    /**
     * Откат транзакции
     * 
     * @return bool true
     */
    public static function rollBack() {
    	self::$_transaction->rollBack();
    	return true;
    }
    
    /**
     * Коммит транзакции
     * 
     * @return bool true
     */
    public static function commit() {
    	self::$_transaction->commit();
    	return true;
    }
}
