<?php
/**
 * CmsInput
 * 
 * @package OneTwist CMS  
 * @author twisted1919 (cristian.serban@onetwist.com)
 * @copyright OneTwist CMS (www.onetwist.com)
 * @version 1.0
 * @since 1.0
 * @access public
 */
class CmsInput extends CApplicationComponent
{
    
    protected $_cleanPostCompleted    = false;
    protected $_cleanGetCompleted     = false;
    protected $_CI_Security;
    
    protected $_originalPost  = array();
    protected $_originalGet   = array();
    
    protected $_purifier;

    public $cleanPost   = false;
    public $cleanGet    = false;

    public function init()
    {
        if(!empty($_POST))
            $this->_originalPost=$_POST;
        
        if(!empty($_GET))
            $this->_originalGet=$_GET;

        parent::init();
        Yii::app()->attachEventHandler('onBeginRequest', array($this, 'cleanGlobals'));
    }
    
    public function purify($str)
    {
        if(is_array($str))
        {
            foreach($str AS $k=>$v)
                $str[$k]=$this->purify($v);
            return $str;
        }
        return $this->getHtmlPurifier()->purify($str);
    }

    public function xssClean($str, $isImage=false)
    {
        return $this->getCISecurity()->xss_clean($str, $isImage);
    }

    public function stripTags($str, $encode=false)
	{
        if(is_array($str))
        {
            foreach($str AS $k=>$v) 
                $str[$k]=$this->stripTags($v, $encode);
            return $str;
        }   
        $str=strip_tags($str);    
        $str=trim($str);
        
        if($encode) 
            $str=CHtml::encode($str);
        
        return $str;              
	}
    
    public function stripCleanEncode($str)
    {
        if(is_array($str))
        {
            foreach($str AS $k=>$v)
                $str[$k]=$this->stripCleanEncode($v);
            return $str;
        }
        $str=$this->stripClean($str);
        return CHtml::encode($str); 
    }
    
    public function stripClean($str)
    {
        return $this->xssClean($this->stripTags($str));
    }
    
    public function encode($str)
    {
        if(is_array($str))
        {
            foreach($str AS $k=>$v)
                $str[$k]=CHtml::encode($v);
            return $str;
        }
        return CHtml::encode($str);
    }
    
    public function get($key, $defaultValue='', $xssClean=true)
    {
        if($xssClean===true && $this->_cleanGetCompleted===false)
            return $this->xssClean(Yii::app()->request->getQuery($key, $defaultValue));
        return Yii::app()->request->getQuery($key, $defaultValue);
    }
    
    public function post($key, $defaultValue='', $xssClean=true)
    {
        if($xssClean===true && $this->_cleanPostCompleted===false)
            return $this->xssClean(Yii::app()->request->getPost($key, $defaultValue));
        return Yii::app()->request->getPost($key, $defaultValue);
    }
    
    public function getPost($key, $defaultValue='', $xssClean=true)
    {
        if($get=$this->get($key, $defaultValue, $xssClean))
            return $get;
        return $this->post($key, $defaultValue, $xssClean);
    }

    public function sanitizeFilename($file)
    {
        return $this->getCISecurity()->sanitize_filename($file);
    } 

    protected function cleanGlobals()
    {
        if($this->cleanPost===true && $this->_cleanPostCompleted===false && !empty($_POST))
        {
            foreach($_POST AS $key=>$value)
                $_POST[$key]=$this->xssClean($value);
            $this->_cleanPostCompleted=true;
        }
        if($this->cleanGet===true && $this->_cleanGetCompleted===false && !empty($_GET))
        {
            foreach($_GET AS $key=>$value)
                $_GET[$key]=$this->xssClean($value);
            $this->_cleanGetCompleted=true;
        }
    }
    
    public function getOriginalPost()
    {
        return $this->_originalPost;
    }
    
    public function getOriginalGet()
    {
        return $this->_originalGet;
    }
    
    private function getCISecurity()
    {
        if($this->_CI_Security!==null)
            return $this->_CI_Security;
        Yii::import('application.vendors.Codeigniter.CI_Security');
        $this->_CI_Security=new CI_Security;
        return $this->_CI_Security;
    }
    
    private function getHtmlPurifier()
    {
        if($this->_purifier!==null)
            return $this->_purifier;
        $this->_purifier=new CHtmlPurifier;
        if(file_exists($file=Yii::getPathOfAlias('application').DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'htmlpurifier.php'))
            $this->_purifier->options=include($file);
        return $this->_purifier;
    }


}