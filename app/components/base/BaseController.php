<?php
/**
 * Base application controller
 *
 * @property string pageTitle
 */
abstract class BaseController extends CController
{
	/**
	 * @var string current layout
	 */
	public $layout = '/layouts/column1';

	/**
	 * @var array
	 */
	public $breadcrumbs = array();

    /**
     * @var string meta description
     */
    public $metaDesc = '';

    /**
     * @var string meta keywords
     */
    public $metaKeys = '';

	/**
	 * @var string default error redirect page
	 */
	protected $defaultErrorRedirect = '/';

    /**
     * Redirect with error flash
     *
     * @param string $msg
     * @param array $redirect
     * @return void
     */
	protected function denyRequest($msg = '', $redirect = array())
    {
		if (Yii::app()->getRequest()->isAjaxRequest) {
			$result = array(
				'result'	=> 'error',
				'message'	=> $msg,
			);
            $this->jsonEnd($result);
		} else {
			if (empty($redirect)) {
				if (Yii::app()->getRequest()->urlReferrer) {
					$redirect = Yii::app()->getRequest()->urlReferrer;
				} else {
					$redirect = $this->defaultErrorRedirect;
				}
	        }
	        if (!empty($msg)) {
	        	$this->errorFlash($msg);
	        }
	        $this->redirect($redirect);
		}
	}


    /**
     * Output json and and application
     *
     * @param string $message
     * @param array $data
     */
    public function jsonEnd($message = '', $data = array())
    {
        Y::endJson(array(
			'result'	=> 'ok',
			'message'	=> $message,
			'data'		=> $data,
		));
    }

    /**
     * Output json and and application
     *
     * @param string $message
     * @param array $data
     */
    public function jsonError($message = '', $data = array())
    {
        Y::endJson(array(
			'result'	=> 'error',
			'message'	=> $message,
			'data'		=> $data,
		));
    }

	/**
	 * Set session flash
	 *
	 * @param string $msg message
	 * @param string $type message type success|error
	 * @return void
	 */
	protected function flash($msg, $type = 'success')
    {
		Yii::app()->user->setFlash($type, $msg);
	}

    /**
     * Sets error type flash
     *
     * @param string $message
     * @return void
     */
    protected function errorFlash($message)
    {
        $this->flash($message, 'error');
    }

	/**
	 * Check if $id is valid and make integer value
	 *
	 * @param int $id
	 * @return bool
	 */
	public function validateId(&$id = null)
    {
		if (empty($id) || !is_numeric($id) || ($id != intval($id))) {
			return false;
		}
		$id = intval($id);
		return true;
	}


	/**
	 * Check if current is main page
	 *
	 * @return bool
	 */
	public function getIsMainPage()
    {
		return Y::app()->controller->id === 'site'
			&& Y::app()->controller->action->id === 'index';
	}

    /**
     * Register scripts
     *
     * @param $view
     * @return bool
     */
    public function beforeRender($view)
    {
        parent::beforeRender($view);

        // if ajax request don't attach any script
        if (Yii::app()->getRequest()->getIsAjaxRequest()) {
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

        return true;
    }


    /**
     * Load model by ID
     *
     * @throws CHttpException
     * @param string $modelName model class name
     * @param int $modelId
     * @return BaseActiveRecord
     */
    protected function loadModel($modelName, $modelId = null)
    {
        if ($modelId === null && empty($_GET['id'])) {
            throw new CHttpException(400, "Bad request");
        }
        $id = $modelId === null ? $_GET['id'] : $modelId;
        $model = BaseActiveRecord::model($modelName)->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, "Record #{$id} of model {$modelName} not found");
        }
        return $model;
    }
}
