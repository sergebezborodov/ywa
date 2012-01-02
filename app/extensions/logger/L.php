<?

class LogLevel {
	const TRACE		= 'trace';
	const LOG		= 'log';
	
	const SUCCESS	= 'success';
	const ERROR		= 'error';

	const FATAL		= 'fatal';
	
	/**
	 * @return array всех значений как ключ массива, название как значение
	 */
	public static function items() {
		return array(
			self::TRACE		=> 'Трассировка',
			self::LOG		=> 'Лог',
			self::SUCCESS	=> 'Успешное выполнение',
			self::ERROR		=> 'Ошибка',
			self::FATAL		=> 'Критическая ошибка',
		);
	}
	
	/**
	 * Проверка корректности значения 
	 * @return bool
	 */
	public static function isValid($value) {
		return array_key_exists($value, self::items());
	}
}

/**
 * Logger class
 */
class L {
	private static  $__instance = null;
	private $__handlers = null;

    private $__handlersFiles = array(
        'EmailLog',
        'FileLog',
    );
	
	private function __construct() {
		// загрузка всех обработчиков
        require_once 'BaseLogHandler.php';
        foreach ($this->__handlersFiles as $class) {
            require_once Yii::getPathOfAlias('application.extensions.logger.handlers').DS.$class.'.php';
            $this->__handlers[] = new $class();
        }
	}

    /**
     * @static
     * @return L
     */
	public static function getInstance() {
		if (is_null(self::$__instance)) {
			self::$__instance = new self();
		}
		return self::$__instance;
	}
	
	/**
	 * Отправка сообщения в обработчики
	 * 
	 * @param string $message сообщение
	 * @param string $target целевое назначение (например, файл)
	 * @param string $level уровень сообщения
	 * @param string $from вызывающий метод
	 * @return bool
	 */
	private function __write($message, $target, $level, $from) {
		foreach ($this->__handlers as $handler) {
			$handler->write($message, $target, $level, $from);
		}
		return true;
	}
	
	/**
	 * Запись трассировочного сообщения
	 * 
	 * @param string $message сообщение
	 * @param string $target целевое назначение (например, файл)
	 * @param string $from вызывающий метод (используется константа __METHOD__)
	 * @return bool
	 */
	public static function trace($message = null, $target = null, $from = null) {
		return self::getInstance()->__write($message, $target, LogLevel::TRACE, $from); 
	}
	
	/**
	 * Запись информационного сообщения о текущем действии
	 * 
	 * @param string $message сообщение
	 * @param string $target целевое назначение (например, файл)
	 * @param string $from вызывающий метод (используется константа __METHOD__)
	 * @return bool
	 */
	public static function log($message = null, $target = null, $from = null) {
		return self::getInstance()->__write($message, $target, LogLevel::LOG, $from); 
	}
	
	/**
	 * Запись сообщения об успешном завершении операции
	 * 
	 * @param string $message сообщение
	 * @param string $target целевое назначение (например, файл)
	 * @param string $from вызывающий метод (используется константа __METHOD__)
	 * @return bool
	 */
	public static function success($message = null, $target = null, $from = null) {
		return self::getInstance()->__write($message, $target, LogLevel::SUCCESS, $from);
	}
	
	/**
	 * Запись сообщения об ошибке
	 * 
	 * @param string $message сообщение
	 * @param string $target целевое назначение (например, файл)
	 * @param string $from вызывающий метод (используется константа __METHOD__)
	 * @return bool
	 */
	public static function error($message = null, $target = null, $from = null) {
		return self::getInstance()->__write($message, $target, LogLevel::ERROR, $from);
	}

	/**
	 * Запись сообщения о нереально крилической ошибке
	 * с данным статусом идет отправка на email
	 *
	 * @static
	 * @param null $message
	 * @param null $target
	 * @param null $from
	 * @return bool
	 */
	public static function fatal($message = null, $target = null, $from = null) {
		return self::getInstance()->__write($message, $target, LogLevel::FATAL, $from);
	}
}
