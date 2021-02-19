<?php
defined('C5_EXECUTE') or die('Access Denied.');
?>

<div class="ccm-dashboard-content-inner">
    <form method="post" action="<?php echo $this->action('save'); ?>">
        <?php
        /** @var $token \Concrete\Core\Validation\CSRF\Token */
        echo $token->output('a3020.advanced_cache.settings');
        ?>

        <div class="form-group">
            <label class="control-label launch-tooltip"
               title="<?php echo t("If disabled, nothing will be cached by %s.", t('Advanced Cache')) ?>"
               for="enabled">
                <?php
                /** @var bool $enabled */
                echo $form->checkbox('enabled', 1, $enabled);
                ?>
                <?php echo t('Enable %s', t('Advanced Cache')); ?>
            </label>
        </div>

        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <button class="pull-right btn btn-primary" type="submit">
                    <?php echo t('Save') ?>
                </button>
            </div>
        </div>
    </form>
</div>
