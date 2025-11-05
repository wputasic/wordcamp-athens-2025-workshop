<?php
/**
 * Admin page template
 */

function wc_notes_admin_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('WordCamp Notes Manager', 'wc-notes'); ?></h1>
        
        <div id="wc-notes-app">
            <div class="wc-notes-header">
                <button id="add-note-btn" class="button button-primary">
                    <?php echo esc_html__('Add New Note', 'wc-notes'); ?>
                </button>
                <input type="search" id="search-notes" placeholder="<?php echo esc_attr__('Search notes...', 'wc-notes'); ?>" class="regular-text">
            </div>

            <div id="notes-form" class="wc-notes-form" style="display: none;">
                <h2 id="form-title"><?php echo esc_html__('Add New Note', 'wc-notes'); ?></h2>
                <form id="note-form">
                    <input type="hidden" id="note-id" value="">
                    <table class="form-table">
                        <tr>
                            <th><label for="note-title"><?php echo esc_html__('Title', 'wc-notes'); ?></label></th>
                            <td><input type="text" id="note-title" class="regular-text" required></td>
                        </tr>
                        <tr>
                            <th><label for="note-content"><?php echo esc_html__('Content', 'wc-notes'); ?></label></th>
                            <td><textarea id="note-content" rows="5" class="large-text" required></textarea></td>
                        </tr>
                    </table>
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php echo esc_html__('Save Note', 'wc-notes'); ?></button>
                        <button type="button" id="cancel-form" class="button"><?php echo esc_html__('Cancel', 'wc-notes'); ?></button>
                    </p>
                </form>
            </div>

            <div id="notes-list" class="wc-notes-list">
                <p class="loading"><?php echo esc_html__('Loading notes...', 'wc-notes'); ?></p>
            </div>

            <div id="wc-notes-message" class="notice" style="display: none;"></div>
        </div>
    </div>
    <?php
}

