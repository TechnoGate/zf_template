<?php

/**
 * Technogate
 *
 * @package Paginator
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */
class Helper_Paginator extends Zend_Controller_Action_Helper_Abstract {

  public function __construct() {

  }

  public function preDispatch() {

  }

  public function paginateData($data, $emptyMessage = null) {

    /** Get the request */
    $request = $this->getRequest();

    /** Get initial params */
    $paginator_params = $request->getParams();
    foreach (array('controller', 'action', 'module', 'p') as $param)
      unset($paginator_params[$param]);

    /** Get the page number */
    $pageNumber = $request->getParam('p');
    if (empty($pageNumber))
      $pageNumber = 1;

    /** Set the paginator */
    if (!empty($data) && is_array($data) && count($data) > 0) {
      $paginator = Zend_Paginator::factory($data);
      $paginator->setItemCountPerPage(Zend_Paginator::getDefaultItemCountPerPage());
      $paginator->setDefaultScrollingStyle('Sliding');
      Zend_View_Helper_PaginationControl::setDefaultViewPartial('pagin.phtml');
      $paginator->setCurrentPageNumber($pageNumber);
    } else {
      $paginator = $emptyMessage;
    }

    /** Send it to the view */
    $this->_getview()->paginator = $paginator;
    $this->_getview()->paginator_params = $paginator_params;
  }

  /**
   * This function is used to direct a bare request to the paginateData function
   */
  public function direct($data, $emptyMessage = null) {

    return $this->paginateData($data, $emptyMessage);
  }

  /**
   * This function return the Zend_View object
   *
   * @return Zend_View
   */
  protected function _getView() {

    return Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;
  }

}

?>