<?php
/**
 * Базовый контроллер приложения
 *
 * @property string pageTitle
 */
abstract class BaseController extends CController {
	/**
	 * @var string текущий layout
	 */
	public $layout = '//layouts/main';

	/**
	 * @var array
	 */
	public $breadcrumbs = array();

    /**
     * @var string meta описание
     */
    public $metaDesc = '';

    /**
     * @var string meta ключевые слова
     */
    public $metaKeys = '';

	/**
	 * @var string страница по умолчанию для редиректа с ошибкой
	 */
	protected $_defaultErrorRedirect = '/';

    /**
     * Редирект с флешкой
     *
     * @param string $msg
     * @param array $redirect
     * @return void
     */
	protected function _denyRequest($msg = '', $redirect = array()) {
		if (Y::request()->isAjaxRequest) {
			$result = array(
				'result'	=> 'error',
				'message'	=> $msg,
			);
			Y::endJson($result);
		} else {
			if (empty($redirect)) {				
				if (Y::request()->urlReferrer) {
					$redirect = Y::request()->urlReferrer;
				} else {
					$redirect = $this->_defaultErrorRedirect;
				}
	        }
	        if (!empty($msg)) {
	        	$this->_flash($msg, 'error');
	        }
	        $this->redirect($redirect);
		}
	}


    /**
     * @param string $message
     * @param array $data
     */
    public function jsonEnd($message = '', $data = array()) {
        Y::endJson(array(
			'result'	=> 'ok',
			'message'	=> $message,
			'data'		=> $data,
		));
    }

    /**
     * @param string $message
     * @param array $data
     */
    public function jsonError($message = '', $data = array()) {
        Y::endJson(array(
			'result'	=> 'error',
			'message'	=> $message,
			'data'		=> $data,
		));
    }
	
	/**
	 * Установка флешки
	 * 
	 * @param string $msg сообщение
	 * @param string $type тип сообщения
	 * @return void
	 */
	protected function _flash($msg, $type = 'success') {
		Yii::app()->user->setFlash($type, $msg);
	}

    /**
     * Флешка об ошибке
     *
     * @param  $message
     * @return void
     */
    protected function _errorFlash($message) {
        $this->_flash($message, 'error');
    }

	/**
	 * Валидец ли id объекта
	 * 
	 * @param int $id
	 * @return bool
	 */
	public function validateId(&$id = null) {
		if (empty($id) || !is_numeric($id) || ($id != intval($id))) {
			return false;
		}
		$id = intval($id);
		return true;
	}

	
	/**
	 * Главная ли это страница
	 * 
	 * @return bool
	 */
	public function getIsMainPage() {
		return Y::app()->controller->id === 'site' 
			&& Y::app()->controller->action->id === 'index';
	}

    /**
     * Вспомогательные действия
     *
     * @param $action
     * @return bool
     */
    public function beforeAction($action) {
        $uri = $_SERVER['REQUEST_URI'];
        if (strlen($uri) > 1
            && $uri{strlen($uri) - 1} == '/') {
            $this->redirect(substr($uri, 0, strlen($uri) - 1), true, 301);
        }

        return true;
    }

    /**
     * Покдлючение скриптов
     *
     * @param $view
     * @return bool
     */
    public function beforeRender($view) {
        parent::beforeRender($view);

        // для ajax никакие скрипт файлы не подключаем
        if (Y::isAjaxRequest()) {
            return true;
        }

        $actionScriptFile = Yii::getPathOfAlias("webroot.js.".Y::app()->controller->id)
                                    .'/'.Y::app()->controller->action->id . '.js';

        if (file_exists($actionScriptFile)) {
            Yii::app()->clientScript->registerScriptFile(Html::asset($actionScriptFile));
        }

        $sharedScriptFile = Yii::getPathOfAlias("webroot.js.".Y::app()->controller->id)
                            .'/shared.js';

        if (file_exists($sharedScriptFile)) {
            Yii::app()->clientScript->registerScriptFile(Html::asset($sharedScriptFile));
        }
    }


    /**
     * Загрузка модели по id
     *
     * @throws CHttpException
     * @param string $modelName
     * @param bool $checkOwner проверять владельца по user_id\
     * @param string $userIdFieldName название поле с id пользователя модели
     * @return ActiveRecord
     */
    protected function _loadModel($modelName, $checkOwner = false, $userIdFieldName = 'user_id') {
        if (empty($_GET['id'])) {
            throw new CHttpException(400, 'Не указан id модели');
        }
        $model = BaseActiveRecord::model($modelName)->findByPk($_GET['id']);
        if ($model === null) {
            throw new CHttpException(404, "Запись #{$_GET['id']} модели {$modelName} не найдена");
        }
        if ($checkOwner && !empty($model->{$userIdFieldName}) && $model->{$userIdFieldName} != Y::userId()) {
            throw new CHttpException(403, 'Доступ не разрешен');
        }

        return $model;
    }

}
