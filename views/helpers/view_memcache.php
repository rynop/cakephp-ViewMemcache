<?php

class ViewMemcacheHelper extends Helper
{
    function afterLayout() {
        if (Configure::read('ViewMemcache.Disable')) {
            return true;
        }
        
        $view = & ClassRegistry::getObject('view');
	    	 
        if (is_object($view) && array_key_exists('docache', $view->viewVars) && $view->viewVars['docache'] === true) {
            $timeout = Configure::read('ViewMemcache.timeout');
 
            if (array_key_exists('docachetimeout', $view->viewVars)) {
                $timeout = $view->viewVars['docachetimeout'];
            }
 	
			$cache_footer = '';
            if (!array_key_exists('nocachefooter', $view->viewVars)) {
                $cache_footer = "\n<!-- galeCached " . date('r') . ' -->';
            }
            
            if ($timeout) {
                Cache::set(array('duration' => $timeout));
            }
            
            Cache::write($view->here, $view->output . $cache_footer, 'view_memcache');
        }
 
        return true;
    }
}