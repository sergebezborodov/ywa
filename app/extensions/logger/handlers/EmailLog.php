<?php

/**
 * Email логгер
 */
class EmailLog extends BaseLogHandler {

	/**
	 * @return array список emails на которые слать сообщения
	 */
	protected function _getEmails() {
		return array(
			'serhey89@gmail.com',
		);
	}

	/**
	 * @return array список уровней которые логгирует обработчик
	 */
	public function getLevels() {
		return array(LogLevel::FATAL);
	}

	/**
	 * @return bool логирует ли данный класс уровень $level
	 */
	public function isLogged($level) {
		return in_array($level, $this->getLevels());
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
	 * @param string $target название назначение лога, например файла
	 * @param string $level уровень сообщения
	 * @param string $from откуда была вызвана запись в лог
	 * @return bool
	 * @see LogLevel
	 */
	public function write($message = null, $target = null, $level = LogLevel::TRACE, $from = null) {
		if (!$this->isLogged($level)) {
			return true;
		}

		// преобразование массива
		if (is_array($message)) {
			$message = print_r($message, true);
		}

		// если указан источник сообщения то добавляем форматирование
		$ffrom = '';
		if (!empty($from)) {
			$ffrom = ' '.$from . ': ';
		}

		$level = $this->__formatLevel($level);
		// форматируем сообщение
		$message = "файл: '$target' - ".date('Y-m-d H:i:s') . ": {$level}{$ffrom} $message\r\n";

		$emails = implode(', ', $this->_getEmails());
		$subject = 'Критическая ошибка';
		if (!empty($from)) {
			$subject .= ': '.$from;
		}

		return mail($emails, $subject, $message);
	}
}
