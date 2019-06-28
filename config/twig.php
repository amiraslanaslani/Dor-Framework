<?php
/*
 * Kernel::$twig is twig variable
 */

\Dor\Kernel::$twig->addGlobal('base_url',\Dor\Kernel::$config['app']['base_url']);