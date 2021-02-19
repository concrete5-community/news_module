<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Page\Page;
use Concrete\Core\Support\Facade\Package;

/** @var \Concrete\Core\Entity\Package $package */
$package = Package::getByHandle('news_module');
?>
<p><?php echo t('Congratulations, the add-on has been installed!'); ?></p>
<br>

<p>
    <?php echo t('Composer can be used to add new news articles.'); ?>
</p>
<br>

<?php
$newsPage = Page::getByPath('/news');
if (is_object($newsPage)) {
    echo '<a class="btn btn-primary" href="' . $newsPage->getCollectionLink() . '">' . t('Visit News') . '</a>';
}
