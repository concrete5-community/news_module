<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var array $data */

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<concrete5-cif version="1.0">
    <?php
    // Only add the feed if the website isn't multilingual
    // Otherwise the feeds need to be manually created
    if (!isset($data['isMultilingual'])): ?>
        <pagefeeds>
            <feed>
                <parent>{ccm:export:page:/news}</parent>
                <title>News</title>
                <description>News</description>
                <handle>news</handle>
                <contenttype type="description"/>
            </feed>
        </pagefeeds>
        <?php
    endif;
    ?>
</concrete5-cif>
