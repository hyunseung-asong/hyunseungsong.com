<?php
/**
 * This site's company id (use A, B, C, or D per group role — one letter per deployed site).
 */
if (!defined('COMPANY_ID')) {
    define('COMPANY_ID', 'A');
}

/**
 * Full URLs to the other group members' api/company_users.php endpoints.
 * Group of four: list exactly three teammates here (replace TEAMMATE_* placeholders).
 *
 * @return list<string>
 */
function get_remote_user_api_urls() {
    return [
        'https://TEAMMATE_B_DOMAIN/api/company_users.php',
        'https://TEAMMATE_C_DOMAIN/api/company_users.php',
        'https://TEAMMATE_D_DOMAIN/api/company_users.php',
    ];
}
