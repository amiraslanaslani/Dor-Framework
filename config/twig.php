<?php
/*
 * Kernel::$twig is twig variable
 */

\Dor\Kernel::$twig->addGlobal('base_url',$config->getContent()['app']['base_url']);