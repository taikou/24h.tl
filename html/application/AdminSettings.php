<?php
/*
 * -------------------------------------
 * Miguel Vasquez
 * AdminSettings.php
 * Get settings Admin
 * -------------------------------------
 */
class adminSettings extends ModelBase
{
    
    public function getSettings() {
        $post = $this->db->query("
        SELECT
        title,
        description,
        keywords,
        message_length,
        post_length,
        new_registrations,
        email_verification,
        ad,
        bg_navbar,
        color_link_navbar,
        color_icons_verified
        FROM ". ADMIN_SETTINGS ." ");
        return $post->fetch( PDO::FETCH_OBJ );
    }
}//<-- * END CLASS * -->

?>
