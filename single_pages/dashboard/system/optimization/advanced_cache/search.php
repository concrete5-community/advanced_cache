<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Url;

/** @var array $items */
?>

<?php
if (count($items) === 0) {
    ?>
    <h3 style="margin-top: 0;"><?php echo t('Instructions') ?></h3>
    <ul style="margin: 20px 0;">
        <li><?php echo t('Search your theme files of hardcoded blocks or code you want to cache.'); ?></li>
        <li><?php echo t('Use the Advanced Cache wrapper to cache the output.'); ?></li>
        <li><?php echo t('Refresh this page to see all cached items.'); ?></li>
    </ul>

    <hr>

    <small class="text-muted"><i><?php echo t('Example code to cache breadcrumbs'); ?></i></small>
    <textarea class="form-control" rows="14" style="font-size: .8em">
$bt = BlockType::getByHandle('autonav');
$bt->controller->orderBy = 'display_asc';
$bt->controller->displaySubPages = 'relevant_breadcrumb';
$bt->controller->displaySubPageLevels = 'all';
$bt->controller->displayPagesIncludeSelf = 1;

/** @var \A3020\AdvancedCache\Cacher $cacher */
$cacher = \A3020\AdvancedCache\Facade\AdvancedCache::make('breadcrumbs');
$cacher
    ->settings()
    ->setExpiresIn(3600);
    ->setDiffersPerPage();

echo $cacher->renderBlock($bt, 'breadcrumb');</textarea>
    <?php
    return;
}
?>

<div class="ccm-dashboard-header-buttons">
    <a class="btn btn-default launch-tooltip" data-placement="bottom"
       href="<?php echo $this->action('removeAll'); ?>"
       title="<?php echo t("Remove all items from the config. This is totally safe, as items will be recreated automatically if you still have them referenced in the code."); ?>"
    >
        <?php echo t('Remove all'); ?>
    </a>

    <a class="btn btn-primary launch-tooltip" data-placement="bottom"
       href="<?php echo $this->action('flushAll'); ?>"
       title="<?php echo t("Clear the cache for all items."); ?>"
    >
        <?php echo t('Flush all'); ?>
    </a>
</div>

<div class="ccm-dashboard-content-inner">
    <table class="table">
        <thead>
            <tr>
                <th><?php echo t('Handle'); ?></th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <?php
        foreach ($items as $handle => $item) {
            ?>
            <tr>
                <td>
                    <?php
                    echo h($handle);
                    ?>

                    <a class="launch-tooltip" data-placement="bottom" style="margin-left: 5px;"
                       href="<?php echo $this->action('remove', $handle); ?>"
                       title="<?php echo t("Remove this item from the config. If you still have references in the code, the item will automatically be recreated.") ?>"
                    >
                        <i class="fa fa-close"></i>
                    </a>
                </td>
                <td style="text-align: right;">
                    <a class="btn btn-xs btn-primary launch-tooltip" data-placement="bottom"
                       href="<?php echo $this->action('flush', $handle); ?>"
                       title="<?php echo t("Clear the cache for this specific item only."); ?>"
                    >
                        <?php echo t('Flush cache'); ?>
                    </a>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
