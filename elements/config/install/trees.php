<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var array $data */

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<concrete5-cif version="1.0">
    <trees>
        <tree type="topic" name="News">
            <?php
            // Convert lines into an array
            $topics = explode("\n", str_replace("\r", '', $data['categories']));

            // Remove empty elements and spaces
            $topics = array_filter(array_map('trim', $topics));

            foreach ($topics as $topic) {
                echo '<topic name="' . h($topic) . '"/>';
            }
            ?>
        </tree>
    </trees>
</concrete5-cif>
