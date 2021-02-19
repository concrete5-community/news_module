<?php

namespace A3020\NewsModule\Installer;

use Concrete\Core\Http\Request;
use Concrete\Core\Page\PageList;

class Uninstaller
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function uninstall()
    {
        if ($this->request->request->get('deleteNewsIndexPages', true)) {
            $this->deleteNewsIndexPages();
        }

        if ($this->request->request->get('deleteNewsArticlePages', true)) {
            $this->deleteNewsArticlePages();
        }
    }

    private function deleteNewsIndexPages()
    {
        $pl = new PageList();
        $pl->filterByPageTypeHandle('news_index');

        /** @var \Concrete\Core\Page\Page $page */
        foreach ($pl->getResults() as $page) {
            $page->delete();
        }
    }

    private function deleteNewsArticlePages()
    {
        $pl = new PageList();
        $pl->filterByPageTypeHandle('news_article');

        /** @var \Concrete\Core\Page\Page $page */
        foreach ($pl->getResults() as $page) {
            $page->delete();
        }
    }
}
