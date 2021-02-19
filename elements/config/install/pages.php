<?php

defined('C5_EXECUTE') or die('Access Denied.');

/** @var array $data */

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<concrete5-cif version="1.0">
    <?php
    // Should a News index page be added?
    if (isset($data['addNewsPage'])): ?>
        <pages>
            <page name="News" path="/news" filename="" pagetype="news_index"
                  template="<?php echo $data['niPageTemplate']; ?>" description="">
            </page>
        </pages>
    <?php
    endif;
    ?>
</concrete5-cif>
