<?php

/**
 * Функции для работы с кешем
 */
class C {
    /**
    * @return CCache
    */
    public function cache() {
        return Yii::app()->getCache();
    }

    /**
    * Читает из кеша параметр
    *
    * @param mixed $id
    * @return mixed
    */
    public function get($id) {
        return Yii::app()->getCache()->get($id);
    }

    /**
     * Записывает параметр в кеш
     *
     * @param string $id the key identifying the value to be cached
     * @param mixed $value the value to be cached
     * @param integer $expire the number of seconds in which the cached value will expire. 0 means never expire.
     * @param ICacheDependency $dependency dependency of the cached item. If the dependency changes, the item is labeled invalid.
     * @return boolean true if the value is successfully stored into cache, false otherwise
     */
    public function set($id,$value,$expire=0,$dependency=null) {
        return Yii::app()->getCache()->set($id,$value,$expire,$dependency);
    }
}