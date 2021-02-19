<?php

namespace A3020\NewsModule\Installer;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Page\Feed;
use Concrete\Core\Page\Template;
use Concrete\Core\Page\Theme\File;
use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\Page\Type\Type;

class Service implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var \Concrete\Core\Database\Connection\Connection
     */
    private $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    /**
     * Returns true if there are more than 1 locales installed
     *
     * @return bool
     */
    public function isMultilingual()
    {
        /** @var \Concrete\Core\Entity\Site\Site $site */
        $site = $this->app->make('site')
            ->getActiveSiteForEditing();

        return count($site->getLocales()) > 1;
    }

    /**
     * @return array
     */
    public function getPageTemplateOptions()
    {
        $options = [];

        /** @var \Concrete\Core\Entity\Page\Template $template */
        foreach(Template::getList() as $template) {
            $options[$template->getPageTemplateHandle()] = $template->getPageTemplateDisplayName();
        }

        return $options;
    }

    public function getAreaOptions()
    {
        $handles = [];

        foreach (array_column($this->db
            ->fetchAll("SELECT DISTINCT(arHandle) as handle
                FROM Areas 
                WHERE arHandle NOT LIKE '% : %' ORDER BY arHandle"
        ), 'handle') as $handle) {
            $handles[$handle] = $handle;
        }

        return $handles;
    }

    /**
     * @return \Concrete\Core\Page\Theme\File[]
     */
    public function getNewPageTemplates()
    {
        /** @var \Concrete\Core\Page\Theme\Theme $theme */
        $theme = Theme::getByID($this->getThemeId());

        $newTemplates = [];

        /** @var \Concrete\Core\Page\Theme\File $file */
        foreach ($theme->getFilesInTheme() as $file) {
            if ($file->getType() === File::TFTYPE_PAGE_TEMPLATE_NEW) {
                $newTemplates[] = $file;
            }
        }

        return $newTemplates;
    }

    /**
     * @return int
     */
    public function getThemeId()
    {
        /** @var \Concrete\Core\Entity\Site\Site $site */
        $site = $this->app->make('site')->getSite();

        return (int) $site->getThemeID();
    }

    /**
     * @return array[][
     *   'type' => string
     *   'name' => string
     * ]
     */
    public function getFormerNewsComponents()
    {
        $components = [];

        // Page Types
        foreach ([
            'news_index',
            'news_article',
        ] as $handle) {
            $type = Type::getByHandle($handle);
            if ($type) {
                $components[] = [
                    'type' => t('Page Type'),
                    'name' => $type->getPageTypeName(),
                ];
            }
        }

        // Attributes
        foreach ([
            'news_categories',
         ] as $handle) {
            /** @var \Concrete\Core\Entity\Attribute\Key\PageKey $attribute */
            $attribute = CollectionKey::getByHandle($handle);
            if ($attribute) {
                $components[] = [
                    'type' => t('Attribute Key'),
                    'name' => $attribute->getAttributeKeyName(),
                ];
            }
        }

        /** @var \Concrete\Core\Entity\Page\Feed $feed */
        $feed = Feed::getByHandle('news');
        if ($feed) {
            $components[] = [
                'type' => t('Feed'),
                'name' => $feed->getHandle(),
            ];
        }

        return $components;
    }
}
