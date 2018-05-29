# d'Or Project
Very simple PHP framework.
This frame work's main idea is based on Symfony framework and laravel Framework.
I use Twig as template engine, Eloquent as ORM and some of Symfony components in d'Or framework.

Note: If there is any feature that may good to exists, then create new issue and tell it to me.

## Directories
- ``/public`` - There is that web server run our programme and any public file is located.
- ``/system`` - There is that our frameworks important files (that make up the framework itself) located.
- ``/system/console`` - There is that console vital files located. This file is should be exists is development time and removed after that.
- ``/src/controllers`` - There is our controllers location that extends from ``AbstractController``.
- ``/src/models`` - There is our models location that extends from ``AbstractModel``.
- ``/stc/view`` - There is our output templates location that rendered with Twig. If we don't use Twig then we can remove this directory.

## Console
There is our console that we can use to do some exciting works!
This console is created with Symfony console component.
You can use console with executing ``console`` file that located at root of repository.
```
~/DorFramework$ php ./console <Your Command>
```

#### Commands
There is some of important commands:
 - You can use ``--help`` or ``-h`` to see all of commands you can use.
 - You can use ``server:run`` command to run built-in server to use in development of your programme.
 - You can use ``controller:add <Controler Name>`` to add new controller.
 - You can use ``model:add <Model Name>`` to add new model.
 
## Controllers
#### Routing
There is you can use annotations to set routing in the system.
There is a example of very very simple controller just to show how you can use annotations to routing:
```php
<?php

namespace Dor\Controller;

use Dor\Util\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route(["/page/{id}", "/post/{id}"])
     */
    public function index($param){
        $body = 'This Article\'s id is ' . $param['id'];
        return $this->getResponse($body);
    }

    /**
     * @Route("/login")
     * @Method(["post","get"])
     */
    public function login($param){
        $body = 'There is a exciting login page that just you can not see that! :D';
        return $this->getResponse($body);
    }

    /**
     * @Route("/hi")
     * @Method("post")
     */
    public function sayHi($param){
        return $this->getResponse("<h1>Hi!</h1>");
    }
}
```

You can use single or set of parameters to pass to ``Route`` or ``Method``.