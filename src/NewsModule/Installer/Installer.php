<?php

namespace A3020\NewsModule\Installer;

use A3020\NewsModule\Import\Importer;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Page;

class Installer
{
    /**
     * @var \Concrete\Core\Http\Request
     */
    private $request;

    /**
     * @var \A3020\NewsModule\Import\Importer
     */
    private $importer;

    /**
     * @var Connection
     */
    private $db;

    public function __construct(Request $request, Importer $importer, Connection $db)
    {
        $this->request = $request;
        $this->importer = $importer;
        $this->db = $db;
    }

    /**
     * @throws \Exception
     */
    public function install()
    {
        $this->importer->importWith(
            $this->request->request->all()
        );

        $this->fixNewsList();
    }

    /**
     * Detach news list from page type defaults
     *
     * - Detach news list
     * - Make sure the 'Beneath this page' option is selected
     */
    private function fixNewsList()
    {
        /** @var Page $page */
        $page = Page::getByPath('/news');
        if (!is_object($page) || $page->isError()) {
            return;
        }

        // getBlocks is part of the Collection class
        $blocks = $page->getBlocks($this->request->request->get('niNewsListArea'));

        /** @var \Concrete\Core\Block\Block $block */
        foreach ($blocks as $block) {
            if ($block->getBlockTypeHandle() == 'page_list') {
                // We 'detach' it from the page type defaults
                // by duplicating it and then removing the aliased block.
                $newBlock = $block->duplicate($page);
                $block->deleteBlock();

                // Make sure the block record is refreshed, otherwise the Edit dialog will show outdated info.
                // There is no API method for this yet. See https://github.com/concrete5/concrete5/pull/6851.
                $this->db->executeQuery('UPDATE Blocks SET btCachedBlockRecord = NULL WHERE bID = ?', [
                    $newBlock->getBlockID(),
                ]);

                // Make sure the parentID is updated with the /news collection id.
                // This will make sure the option "Beneath this page" is selected in the page list
                $this->db
                    ->executeQuery('UPDATE btPageList SET cThis = 1, cParentID = ? WHERE bID = ?', [
                        $page->getCollectionID(),
                        $newBlock->getBlockID(),
                    ]);
            }
        }
    }
}
