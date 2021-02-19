<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Application;
use A3020\NewsModule\Installer\Service;
use Concrete\Core\Support\Facade\Url;

$app = Application::getFacadeApplication();
$form = $app->make('helper/form');

// Container doesn't load classes yet
require_once __DIR__ .'/../../src/NewsModule/Installer/Service.php';

/** @var Service $service **/
$service = $app->make(Service::class);

$areaTooltipHelp = t('Select in which area you want the block to be installed.') . ' ' .
    t('Is an area missing from the template you selected? Please browse to a page first that has this page template to make sure the Areas are available in the database.') . ' '.
    t('Unfortunately there is not an easy way to grab all available areas from the page template files.');
?>

<?php
if ($files = $service->getNewPageTemplates()) {
    ?>
    <div class="alert alert-warning">
        <?php
        echo t('It seems your theme has uninstalled Page Templates. Would you like to inspect / install them first?');
        ?>

        <ul>
            <?php
            foreach ($files as $file) {
                echo '<li>' . e($file->getHandle()) . '</li>';
            }
            ?>
        </ul>
        <br>

        <a class="btn btn-sm btn-primary" href="<?php echo Url::to('/dashboard/pages/themes/inspect/' . $service->getThemeId()) ?>">
            <?php echo t('Inspect theme'); ?>
        </a>
    </div>
    <?php
}

if ($components = $service->getFormerNewsComponents()) {
    ?>
    <div class="alert alert-warning">
        <?php
        echo t('It seems you have news components installed already. Please remove the following component(s) first to prevent collisions:');
        ?>

        <ul>
            <?php
            foreach ($components as $component) {
                echo '<li>' . $component['type'] . ' - ' . e($component['name']) . '</li>';
            }
            ?>
        </ul>
        <br>
    </div>
    <?php
}
?>

<fieldset>
    <legend><?php echo t('General'); ?></legend>

    <div class="form-group">
        <div class="checkbox">
            <label>
                <?php
                echo $form->checkbox('isMultilingual', 1, $service->isMultilingual());
                ?>

                <?php echo t('I have a multilingual website or plan to have one'); ?>
            </label>
        </div>

        <div class="checkbox">
            <label>
                <?php
                echo $form->checkbox('addNewsPage', 1, true);
                ?>

                <?php echo t('Add a News index page'); ?>
            </label>
        </div>
    </div>
</fieldset>

<fieldset style="background: rgba(6,255,136,0.05)">
    <h3><?php echo t('News Index - Defaults'); ?></h3>
    <br>

    <div class="form-group">
        <label class="control-label launch-tooltip"
               title="<?php echo t('Go to the %s page to add or inspect page templates.', t('Page Templates')) ?>">
            <?php
            echo t('Which Page Template would you like to use?');
            ?>
        </label>

        <?php
        echo $form->select('niPageTemplate', $service->getPageTemplateOptions(), 'right_sidebar');
        ?>
    </div>

    <hr>

    <div class="well">
        <div class="form-group">
            <div class="checkbox">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("Add a 'Page Title' block above the page list.") ?>">
                    <?php
                    echo $form->checkbox('niPageTitle', 1, true);
                    ?>

                    <?php
                    echo t("Add title block");
                    ?>
                </label>
            </div>
        </div>

        <div class="form-group ni-title-area">
            <label class="control-label launch-tooltip"
                   title="<?php echo $areaTooltipHelp ?>"
                   for="niTitleArea">
                <?php
                echo $form->label('niTitleArea', t("Add to"));
                ?>
            </label>

            <?php
            $hasPageHeaderArea = in_array('Page Header', $service->getAreaOptions());
            echo $form->select('niTitleArea', $service->getAreaOptions(), $hasPageHeaderArea ? 'Page Header' : 'Main');
            ?>
        </div>
    </div>

    <div class="well">
        <div class="form-group">
            <div class="checkbox">
                <label class="control-label launch-tooltip"
                   title="<?php echo t("Add a 'Page List' block that shows news articles.") ?>">
                    <?php
                    echo $form->checkbox('niNewsList', 1, true);
                    ?>

                    <?php
                    echo t("Add news list block");
                    ?>
                </label>
            </div>
        </div>

        <div class="form-group ni-news-list-area">
            <label class="control-label launch-tooltip"
                   title="<?php echo $areaTooltipHelp ?>"
                   for="niNewsListArea">
                <?php
                echo $form->label('niNewsListArea', t("Add to"));
                ?>
            </label>

            <?php
            echo $form->select('niNewsListArea', $service->getAreaOptions(), 'Main');
            ?>
        </div>
    </div>

    <div class="well">
        <div class="form-group">
            <div class="checkbox">
                <label class="control-label launch-tooltip"
                   title="<?php echo t("Add a 'Topics' block that shows the news categories. When a category is clicked, only articles from that category are shown.") ?>">
                    <?php
                    echo $form->checkbox('niCategories', 1, true);
                    ?>

                    <?php
                    echo t("Add categories block");
                    ?>
                </label>
            </div>
        </div>

        <div class="form-group ni-categories">
            <label class="control-label launch-tooltip"
                   title="<?php echo t("Each category will be added to the 'News' topic tree.") ?>">
                <?php
                echo t('Add the following news categories');
                ?>
            </label>

            <?php
            echo $form->textarea('categories', null, [
                'placeholder' => t('One category per line'),
            ]);
            ?>
        </div>

        <div class="form-group ni-categories-area">
            <label class="control-label launch-tooltip"
                   title="<?php echo $areaTooltipHelp ?>"
                   for="niCategoriesArea">
                <?php
                echo $form->label('niCategoriesArea', t("Add to"));
                ?>
            </label>

            <?php
            $hasSidebarArea = in_array('Sidebar', $service->getAreaOptions());
            echo $form->select('niCategoriesArea', $service->getAreaOptions(), $hasSidebarArea ? 'Sidebar' : 'Main');
            ?>
        </div>
    </div>
</fieldset>

<fieldset style="background: rgba(255,153,51,0.05)">
    <h3><?php echo t('News Article - Defaults'); ?></h3>
    <br>

    <div class="form-group">
        <label class="control-label launch-tooltip"
               title="<?php echo t('Go to the %s page to add or inspect page templates.', t('Page Templates')) ?>">
            <?php
            echo t('Which Page Template would you like to use?');
            ?>
        </label>

        <?php
        echo $form->select('naPageTemplate', $service->getPageTemplateOptions(), 'right_sidebar');
        ?>
    </div>

    <hr>

    <div class="well">
        <div class="form-group">
            <div class="checkbox">
                <label class="control-label launch-tooltip"
                   title="<?php echo t('Add a block to show the name of the news article.') ?>">
                    <?php
                    echo $form->checkbox('naPageTitle', 1, true);
                    ?>

                    <?php
                    echo t('Add title block');
                    ?>
                </label>
            </div>
        </div>

        <div class="form-group na-title-area">
            <label class="control-label launch-tooltip"
                   title="<?php echo $areaTooltipHelp ?>"
                   for="naTitleArea">
                <?php
                echo $form->label('naTitleArea', t("Add to"));
                ?>
            </label>

            <?php
            $hasPageHeaderArea = in_array('Page Header', $service->getAreaOptions());
            echo $form->select('naTitleArea', $service->getAreaOptions(), $hasPageHeaderArea ? 'Page Header' : 'Main');
            ?>
        </div>
    </div>

    <div class="well">
        <div class="form-group">
            <div class="checkbox">
                <label class="control-label launch-tooltip"
                   title="<?php echo t('Add a block to show the description of the news article.') ?>">
                    <?php
                    echo $form->checkbox('naTextIntro', 1, true);
                    ?>

                    <?php
                    echo t("Add text intro block");
                    ?>
                </label>
            </div>

            <div class="checkbox">
                <label class="control-label launch-tooltip"
                       title="<?php echo t('Add a content block to show the news article. This block is mandatory.') ?>">
                    <?php
                    echo $form->checkbox('naNewsContent', 1, true, [
                        'readonly' => 'readonly',
                        'disabled' => 'disabled',
                    ]);
                    ?>

                    <?php
                    echo t('Add content block');
                    ?>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label launch-tooltip"
                   title="<?php echo $areaTooltipHelp ?>"
                   for="naContentArea">
                <?php
                echo $form->label('naContentArea', t("Add to"));
                ?>
            </label>

            <?php
            echo $form->select('naContentArea', $service->getAreaOptions(), 'Main');
            ?>
        </div>
    </div>

    <div class="well">
        <div class="form-group">
            <div class="checkbox">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("Add a 'Tags' block. When clicked on a tag, only news articles with that tag are shown.") ?>">
                    <?php
                    echo $form->checkbox('naTags', 1, true);
                    ?>

                    <?php
                    echo t('Add tags block');
                    ?>
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label launch-tooltip"
                   title="<?php echo $areaTooltipHelp ?>"
                   for="naTagsArea">
                <?php
                echo $form->label('naTagsArea', t('Add to'));
                ?>
            </label>

            <?php
            echo $form->select('naTagsArea', $service->getAreaOptions(), 'Main');
            ?>
        </div>
    </div>

    <div class="well">
        <div class="form-group">
            <div class="checkbox">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("Add a 'Next & Previous' navigation block to automatically link to other news articles.") ?>">
                    <?php
                    echo $form->checkbox('naNavigation', 1, true);
                    ?>

                    <?php
                    echo t('Add next/previous block');
                    ?>
                </label>
            </div>
        </div>

        <div class="form-group na-navigation-area">
            <label class="control-label launch-tooltip"
                   title="<?php echo $areaTooltipHelp ?>"
                   for="naNavigationArea">
                <?php
                echo $form->label('naNavigationArea', t("Add to"));
                ?>
            </label>

            <?php
            $hasSidebarArea = in_array('Sidebar', $service->getAreaOptions());
            echo $form->select('naNavigationArea', $service->getAreaOptions(), $hasSidebarArea ? 'Sidebar' : 'Main');
            ?>
        </div>
    </div>

    <div class="well">
        <div class="form-group">
            <div class="checkbox">
                <label class="control-label launch-tooltip"
                       title="<?php echo t("Add a '%s' block to easily share a news article to a social network.", t('Share This Page')) ?>">
                    <?php
                    echo $form->checkbox('naShare', 1, true);
                    ?>

                    <?php
                    echo t("Add share page block");
                    ?>
                </label>
            </div>
        </div>

        <div class="form-group na-share-area">
            <label class="control-label launch-tooltip"
                   title="<?php echo $areaTooltipHelp ?>"
                   for="naShareArea">
                <?php
                echo $form->label('naShareArea', t('Add to'));
                ?>
            </label>

            <?php
            $hasSidebarArea = in_array('Sidebar', $service->getAreaOptions());
            echo $form->select('naShareArea', $service->getAreaOptions(), $hasSidebarArea ? 'Sidebar' : 'Main');
            ?>
        </div>
    </div>

    <div class="well">
        <div class="form-group">
            <div class="checkbox">
                <label class="control-label launch-tooltip"
                       title="<?php echo t('The conversation blocks allows visitors to leave a comment.') ?>">
                    <?php
                    echo $form->checkbox('naConversation', 1, true);
                    ?>

                    <?php
                    echo t('Add conversation block');
                    ?>
                </label>
            </div>
        </div>

        <div class="form-group na-conversation-area">
            <label class="control-label launch-tooltip"
                   title="<?php echo $areaTooltipHelp ?>"
                   for="naConversationArea">
                <?php
                echo $form->label('naConversationArea', t("Add to"));
                ?>
            </label>

            <?php
            $hasFooterArea = in_array('Page Footer', $service->getAreaOptions());
            echo $form->select('naConversationArea', $service->getAreaOptions(), $hasFooterArea ? 'Page Footer' : 'Main');
            ?>
        </div>
    </div>
</fieldset>

<script>
$(document).ready(function() {
    // News Article
    $('#naPageTitle').change(function() {
       $('.na-title-area').toggle($(this).is(':checked'));
    });

    $('#naNavigation').change(function() {
        $('.na-navigation-area').toggle($(this).is(':checked'));
    });

    $('#naShare').change(function() {
        $('.na-share-area').toggle($(this).is(':checked'));
    });

    $('#naConversation').change(function() {
        $('.na-conversation-area').toggle($(this).is(':checked'));
    });

    // News Index
    $('#niPageTitle').change(function() {
        $('.ni-title-area').toggle($(this).is(':checked'));
    });

    $('#niNewsList').change(function() {
        $('.ni-news-list-area').toggle($(this).is(':checked'));
    });

    $('#niCategories').change(function() {
        $('.ni-categories, .ni-categories-area').toggle($(this).is(':checked'));
    });
});
</script>
