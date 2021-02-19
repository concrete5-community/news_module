<?php

namespace A3020\NewsModule\Provider;

use A3020\NewsModule\Listener\PageTypePublish;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;

class ServiceProvider implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    public function register()
    {
        // PS. The EventDispatcher is not injected because it doesn't work in 8.0.0

        // When a page is published a few blocks are detached from page type defaults
        $this->app['director']->addListener('on_page_type_publish', function($event) {
            /** @var \A3020\NewsModule\Listener\PageTypePublish $listener */
            $listener = $this->app->make(PageTypePublish::class);
            $listener->handle($event);
        });
    }
}
