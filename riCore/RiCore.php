<?php
namespace plugins\riCore;

use plugins\riCore\Event;
use plugins\riCore\PluginCore;
use plugins\riPlugin\Plugin;


class RiCore extends PluginCore{
	public function init(){
        Plugin::get('dispatcher')->addListener(\plugins\riCore\Events::onPageEnd, array($this, 'onPageEnd'));
	}	
	
	public function onPageEnd(Event $event)
    {
        Plugin::get('templating.holder')->processHolders();

        $content = &$event->getContent();
        Plugin::get('templating.holder')->injectHolders($content);
        // extend here the functionality of the core
        // ...
    }
}