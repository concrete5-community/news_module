<?php

namespace A3020\NewsModule\Import;

use Concrete\Core\View\View;

class FileLoader
{
    /**
     * @var \Concrete\Core\View\View
     */
    private $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * Load a particular XML file
     *
     * @param string $file (without extension, e.g. page_types)
     * @param array $data
     *
     * @return string (XML data)
     *
     * @throws \Exception
     */
    public function load($file, $data)
    {
        ob_start();

        $this->view->element('config/install/' . $file, $data, 'news_module');

        return ob_get_clean();
    }
}