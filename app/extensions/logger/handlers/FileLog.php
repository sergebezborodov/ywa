<?

/**
 * Файловый логгер
 */
class FileLog extends BaseLogHandler {
	
	private $__files;
	
	/**
	 * @return array список уровней которые логгирует обработчик
	 */
	public function getLevels() {
		return array_keys(LogLevel::items());
	}

	/**
	 * @return bool логирует ли данный класс уровень $level 
	 */
	public function isLogged($level) {
		return in_array($level, $this->getLevels());
	}
	
	/**
	 * Возвращает закешированный объект
	 * при необходимости создает его
	 * 
	 * @return resource
	 */
	private function __getFile($file) {
		if (empty($this->__files[$file])) {
			if (empty($file)) {
				$file = 'blackhole';
			}
            $fp = fopen( Yii::getPathOfAlias('application.runtime.logs') . DIRECTORY_SEPARATOR . $file. '.log', "a");
			$this->__files[$file] = $fp;
		}
		return $this->__files[$file];
	}
	
	private function __formatLevel($level) {
		$maxLength = strlen(LogLevel::SUCCESS);
		$spaces = $maxLength - strlen($level);
		
		return '['.$level.']'.str_repeat(' ', $spaces);
	}
	
	/**
	 * Запись сообщения в лог
	 * 
	 * @param string $message сообщение
	 * @param string $target
	 * @param string $level уровень сообщения 
	 * @param string $from откуда была вызвана запись в лог
	 * @return bool
	 * @see LogLevel
	 */
	public function write($message = null, $target = null, $level = LogLevel::TRACE, $from = null) {
		if (!$this->isLogged($level)) {
			return true;
		}
		$fp = $this->__getFile($target);
		
		// преобразование массива
		if (is_array($message)) {
			$message = print_r($message, true);
		}
		
		// заменяем переносы строк 
		$message = str_replace(array("\r", "\n"), array('', '\\'), $message);

		// если указан источник сообщения то добавляем форматирование
		if (!empty($from)) {
			$from = ' '.$from . ': ';
		}
		$level = $this->__formatLevel($level);
		// форматируем сообщение
		$message = date('Y-m-d H:i:s') . ": {$level}{$from} $message\r\n";

        fwrite($fp, $message);
		
		return true;
	}
	
}