<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Login\Model\prSession;
use Zend\View\Helper\ServerUrl;
use Zend\View\Renderer\RendererInterface;
use Zend\View\Helper\Url;
use Zend\View\Helper\BasePath;

class ApplicationControllerAction extends AbstractActionController
{
    var $_client = null;
    var $_request = null;
    var $_pagenumleft = 1;
    public function __construct()
    {
        $app_path_replace1 = str_replace("\\", ".",APPLICATION_PATH );
        $app_path_replace2 = str_replace("/", ".",$app_path_replace1);
        $app_path_arr = explode(".",$app_path_replace2);
        $count_path = count($app_path_arr);
        $uri = $app_path_arr[$count_path-1];
        define('URL_BASE', "/".$uri."/");
        define('URL_MEDIA_COMPANY_PROFILE',  URL_BASE.'public/media/companyprofiles/');
        define('URL_MEDIA_PORTFOLIO',  URL_BASE.'public/media/portfolio/');
        define('URL_MEDIA_VIDEO',  URL_BASE.'public/media/video/');
        define('URL_MEDIA_PHOTO',  URL_BASE.'public/media/photo/');

        define('URL_THEMES', URL_BASE. "public/");

        define('URL_MEDIA', URL_BASE. "public/media/");
        define('URL_MEDIA_PROFILE', URL_MEDIA. "profiles/");
        define('URL_MEDIA_TEMP', URL_MEDIA. "temp/");
        define('URL_MEDIA_SCHOOLLOGO', URL_MEDIA. "schoollogos/");
        define('URL_MEDIA_PROFILE_NOAVATAR', "none.png");
        define('DIR_BASE',  realpath(APPLICATION_PATH . '/../'));
        define('DIR_MEDIA_COMPANY_PROFILE',  DIR_BASE.'/public/media/companyprofiles/');
        define('DIR_MEDIA_PORTFOLIO',  DIR_BASE.'/public/media/portfolio/');
        define('DIR_MEDIA_VIDEO',  DIR_BASE.'/public/media/video/');
        define('DIR_MEDIA_PHOTO',  DIR_BASE.'/public/media/photo/');

        define('DIR_MEDIA',  DIR_BASE.'/public/media/');
        define('DIR_MEDIA_TEMP',  DIR_MEDIA.'temp/');

        define('LIMIT_PAGE_LEFT', 10);
        define('LIMIT_PAGE_NUMBER_LEFT', 3);

        define('USER_TYPE_COMPANY', 1);
        define('USER_TYPE_CANDIDATE', 2);

        $this->_client = prSession::getSession(prSession::SESSION_USER);
        $this->layout()->setVariables(array('userLogin'=>$this->_client));
    }

}

