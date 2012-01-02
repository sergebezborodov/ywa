<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @property string pageTitle
 */
abstract class BaseController extends CController {
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/1column.php'.
	 */
	public $layout = '//layouts/main';
    
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();

	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();
	
	public $isAdmin = false;
    

    public $metaDesc = '';
    public $metaKeys = '';


    public $adminMenu = array();

	/**
	 * Страница по умолчанию для редиректа с ошибкой
	 * @var string
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
     * Поиск нужно вьюхи в зависимости от языка
     *
     * @param $viewName
     * @param $viewPath
     * @param $basePath
     * @param null $moduleViewPath
     * @return bool|string
     */
	public function resolveViewFile($viewName, $viewPath, $basePath, $moduleViewPath=null) {
        if(empty($viewName))
            return false;

        if(($renderer=Yii::app()->getViewRenderer())!==null)
            $extension=$renderer->fileExtension;
        else
            $extension='.php';
        if($viewName[0]==='/')
            $viewFile=$basePath.$viewName.$extension;
        else if(strpos($viewName,'.'))
            $viewFile=Yii::getPathOfAlias($viewName).$extension;
        else
            $viewFile=$viewPath.DIRECTORY_SEPARATOR.$viewName.$extension;
        //такой папки точно нет
        return Yii::app()->findLocalizedFile($viewFile,'+++');
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

        return true;
    }


    /**
     * Загрузка модели по id
     *
     * @throws CHttpException
     * @param string $modelName
     * @param bool $checkOwner проверять владельца по user_id
     * @return ActiveRecord
     */
    protected function _loadModel($modelName, $checkOwner = true) {
        if (empty($_GET['id'])) {
            throw new CHttpException(400, 'Не указан id модели');
        }
        $model = BaseActiveRecord::model($modelName)->findByPk($_GET['id']);
        if ($model === null) {
            throw new CHttpException(404, "Запись #{$_GET['id']} модели {$modelName} не найден в системе");
        }
        if ($checkOwner && !empty($model->user_id) && $model->user_id != Y::userId()) {
            throw new CHttpException(403, 'Доступ не разрешен');
        }

        return $model;
    }

}
