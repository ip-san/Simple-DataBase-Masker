<?php
/**
 * TODO エラー時に呼ばれるコントローラー
 *
 * @author Ippei Sesoko
 */
class ErrorController extends BaseController
{

    public function page404()
    {
        require_once(dirname(__FILE__) . '/../View/Layout/page404.php');
    }
}
