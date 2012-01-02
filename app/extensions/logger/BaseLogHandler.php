<?

/**
 * Базовый класс лог обработчиков
 *
 */
abstract class BaseLogHandler {
	
	/**
	 * @return array список уровней которые логгирует обработчик
	 */
	abstract public function getLevels();
	
	/**
     * @param int $level
	 * @return bool логирует ли данный класс уровень $level 
	 */
	abstract public function isLogged($level);
	
	/**
	 * Запись сообщения в лог
	 * 
	 * @param string $message сообщение
	 * @param string $target название назначение лога, например файла
	 * @param string $level уровень сообщения 
	 * @param string $from откуда была вызвана запись в лог
	 * @return bool
	 * @see LogLevel
	 */
	abstract public function write($message = null, $target = null, $level = LogLevel::TRACE, $from = null);
	
}