<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Application;

$app = Application::getFacadeApplication();
$form = $app->make('helper/form');
?>

<div class="form-group">
    <div class="checkbox">
        <label>
            <?php
            echo $form->checkbox('deleteNewsIndexPages', 1, true);
            ?>

            <?php echo t('Delete News Index pages'); ?>
        </label>
    </div>

    <div class="checkbox">
        <label>
            <?php
            echo $form->checkbox('deleteNewsArticlePages', 1, true);
            ?>
            <?php echo t('Delete News Article pages'); ?>
        </label>
    </div>
</div>
