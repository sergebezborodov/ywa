<?php

/**
 * Эксепшен приложения
 */
class AppException extends CException
{
    protected $data;

    /**
     * Создание исключения
     *
     * @param string $message сообщение
     * @param int $code код ошибки
     * @param array $data дополнительные данные
     */
    public function __construct($message, $code = 0, $data = array())
    {
        $this->message = $message;
        $this->code = $code;

        $this->data = $data;
    }

    /**
     * @return array доп данные исключения
     */
    public function getData()
    {
        return $this->data;
    }
}
