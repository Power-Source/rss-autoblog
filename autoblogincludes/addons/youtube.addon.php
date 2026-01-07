<?php

/*
Addon Name: Youtube Feed Import
Description: YouTube Feed Importeur. Fügt am Anfang eines Beitrags ein YouTube-Video hinzu.
Author: PSOURCE
Author URI: https://github.com/Power-Source
*/

if (!defined('SIMPLEPIE_NAMESPACE_YOUTUBE')) {
    define('SIMPLEPIE_NAMESPACE_YOUTUBE', 'http://search.yahoo.com/mrss/');
}

class Autoblog_Addon_Youtube extends Autoblog_Addon
{
    const SOURCE_THE_FIRST_VIDEO = 'ASC';
    const SOURCE_THE_LAST_VIDEO = 'DESC';
    const SOURCE_ENCLOSURE = 'ENCLOSURE';

    /**
     * Constructor.
     *
     * @since  4.0.2
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->_add_action('autoblog_feed_edit_form_end', 'render_utube_options', 10, 2);
        // Use WordPress Embed API instead of manual iframe handling
        $this->_add_filter('autoblog_post_content_before_import', 'process_content', 10, 3);
    }

    /**
     * Renders YouTube addon options in feed settings.
     *
     * @param string $key Feed key.
     * @param object $details Feed details.
     */
    public function render_utube_options($key, $details)
    {
        $table = !empty($details->feed_meta)
            ? maybe_unserialize($details->feed_meta)
            : array();

        $selected_option = apply_filters('autoblog_utube_from', isset($table['utubeimport']) ? $table['utubeimport'] : AUTOBLOG_IMAGE_CHECK_ORDER);
        $options = array(
            '' => __('Keine YouTube-Videos importieren', 'autoblogtext'),
            self::SOURCE_ENCLOSURE => __('Verwende das Basis-Tag eines Feed-Elements', 'autoblogtext'),
            self::SOURCE_THE_FIRST_VIDEO => __('Finde das erste Youtube-Video im Inhalt eines Feed-Elements', 'autoblogtext'),
            self::SOURCE_THE_LAST_VIDEO => __('Finde das letzte Youtube-Video im Inhalt eines Feed-Elements', 'autoblogtext'),
        );

        $radio = '';
        foreach ($options as $option_key => $label) {
            $radio .= sprintf(
                '<div><label><input type="radio" name="abtble[utubeimport]" value="%s"%s> %s</label></div>',
                esc_attr($option_key),
                checked($option_key, $selected_option, false),
                esc_html($label)
            );
        }

        // render block header
        $this->_render_block_header(__('Youtube Video Importieren', 'autoblogtext'));

        // render block elements
        $this->_render_block_element(__('Wähle eine Möglichkeit zum Importieren von Youtube-Videos', 'autoblogtext'), $radio);
        $this->_render_block_element(__('Hinweis', 'autoblogtext'), __('Videos werden über die WordPress Embed API eingebunden (responsive und sicher).', 'autoblogtext'));
    }

    /**
     * Processes content to add YouTube videos.
     * Uses WordPress Embed API for clean, responsive video embedding.
     *
     * @param string $old_content The original post content.
     * @param array $details Feed details.
     * @param SimplePie_Item $item Feed item.
     * @return string Modified content with video URLs.
     */
    public function process_content($old_content, $details, SimplePie_Item $item)
    {
        $method = trim(isset($details['utubeimport']) ? $details['utubeimport'] : '');
        if (empty($method)) {
            return $old_content;
        }

        $video_url = null;

        if ($method === self::SOURCE_ENCLOSURE) {
            $video_url = $this->_find_in_enclosure($item);
        } else {
            $video_url = $this->_find_in_content($method, $item);
        }

        if (!$video_url) {
            return $old_content;
        }

        // Prepend video URL on its own line - WordPress Embed API will handle the rest
        // This is much cleaner than manual iframe handling
        return $video_url . PHP_EOL . PHP_EOL . $old_content;
    }

    /**
     * Finds YouTube video URL in content.
     *
     * @param string $method SOURCE_THE_FIRST_VIDEO or SOURCE_THE_LAST_VIDEO.
     * @param SimplePie_Item $item Feed item.
     * @return string|null YouTube video URL or null.
     */
    private function _find_in_content($method, SimplePie_Item $item)
    {
        // Comprehensive YouTube URL regex
        $regex = '/https?:\/\/(?:[0-9A-Z-]+\.)?(?:youtu\.be\/|youtube\.com(?:\/embed\/|\/v\/|\/watch\?v=|\/ytscreeningroom\?v=|\/feeds\/api\/videos\/|\/user\S*[^\w\-\s]|\S*[^\w\-\s]))([\w\-]{11})[?=&+%\w-]*/i';

        $content = $item->get_content();
        if (empty($content)) {
            return null;
        }

        if (!preg_match_all($regex, $content, $matches)) {
            return null;
        }

        $links = $matches[0];
        if (empty($links)) {
            return null;
        }

        // Get first or last video based on method
        if ($method === self::SOURCE_THE_FIRST_VIDEO) {
            $link = reset($links);
        } else {
            $link = end($links);
        }

        return $link;
    }

    /**
     * Finds YouTube video in enclosure tags.
     *
     * @param SimplePie_Item $item Feed item.
     * @return string|null YouTube video URL or null.
     */
    private function _find_in_enclosure(SimplePie_Item $item)
    {
        $enclosures = $item->get_enclosures();
        if (empty($enclosures)) {
            return null;
        }

        foreach ($enclosures as $enclosure) {
            $link = $enclosure->link;
            if (preg_match('#^https?://(www\.)?youtube\.com/#i', $link)) {
                return $link;
            }
        }

        return null;
    }

    /**
     * Extracts YouTube video ID from a URL.
     *
     * @param string $link YouTube URL.
     * @return string|false YouTube video ID or false.
     */
    private function find_youtube_id($link)
    {
        $regex = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/";
        if (preg_match($regex, $link, $uid)) {
            return $uid[1];
        }

        return false;
    }

}

$ayoutubeaddon = new Autoblog_Addon_Youtube();