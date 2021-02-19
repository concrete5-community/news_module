<?php

namespace A3020\NewsModule\Installer;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Cache\Cache;
use Exception;
use Throwable;

class Importer implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var ContentImporter
     */
    protected $contentImporter;

    /**
     * @var FileLoader
     */
    private $fileLoader;

    public function __construct(ContentImporter $contentImporter, FileLoader $fileLoader)
    {
        $this->contentImporter = $contentImporter;
        $this->fileLoader = $fileLoader;
    }

    /**
     * Load and import an XML based on settings from POST
     *
     * @param array $data
     *
     * @throws Exception
     */
    public function importWith($data)
    {
        Cache::disableAll();

        // The order here is important and has an impact on page type defaults
        $this->import([
            'trees',
            'attributes', // the topics custom attribute relies on the /News Topic / tree
            'page_types/news_index',
            'page_types/news_article',
            'pages',
        ], $data);
    }

    /**
     * Import different files
     *
     * Some XML files are separated, because e.g. page type defaults
     * does not work if the <pages> are included in the main XML export.
     *
     * @param array $files
     * @param array $data
     *
     * @throws Exception
     */
    private function import($files, $data)
    {
        $args = [
            'data' => $data,
        ];

        foreach ($files as $file) {
            try {
                $xml = $this->fileLoader->load($file, $args);
            } catch (Exception $e) {
                throw new Exception(
                    t('Could not load file: %s', $file,
                        $e->getMessage() . ' ' . $e->getTraceAsString())
                );
            }

            try {
                $this->contentImporter->importContentString($xml);
            } catch (Exception $e) {
                throw new Exception(
                    t("Import of file '%s' failed: %s. XML: %s",
                        $file,
                        $e->getMessage() . ' ' . $e->getTraceAsString(),
                        htmlentities($xml)
                    )
                );
            }
        }
    }
}
