<?php

namespace A3020\NewsModule\Listener;

use Concrete\Core\Database\Connection\Connection;
use Exception;
use Psr\Log\LoggerInterface;

class PageTypePublish
{
    /**
     * @var \Concrete\Core\Database\Connection\Connection
     */
    private $db;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(Connection $db, LoggerInterface $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    /**
     * @param \Concrete\Core\Page\Type\Event $event
     */
    public function handle(\Concrete\Core\Page\Type\Event $event)
    {
        /** @var \Concrete\Core\Page\Type\Type $pageType */
        $pageType = $event->getPageTypeObject();

        // Only continue if the page is a news article
        if (!$pageType->getPageTypeHandle() === 'news_article') {
            return;
        }

        try {
            $this->fixTagsBlock(
                $event->getPageObject()
            );
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * The tags should link to a parent page
     *
     * Because the website might be multilingual
     * the parent id can't be set in page types.
     *
     * @param \Concrete\Core\Page\Page $page
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    private function fixTagsBlock(\Concrete\Core\Page\Page $page)
    {
        // Get *all* blocks on the page (at this point we don't know the area name)
        // getBlocks is part of the Collection class
        $blocks = $page->getBlocks();

        /** @var \Concrete\Core\Block\Block $block */
        foreach ($blocks as $block) {
            if ($block->getBlockTypeHandle() !== 'tags') {
                continue;
            }

            // We 'detach' it from the page type defaults
            // by duplicating it and then removing the aliased block.
            $newBlock = $block->duplicate($page);
            $block->deleteBlock();

            // Make sure the block record is refreshed, otherwise the Edit dialog will show outdated info.
            // There is no API method for this yet. See https://github.com/concrete5/concrete5/pull/6851.
            $this->db->executeQuery('UPDATE Blocks SET btCachedBlockRecord = NULL WHERE bID = ?', [
                $newBlock->getBlockID(),
            ]);

            // Make sure the tags blocks points to the parent page.
            // E.g. /de/news/test-news should point to /de/news
            $this->db
                ->executeQuery('UPDATE btTags SET targetCID = ? WHERE bID = ?', [
                    $page->getCollectionParentID(),
                    $newBlock->getBlockID(),
                ]);
        }
    }
}
