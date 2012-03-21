<?php

class ActiveRecordException extends AppException
{}


/**
 * Extended active record class with helper functions
 */
class BaseActiveRecord extends CActiveRecord
{
    /**
     * @var string field name with record creation date
     */
    protected $createdField = 'created_date';

    /**
     * @var string field name with record last update date
     */
    protected $updatedField = 'updated_date';

    /**
     * Sets created and updated field values
     *
     * @return bool true
     */
    protected function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        if (isset($this->getMetaData()->tableSchema->columns[$this->updatedField])) {
            $this->{$this->updatedField} = new CDbExpression('NOW()');
        }

        if ($this->isNewRecord && isset($this->getMetaData()->tableSchema->columns[$this->createdField])) {
            $this->{$this->createdField} = new CDbExpression('NOW()');
        }

        return true;
    }


    /**
     * Start DB transaction
     *
     * @param CDbConnection $connection
     * @return CDbTransaction
     */
    public static function beginTransaction($connection = null)
    {
        if ($connection === null) {
            $connection = Yii::app()->getDb();
        }

        return $connection->beginTransaction();
    }

    /**
     * Commit current DB connection transaction
     *
     * @static
     * @param CDbConnection $connection
     */
    public static function commitTransaction($connection = null)
    {
        if ($connection === null) {
            $connection = Yii::app()->getDb();
        }

        /** @var CDbTransaction $transaction */
        if (($transaction = $connection->getCurrentTransaction()) === null) {
            throw new ActiveRecordException('Transaction not started');
        }

        $transaction->commit();
    }

    /**
     * Rollback current DB connection transaction
     *
     * @static
     * @param CDbConnection $connection
     */
    public static function rollbackTransaction($connection = null)
    {
        if ($connection === null) {
            $connection = Yii::app()->getDb();
        }

        /** @var CDbTransaction $transaction */
        if (($transaction = $connection->getCurrentTransaction()) === null) {
            throw new ActiveRecordException('Transaction not started');
        }

        $transaction->rollback();
    }
}
