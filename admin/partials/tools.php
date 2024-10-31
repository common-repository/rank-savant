<?php
    // If this file is called directly, abort.
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
?>
<div class="wrap">
    <h1><?php esc_html_e('Rank Savant', 'rank-savant'); ?></h1>
    <div class="card">
        <h2 class="title"><?php esc_html_e('Shortcodes', 'rank-savant'); ?></h2>
        <p><?php esc_html_e('To display the projects use the shortcode:', 'rank-savant'); ?> <em>[ranksavant-integration container=1234]</em></p>
        <p><?php esc_html_e('Find you container id in your Rank Savant account.', 'rank-savant'); ?></p>
        <?php if ($status && is_array($containers) && ! empty($containers)) : ?>
            <p><?php esc_html_e('Alternatively select bellow a container to get the shortcode.', 'rank-savant'); ?></p>
            <select class="ranksavant-select-containers">
                <option value="empty"><?php esc_html_e('Select a container to get a shortcode', 'rank-savant'); ?></option>
                <?php
                foreach ($containers as $container) :
					$details = maybe_unserialize($container['settings']);
					?>
                    <option value="<?php echo esc_html($container['id']); ?>"><?php echo esc_html($details['internal_title']); ?></option>
                <?php endforeach; ?>
            </select>
            <p class="ranksavant-generated-shortcode"></p>
        <?php endif; ?>
        <?php if($status): ?>
            <form action="" method="post">
                <?php wp_nonce_field('ranksavant-get-containers', 'nonce-ranksavant-containers'); ?>
                <input type="submit" value="<?php esc_html_e('Syncronise containers', 'rank-savant'); ?>" class="button button-primary">
            </form>
        <?php endif; ?>
    </div>
    <div class="card">
        <h2 class="title"><?php esc_html_e('API Key', 'rank-savant'); ?> <span class="ranksavant-badge <?php echo $status ? 'ranksavant-badge-active' : 'ranksavant-badge-inactive'; ?>"><?php echo $status ? esc_html__('Activated', 'rank-savant') : esc_html__('Not Activated', 'rank-savant'); ?></span></h2>
        <form action="" method="post">
            <?php wp_nonce_field('ranksavant-api-key', 'nonce-ranksavant-api-key'); ?>
            <?php if ( ! $status && $reason) : ?>
                <div class="ranksavant-notice"><?php echo esc_html($reason); ?></div>
            <?php endif; ?>
            <p><?php esc_html_e('Find your API Key in your Rank Savant account, under Integrations and paste it bellow.', 'rank-savant'); ?></p>
            <p>
                <label for="ranksavant-api-key"><?php esc_html_e('API Key', 'rank-savant'); ?></label><br>
                <input type="text" value="<?php echo esc_html($api_key); ?>" name="ranksavant-api-key" id="ranksavant-api-key" class="regular-text">
            </p>
            <input type="submit" value="<?php esc_html_e('Save Key', 'rank-savant'); ?>" class="button button-primary">
        </form>
    </div>
    <div class="card">
        <h2 class="title"><?php esc_html_e('Cache', 'rank-savant'); ?></h2>
        <form action="" method="post">
            <?php wp_nonce_field('ranksavant-cache', 'nonce-ranksavant-cache'); ?>
            <p><?php esc_html_e('Ranksavant use', 'rank-savant'); ?> <a href="https://developer.wordpress.org/apis/transients/" target="_blank"><?php esc_html_e('transients', 'rank-savant'); ?></a> <?php esc_html_e('to store the information. The cache is stored for 6 hours, use this to purge cache.', 'rank-savant'); ?></p>
            <input type="submit" value="<?php esc_html_e('Purge Cache', 'rank-savant'); ?>" class="button button-primary">
        </form>
    </div>
</div>