# Algolia integration plugin

This plugin for WordPress wraps the Algolia Search Client for initializing an Algolia app.

It enables a set of WordPress filters for customizing the posts and custom posts data to sync to Algolia. 

## Prerequisites
- PHP 7.2

## Setup the Algolia App.
```php
$algolia = new AlgoliaIntegration( 'app_id', 'admin_api_key' );
```

## Setup a post type to sync with default fields.

```php
$algolia->createPostSync( 'pos_type', 'algolia_index_name' );
```

Default fields that are synced:
```php
[
    'title'    => 'post_title',
    'author'   => [
        'id'   => 'author_id',
        'name' => 'author_name',
    ],
    'excerpt'  => 'post_excerpt',
    'content'  => wp_strip_all_tags( 'post_content' ),
    'tags'     => [ 'tag1_name', 'tag2_name' ],
    'url'      => 'post_permalink',
]
```