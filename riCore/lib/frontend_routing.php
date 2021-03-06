<?php
/**
 * Created by RubikIntegration Team.
 * User: vunguyen
 * Date: 6/25/12
 * Time: 3:53 PM
 * Question? Come to our website at http://rubikintegration.com
 */
use plugins\riPlugin\Plugin;
if (!is_dir(DIR_WS_MODULES .  'pages/' . $current_page)){

    Plugin::load(array('riSimplex'));

    $container->setParameter('request', $request);

    try{
        global $response, $current_page, $current_page_base;
        $response = Plugin::get('riSimplex.Framework')->customHandle($current_page, $request);
        if($response instanceof \Symfony\Component\HttpFoundation\RedirectResponse)
            $response->send();
        $_GET['main_page'] = $current_page = $current_page_base = 'ri';

// eof ri: cjloader
    }catch (\Exception $e){
        zen_redirect(zen_href_link('page_not_found'));
    }

}