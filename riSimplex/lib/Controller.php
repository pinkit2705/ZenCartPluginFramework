<?php
namespace plugins\riSimplex;

use plugins\riPlugin\Plugin;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Controller extends ContainerAware{
 	protected $response, $view, $router;
	
	public function __construct(){
		$this->response = new Response();
		$this->view = Plugin::get('view');
		$this->router = $this->view->get('router');
		$this->setContainer(Plugin::getContainer());
	}

    public function beforeAction(Event $event){
        $is_permitted = true;
        if($event->getRequest()->get('role', '') == 'isAdmin'){
            if(!defined('IS_ADMIN_FLAG') || !IS_ADMIN_FLAG)
                $is_permitted = false;
        }else{
            $route_parts = explode('_', $event->getRequest()->get('_route'));
            if($route_parts[1] == 'admin' && !IS_ADMIN_FLAG){
                $is_permitted = false;
            }
        }
        if(!$is_permitted)
            die(ri('You do not have permission to access an admin route from here!'));
    }

	public function exceptionAction(){
		//
	}
	
 	/**
     * Renders a view.
     *
     * @param string   $view The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response A response instance
     *
     * @return Response A Response instance
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
    	if($response == null) $response = $this->response;
        return $this->view->renderResponse($view, $parameters, $response);
    }

    public function renderJson($data, Response $response = null){
        if($response == null) $response = $this->response;
        $response->setContent(json_encode($data));
        $response->send();
        exit();
    }
    
	/**
     * Generates a URL from the given parameters.
     *
     * @param string  $route      The name of the route
     * @param mixed   $parameters An array of parameters
     * @param Boolean $absolute   Whether to generate an absolute URL
     *
     * @return string The generated URL
     */
    public function generateUrl($route, $parameters = array(), $absolute = false)
    {
        return $this->router->generate($route, $parameters, $absolute);
    }
    
	/**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string  $url The URL to redirect to
     * @param integer $status The status code to use for the Response
     *
     * @return RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }
}