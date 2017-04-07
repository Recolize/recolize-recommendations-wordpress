<?php
/**
 * Recolize WordPress RSS Feed Extensions
 *
 * @section LICENSE
 * This source file is subject to the GNU General Public License Version 3 (GPLv3).
 *
 * @category Recolize
 * @package Recolize_RecommendationEngine
 * @author Recolize GmbH <service@recolize.com>
 * @copyright 2015 Recolize GmbH (http://www.recolize.com)
 * @license http://opensource.org/licenses/GPL-3.0 GNU General Public License Version 3 (GPLv3).
 */
class Recolize_RssFeed
{
    /**
     * Initializes the methods to extend the RSS items.
     *
     * @return Recolize_RssFeed
     */
    public function initialize()
    {
        add_action('rss_item', array($this, 'addExtraItemInformation'));
        add_action('rss2_item', array($this, 'addExtraItemInformation'));

        return $this;
    }

    /**
     * Add additional information to the RSS feed.
     *
     * The information includes, if available:
     * - Product Price (WooCommerce)
     * - Product Image (WooCommerce)
     * - Post Image
     *
     * @return Recolize_RssFeed
     */
    public function addExtraItemInformation()
    {
        /** @var WP_Post $post */
        global $post;

        $extraItemInformation = sprintf(
            '<image_url>%s</image_url><product><price>%.2f</price><image>%s</image></product>',
            $this->getPostImage($post->ID),
            $this->getProductPrice($post->ID),
            $this->getPostImage($post->ID)
        );

        echo $extraItemInformation;

        return $this;
    }

    /**
     * Return the image for the given post id.
     *
     * @param integer $postId
     * @param string $size
     *
     * @return string
     */
    private function getPostImage($postId, $size = 'full')
    {
        if (function_exists ('has_post_thumbnail') === false || has_post_thumbnail($postId) === false) {
            return '';
        }

        $postThumbnailId = get_post_thumbnail_id($postId);
        if (empty($postThumbnailId) === true) {
            return '';
        }

        $postImageSrc = wp_get_attachment_image_src($postThumbnailId, $size);
        if (is_array($postImageSrc) === false) {
            return '';
        }

        return $postImageSrc[0];
    }

    /**
     * Return the price for the given post id if the WooCommerce plugin is installed.
     *
     * @param integer $postId
     *
     * @return null|float
     */
    private function getProductPrice($postId)
    {
        if (class_exists('WC_Product') === false) {
            return null;
        }

        $product = new WC_Product($postId);

        return $product->price;
    }
}