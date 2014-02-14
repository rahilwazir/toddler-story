<?php

namespace RW\PostTypes;

interface PostTypeBase
{
    /**
     * Register custom post type
     */
    public function registerPostType();

    /**
     * Register taxonomy for custom post type
     */
    public function registerTaxonomy();

    /**
     * Remove post type slugs
     */
    public function removePostTypeSlug();
}