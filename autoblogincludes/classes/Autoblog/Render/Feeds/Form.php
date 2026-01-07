<?php

// +----------------------------------------------------------------------+
// | Copyright PSOURCE (https://github.com/Power-Source)                                |
// +----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License, version 2, as  |
// | published by the Free Software Foundation.                           |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to the Free Software          |
// | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston,               |
// | MA 02110-1301 USA                                                    |
// +----------------------------------------------------------------------+

/**
 * Feed form template class.
 *
 * @category Autoblog
 * @package Render
 * @subpackage Feeds
 *
 * @since 4.0.0
 */
class Autoblog_Render_Feeds_Form extends Autoblog_Render
{

    /**
     * Tips rendering object.
     *
     * @since 4.0.0
     *
     * @access private
     * @var PSOURCE_Help_Tooltips
     */
    private $_tips;

    /**
     * Constructor.
     *
     * @since 4.0.0
     *
     * @access public
     * @param array $data The data what has to be associated with this render.
     */
    public function __construct($data = array())
    {
        parent::__construct($data);

        $this->_tips = new PSOURCE_Help_Tooltips();
        $this->_tips->set_icon_url(AUTOBLOG_ABSURL . 'images/information.png');
    }

    /**
     * Renders template.
     *
     * @since 4.0.0
     *
     * @access protected
     */
    protected function _to_html()
    {
        $title = !empty($this->feed_id)
            ? esc_html__('Bearbeite automatischen Blog-Feed', 'autoblogtext')
            : esc_html__('Erstelle automatischen Blog-Feed', 'autoblogtext');

        ?>
        <div class="wrap">
        <div class="icon32" id="icon-edit"><br></div>
        <h2><?php echo $title ?></h2>

        <form id="autoblog-feeds-table" action="<?php echo add_query_arg('noheader', 'true') ?>" method="post">
            <?php wp_nonce_field('autoblog_feeds') ?>
            <?php $this->_render_form() ?>
        </form>
        </div><?php
    }

    /**
     * Renders form template.
     *
     * @since 4.0.0
     *
     * @access private
     */
    private function _render_form()
    {
        ?>
    <div class="postbox autoblogeditbox" id="ab-<?php echo esc_attr($this->feed_id) ?>">

        <h3 class="hndle"><span><?php esc_html_e('Feed', 'autoblogtext') ?>
                :  <?php echo esc_html(stripslashes($this->title)) ?></span></h3>

        <div class="inside">
            <table width="100%" class="feedtable">
                <?php $this->_render_form_general_section() ?>
                <?php $this->_render_form_author_section() ?>
                <?php $this->_render_form_taxonomies_section() ?>
                <?php $this->_render_form_post_filters_section() ?>
                <?php $this->_render_form_post_excerpt_section() ?>
                <?php $this->_render_form_feed_processing_section() ?>
                <?php do_action('autoblog_feed_edit_form_end', null, $this) ?>
            </table>

            <div class="tablenav">
                <div class="alignright">
                    <a class="button"
                       href='admin.php?page=autoblog_admin'><?php esc_html_e('Abbrechen', 'autoblogtext') ?></a>
                    &nbsp;&nbsp;&nbsp;
                    <input class="button-primary delete save" type="submit"
                           value="<?php echo !empty($this->feed_id) ? esc_attr__('Feed aktualisieren', 'autoblogtext') : esc_attr__('Feed erstellen', 'autoblogtext') ?>"
                           style="margin-right: 10px;">
                </div>
            </div>
        </div>
        </div><?php
    }

    /**
     * Renders form general section template.
     *
     * @since 4.0.0
     *
     * @access private
     */
    private function _render_form_general_section()
    {
        $post_types = get_post_types(array('public' => true), 'objects');
        $post_statuses = get_post_stati(array('public' => true, 'protected' => true, 'private' => true), 'objects', 'or');

        ?>
        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Feed Titel', 'autoblogtext') ?></td>
            <td valign="top">
                <input type="text" name="abtble[title]" value="<?php echo esc_attr(stripslashes($this->title)) ?>"
                       class="long title field">
                <?php echo $this->_tips->add_tip(__('Gib dem Feed einen Namen.', 'autoblogtext')); ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Feed URL', 'autoblogtext') ?></td>
            <td valign="top">
                <input type="text" name="abtble[url]" value="<?php echo esc_attr(stripslashes($this->url)) ?>"
                       class="long url field">
                <?php echo $this->_tips->add_tip(__('Gib die Feed-URL ein.', 'autoblogtext')); ?>
            </td>
        </tr>

        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Beiträge hinzufügen zu', 'autoblogtext') ?></td>
            <td valign="top">
                <?php if (is_multisite() && is_network_admin()) : ?>
                    <select name="abtble[blog]" class="field blog">
                        <?php foreach ($this->_get_blogs_of_site() as $bkey => $blog) : ?>
                            <option value="<?php echo esc_attr($bkey) ?>"<?php selected($blog->id, $this->blog) ?>>
                                <?php echo esc_html($blog->domain . $blog->path) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo $this->_tips->add_tip(__('Wähle einen Blog aus, zu dem der Beitrag hinzugefügt werden soll.', 'autoblogtext')) ?>
                <?php else : ?>
                    <strong>
                        <?php echo esc_html(function_exists('get_blog_option') ? get_blog_option((int)$this->blog, 'blogname') : get_option('blogname')) ?>
                    </strong>
                    <input type="hidden" name="abtble[blog]" value="<?php echo esc_attr($this->blog) ?>">
                <?php endif; ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Beitragstyp für neue Beiträge', 'autoblogtext') ?></td>
            <td valign="top">
                <select id="abtble_posttype" name="abtble[posttype]" class="field">
                    <?php foreach ($post_types as $key => $post_type) : ?>
                        <option value="<?php echo esc_attr($key) ?>"<?php selected($key, $this->posttype) ?>>
                            <?php echo esc_html($post_type->label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php echo $this->_tips->add_tip(__('Wähle den Beitragstyp aus, den die importierten Beiträge im Blog haben sollen.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Standardstatus für neue Beiträge', 'autoblogtext') ?></td>
            <td valign="top">
                <select name="abtble[poststatus]" class="field">
                    <?php foreach ($post_statuses as $key => $post_status) : ?>
                        <option value="<?php echo esc_attr($key) ?>"<?php selected($key, $this->poststatus) ?>>
                            <?php echo esc_html($post_status->label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php echo $this->_tips->add_tip(__('Wähle den Status der importierten Beiträge im Blog aus.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
        <td valign="top" class="heading"><?php esc_html_e('Lege das Datum für neue Beiträge fest', 'autoblogtext') ?></td>
        <td valign="top">
            <select name="abtble[postdate]" class="field">
                <option
                    value="current"<?php selected('current', $this->postdate) ?>><?php esc_html_e('Importdatum', 'autoblogtext') ?></option>
                <option
                    value="existing"<?php selected('existing', $this->postdate) ?>><?php esc_html_e('Datum der ursprünglichen Beiträge', 'autoblogtext') ?></option>
            </select>
            <?php echo $this->_tips->add_tip(__('Wähle das Datum aus, an dem importierte Beiträge angezeigt werden.', 'autoblogtext')) ?>
        </td>
        </tr><?php

        do_action('autoblog_feed_edit_form_details_end', $this->feed_id, $this);

    }

    /**
     * Renders form author section template.
     *
     * @since 4.0.0
     *
     * @access private
     */
    private function _render_form_author_section()
    {
        $blogusers = get_users('blog_id=' . $this->blog);

        ?>
        <tr class="spacer">
            <td colspan="2" class="spacer"><span><?php esc_html_e('Autorendetails', 'autoblogtext') ?></span></td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Autor für neue Beiträge festlegen', 'autoblogtext') ?></td>
            <td valign="top">
                <select name="abtble[author]" class="field author">
                    <option value="0"><?php esc_html_e('Verwende den Feed-Autor', 'autoblogtext') ?></option>
                    <?php foreach ($blogusers as $bloguser) : ?>
                        <option
                            value="<?php echo esc_attr($bloguser->ID) ?>"<?php selected($bloguser->ID, $this->author) ?>>
                            <?php echo esc_html($bloguser->user_login) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php echo $this->_tips->add_tip(__('Wähle den Autor aus, den Du für die Beiträge verwenden möchtest, oder versuche, den ursprünglichen Feed-Autor zu verwenden.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
        <td valign="top"
            class="heading"><?php esc_html_e('Wenn der Autor im Feed nicht lokal vorhanden ist, verwende', 'autoblogtext') ?></td>
        <td valign="top">
            <select name="abtble[altauthor]" class="field altauthor">
                <?php foreach ($blogusers as $bloguser) : ?>
                    <option
                        value="<?php echo esc_attr($bloguser->ID) ?>"<?php selected($bloguser->ID, $this->altauthor) ?>>
                        <?php echo esc_html($bloguser->user_login) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php echo $this->_tips->add_tip(__('Wenn der Feed-Autor in Deinem Blog nicht vorhanden ist, verwende diesen Autor.', 'autoblogtext')) ?>
        </td>
        </tr><?php
    }

    /**
     * Renders form taxonomies section template.
     *
     * @since 4.0.0
     *
     * @access private
     */
    private function _render_form_taxonomies_section()
    {
        // backward compatibility
        switch ($this->feedcatsare) {
            case 'tags':
                $this->feedcatsare = 'post_tag';
                break;
            case 'categories':
                $this->feedcatsare = 'category';
                break;
        }

        // fetch all public taxonomies
        $taxonomies = get_taxonomies(array('public' => true, 'show_ui' => true), 'objects');

        ?>
        <tr class="spacer">
            <td colspan="2" class="spacer"><span><?php esc_html_e('Taxonomien', 'autoblogtext') ?></span></td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Feedkategorien behandeln als', 'autoblogtext') ?></td>
            <td valign="top">
                <select id="abtble_feedcatsare" name="abtble[feedcatsare]">
                    <option></option>
                    <?php foreach ($taxonomies as $taxonomy_id => $taxonomy) : ?>
                        <option
                            value="<?php echo esc_attr($taxonomy_id) ?>"<?php selected($this->feedcatsare, $taxonomy_id) ?>
                            data-objects="<?php echo implode(',', $taxonomy->object_type) ?>">
                            <?php echo esc_html($taxonomy->label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label>
                    <input type="checkbox" name="abtble[originalcategories]" class="case field"
                           value="1"<?php checked($this->originalcategories == 1) ?>>
                    <span><?php esc_html_e('Füge alle hinzu, die nicht vorhanden sind.', 'autoblogtext') ?></span>
                </label>
                <?php echo $this->_tips->add_tip(__('Erstelle alle erforderlichen Taxonomiebegriffe.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Weise dieser Kategorie Beiträge zu', 'autoblogtext') ?></td>
            <td valign="top">
                <?php if (function_exists('switch_to_blog')) switch_to_blog($this->blog) ?>
                <?php wp_dropdown_categories(array(
                    'hide_empty' => 0,
                    'name' => 'abtble[category]',
                    'orderby' => 'name',
                    'selected' => $this->category,
                    'hierarchical' => true,
                    'show_option_none' => __('Keine', 'autoblogtext'),
                    'class' => 'field cat'
                )) ?>
                <?php if (function_exists('restore_current_blog')) restore_current_blog() ?>
                <?php echo $this->_tips->add_tip(__('Weise diese Kategorie den importierten Beiträgen zu.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
        <td valign="top" class="heading"><?php esc_html_e('Füge diese Tags zu den Beiträgen hinzu', 'autoblogtext') ?></td>
        <td valign="top">
            <input type="text" name="abtble[tag]" value="<?php echo esc_attr(stripslashes($this->tag)) ?>"
                   class="long tag field">
            <?php echo $this->_tips->add_tip(__('Gib eine durch Kommas getrennte Liste der hinzuzufügenden Tags ein.', 'autoblogtext')) ?>
        </td>
        </tr><?php
    }

    /**
     * Renders form post filters section template.
     *
     * @since 4.0.0
     *
     * @access private
     */
    private function _render_form_post_filters_section()
    {
        ?>
        <tr class="spacer">
            <td colspan="2" class="spacer"><span><?php esc_html_e('Beiträge Filtern', 'autoblogtext') ?></span></td>
        </tr>

        <tr>
            <td colspan="2"><?php esc_html_e('Füge Beiträge hinzu, die (separate Wörter mit Kommas) enthalten', 'autoblogtext') ?></td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Alle diese Wörter', 'autoblogtext') ?></td>
            <td valign="top">
                <input type="text" name="abtble[allwords]" value="<?php echo esc_attr(stripslashes($this->allwords)) ?>"
                       class="long title field">
                <?php echo $this->_tips->add_tip(__('Ein zu importierender Beitrag muss ALLE diese Wörter im Titel oder Inhalt enthalten.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Eines dieser Wörter', 'autoblogtext') ?></td>
            <td valign="top">
                <input type="text" name="abtble[anywords]" value="<?php echo esc_attr(stripslashes($this->anywords)) ?>"
                       class="long title field">
                <?php echo $this->_tips->add_tip(__('Ein zu importierender Beitrag muss eines dieser Wörter im Titel oder Inhalt enthalten.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Die genaue Phrase', 'autoblogtext') ?></td>
            <td valign="top">
                <input type="text" name="abtble[phrase]" value="<?php echo esc_attr(stripslashes($this->phrase)) ?>"
                       class="long title field">
                <?php echo $this->_tips->add_tip(__('Ein zu importierender Beitrag muss genau diesen Ausdruck im Titel oder Inhalt haben.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Keines dieser Wörter', 'autoblogtext') ?></td>
            <td valign="top">
                <input type="text" name="abtble[nonewords]"
                       value="<?php echo esc_attr(stripslashes($this->nonewords)) ?>" class="long title field">
                <?php echo $this->_tips->add_tip(__('Ein zu importierender Beitrag darf KEINES dieser Wörter im Titel oder Inhalt enthalten.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td style="vertical-align: top"
                class="heading"><?php esc_html_e('Regelmäßigen Ausdruck abgleichen', 'autoblogtext') ?></td>
            <td>
                <textarea name="abtble[regex]"
                          class="long title field"><?php echo esc_textarea(stripslashes($this->regex)) ?></textarea>
                <?php echo $this->_tips->add_tip(__('Verwende das "|" ODER-Operator zum Kombinieren mehrerer Ausdrücke.', 'autoblogtext')) ?>
                <br>
                <span><?php _e('Verwende die <a href="http://www.php.net/manual/en/reference.pcre.pattern.syntax.php">PCRE-Mustersyntax </a> für Deinen regulären Ausdruck, einschließlich Trennzeichen und Escapezeichen.', 'autoblogtext') ?></span>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Alle diese Tags', 'autoblogtext') ?></td>
            <td valign="top">
                <input type="text" name="abtble[anytags]" value="<?php echo esc_attr(stripslashes($this->anytags)) ?>"
                       class="long title field">
                <?php echo $this->_tips->add_tip(__('Ein zu importierender Beitrag muss mit einer dieser Kategorien oder Tags gekennzeichnet sein.', 'autoblogtext')) ?>
                <br>
                <span><?php esc_html_e('Tags sollten durch Kommas getrennt werden', 'autoblogtext') ?></span>
            </td>
        </tr>
    <?php
    }

    /**
     * Renders form post_excerpt section template.
     *
     * @since 4.0.0
     *
     * @access private
     */
    private function _render_form_post_excerpt_section()
    {
        ?>
        <tr class="spacer">
            <td colspan="2" class="spacer"><span><?php esc_html_e('Auszüge zeigen', 'autoblogtext') ?></span></td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Verwende den vollständigen Beitrag oder einen Auszug', 'autoblogtext') ?></td>
            <td valign="top">
                <select name="abtble[useexcerpt]" class="field">
                    <option value="1"><?php esc_html_e('Verwende vollen Beitrag', 'autoblogtext') ?></option>
                    <option
                        value="2"<?php selected(2, $this->useexcerpt) ?>><?php esc_html_e('Verwende Auszug', 'autoblogtext') ?></option>
                </select>
                <?php echo $this->_tips->add_tip(__('Verwende den vollständigen Beitrag (falls verfügbar) oder erstelle einen Auszug.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Für Auszüge verwenden', 'autoblogtext') ?></td>
            <td valign="top">
                <input type="text" name="abtble[excerptnumber]"
                       value="<?php echo esc_attr(stripslashes($this->excerptnumber)) ?>" class="narrow field"
                       style='width: 3em;'>
                <select name="abtble[excerptnumberof]" class="field">
                    <option
                        value="words"<?php selected('words', $this->excerptnumberof) ?>><?php esc_html_e('Wörter', 'autoblogtext') ?></option>
                    <option
                        value="sentences"<?php selected('sentences', $this->excerptnumberof) ?>><?php esc_html_e('Sätze', 'autoblogtext') ?></option>
                    <option
                        value="paragraphs"<?php selected('paragraphs', $this->excerptnumberof) ?>><?php esc_html_e('Absätze', 'autoblogtext') ?></option>
                </select>
                <?php echo $this->_tips->add_tip(__('Gib die Größe des zu erstellenden Auszugs an (falls ausgewählt).', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
        <td valign="top" class="heading"><?php esc_html_e('Link zur Originalquelle', 'autoblogtext') ?></td>
        <td valign="top">
            <input type="text" name="abtble[source]" value="<?php echo esc_attr(stripslashes($this->source)) ?>"
                   class="long source field">
            <?php echo $this->_tips->add_tip(__('Wenn Du einen Link zur Originalquelle erstellen möchtest, gib hier einen Ausdruck ein, der verwendet werden soll.', 'autoblogtext')) ?>
            <br>
            <label>
                <input type="checkbox" name="abtble[nofollow]" value="1"<?php checked($this->nofollow == 1) ?>>
                <?php esc_html_e('Stelle sicher, dass Suchmaschinen diesem Link nicht folgen', 'autoblogtext') ?>
            </label>
            <br>
            <label>
                <input type="checkbox" name="abtble[newwindow]" value="1"<?php checked($this->newwindow == 1) ?>>
                <?php esc_html_e('Öffne diesen Link in einem neuen Fenster', 'autoblogtext') ?>
            </label>
        </td>
        </tr><?php
    }

    /**
     * Renders form feed processing section template.
     *
     * @since 4.0.0
     *
     * @access private
     */
    private function _render_form_feed_processing_section()
    {
        ?>
        <tr class="spacer">
            <td colspan="2" class="spacer"><span><?php esc_html_e('Feed Verarbeitung', 'autoblogtext') ?></span></td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Importiere die neuesten', 'autoblogtext') ?></td>
            <td valign="top">
                <select name="abtble[poststoimport]" class="field">
                    <option value="0"><?php esc_html_e('Beiträge.', 'autoblogtext') ?></option>
                    <?php for ($n = 1; $n <= 100; $n++) : ?>
                        <option value="<?php echo $n ?>"<?php selected($n, $this->poststoimport) ?>>
                            <?php echo $n ?> <?php esc_html_e('hinzugefügte Beiträge.', 'autoblogtext') ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <?php echo $this->_tips->add_tip(__('Du kannst festlegen, dass nur eine bestimmte Anzahl neuer Beiträge importiert wird und nicht so viele, wie das Plugin verwalten kann.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Verarbeite diesen Feed', 'autoblogtext') ?></td>
            <td valign="top">
                <select name="abtble[processfeed]" class="field">
                    <option
                        value="0"<?php selected(0, $this->processfeed) ?>><?php esc_html_e('Nie (Pause)', 'autoblogtext') ?></option>

                    <option
                        value="5"<?php selected(5, $this->processfeed) ?>><?php esc_html_e('alle 5 Minuten', 'autoblogtext') ?></option>
                    <option
                        value="10"<?php selected(10, $this->processfeed) ?>><?php esc_html_e('alle 10 Minuten', 'autoblogtext') ?></option>
                    <option
                        value="15"<?php selected(15, $this->processfeed) ?>><?php esc_html_e('alle 15 Minuten', 'autoblogtext') ?></option>
                    <option
                        value="20"<?php selected(20, $this->processfeed) ?>><?php esc_html_e('alle 20 Minuten', 'autoblogtext') ?></option>
                    <option
                        value="25"<?php selected(25, $this->processfeed) ?>><?php esc_html_e('alle 25 Minuten', 'autoblogtext') ?></option>

                    <option
                        value="30"<?php selected(30, $this->processfeed) ?>><?php esc_html_e('alle 30 Minuten', 'autoblogtext') ?></option>
                    <option
                        value="60"<?php selected(60, $this->processfeed) ?>><?php esc_html_e('jede Stunde', 'autoblogtext') ?></option>
                    <option
                        value="90"<?php selected(90, $this->processfeed) ?>><?php esc_html_e('alle 1 Stunde 30 Minuten', 'autoblogtext') ?></option>
                    <option
                        value="120"<?php selected(120, $this->processfeed) ?>><?php esc_html_e('alle 2 Stunden', 'autoblogtext') ?></option>
                    <option
                        value="150"<?php selected(150, $this->processfeed) ?>><?php esc_html_e('alle 2 Stunden 30 Minuten', 'autoblogtext') ?></option>
                    <option
                        value="300"<?php selected(300, $this->processfeed) ?>><?php esc_html_e('alle 5 Stunden', 'autoblogtext') ?></option>
                    <option
                        value="1449"<?php selected(1449, $this->processfeed) ?>><?php esc_html_e('jeden Tag', 'autoblogtext') ?></option>
                </select>
                <?php echo $this->_tips->add_tip(__('Stelle die Zeitverzögerung für die Verarbeitung dieses Feeds ein. Unregelmäßig aktualisierte Feeds müssen nicht sehr oft überprüft werden.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Beginnt ab', 'autoblogtext') ?></td>
            <td valign="top">
                <select name="abtble[startfromday]" class="field">
                    <option></option>
                    <?php for ($n = 1; $n <= 31; $n++) : ?>
                        <option
                            value="<?php echo $n ?>"<?php selected(!empty($this->startfrom) && $n == date('j', $this->startfrom)) ?>><?php echo $n ?></option>
                    <?php endfor; ?>
                </select>
                <select name="abtble[startfrommonth]" class="field">
                    <option></option>
                    <?php for ($n = 1; $n <= 12; $n++) : ?>
                        <option
                            value="<?php echo $n ?>"<?php selected(!empty($this->startfrom) && $n == date('n', $this->startfrom)) ?>><?php echo date('M', strtotime(date('Y-' . $n . '-1'))) ?></option>
                    <?php endfor; ?>
                </select>
                <select name="abtble[startfromyear]" class="field">
                    <option></option>
                    <?php for ($n = date("Y") - 10; $n <= date("Y") + 9; $n++) : ?>
                        <option
                            value="<?php echo $n ?>"<?php selected(!empty($this->startfrom) && $n == date('Y', $this->startfrom)) ?>><?php echo $n ?></option>
                    <?php endfor; ?>
                </select>
                <?php echo $this->_tips->add_tip(__('Lege das Datum fest, ab dem Sie mit der Verarbeitung von Posts beginnen möchtest.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('Ende am', 'autoblogtext') ?></td>
            <td valign="top">
                <select name="abtble[endonday]" class="field">
                    <option></option>
                    <?php for ($n = 1; $n <= 31; $n++) : ?>
                        <option
                            value="<?php echo $n ?>"<?php selected(!empty($this->endon) && $n == date('j', $this->endon)) ?>><?php echo $n ?></option>
                    <?php endfor; ?>
                </select>
                <select name="abtble[endonmonth]" class="field">
                    <option></option>
                    <?php for ($n = 1; $n <= 12; $n++) : ?>
                        <option
                            value="<?php echo $n ?>"<?php selected(!empty($this->endon) && $n == date('n', $this->endon)) ?>><?php echo date('M', strtotime(date('Y-' . $n . '-1'))) ?></option>
                    <?php endfor; ?>
                </select>
                <select name="abtble[endonyear]" class="field">
                    <option></option>
                    <?php for ($n = date("Y") - 10; $n <= date("Y") + 9; $n++) : ?>
                        <option
                            value="<?php echo $n ?>"<?php selected(!empty($this->endon) && $n == date('Y', $this->endon)) ?>><?php echo $n ?></option>
                    <?php endfor; ?>
                </select>
                <?php echo $this->_tips->add_tip(__('Lege das Datum fest, an dem die Verarbeitung von Beiträgen aus diesem Feed beendet werden soll.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
            <td valign="top" class="heading"><?php esc_html_e('SSL-Überprüfung erzwingen', 'autoblogtext') ?></td>
            <td valign="top">
                <select name="abtble[forcessl]" class="field">
                    <option value="yes"><?php esc_html_e('Ja', 'autoblogtext') ?></option>
                    <option
                        value="no"<?php selected('no', $this->forcessl) ?>><?php esc_html_e('Nein', 'autoblogtext') ?></option>
                </select>
                <?php echo $this->_tips->add_tip(__('Wenn Du SSL-Fehler erhältst oder Dein Feed ein selbstsigniertes SSL-Zertifikat verwendet, setze dieses auf <strong>Nein</strong>.', 'autoblogtext')) ?>
            </td>
        </tr>

        <tr>
        <td valign="top" class="heading"><?php esc_html_e('Duplikate überschreiben', 'autoblogtext') ?></td>
        <td valign="top">
            <select name="abtble[overridedups]" class="field">
                <option value="no"><?php esc_html_e('Nein', 'autoblogtext') ?></option>
                <option
                    value="yes"<?php selected('yes', $this->overridedups) ?>><?php esc_html_e('Ja', 'autoblogtext') ?></option>
            </select>
            <?php echo $this->_tips->add_tip(__('Wähle Ja, wenn Du zuvor importierte Elemente mit dem neuen Inhalt überschreiben möchtest. Andernfalls werden Duplikate übersprungen.', 'autoblogtext')) ?>
        </td>
        </tr><?php
    }

    /**
     * Returns blogs of the site.
     *
     * @since 4.0.0
     *
     * @access private
     * @global type $current_site
     * @global type $wpdb
     * @param type $siteid
     * @param type $all
     * @return type
     */
    private function _get_blogs_of_site($siteid = false, $all = false)
    {
        global $current_site, $wpdb;
        if (!$siteid && !empty($current_site)) {
            $siteid = $current_site->id;
        }

        $blogs = array();
        $results = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = %d ORDER BY path ASC", $siteid));
        foreach ($results as $blog_id) {
            $blog = get_blog_details($blog_id);
            if (!empty($blog) && isset($blog->domain)) {
                $blogs[$blog_id] = $blog;
                $blogs[$blog_id]->id = $blog_id;
            }
        }
        //sort by alphebeta
        //get the main blog out of array
        //$main_blog = array_shift($blogs);
        //usort($blogs, array(&$this, '_sort_blogs_by_name'));
        //$blogs = array_merge(array($main_blog), $blogs);
        return $blogs;
    }

    function _sort_blogs_by_name($a, $b)
    {
        return strcmp($a->path, $b->path);
    }

}