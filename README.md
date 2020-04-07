# Algolia integration plugin

This plugin for WordPress wraps the Algolia Search Client for initializing an Algolia app.

It enables a set of WordPress filters for customizing the posts and custom posts data to sync to Algolia. 

## Prerequisites
- PHP 7.2

## Setup the Algolia App.

Add the Algolia's app id and the admin api key.
```php
$algolia = new AlgoliaIntegration( 'app_id', 'admin_api_key' );
```

## Setup a post type to sync with default fields.

Add the post type and the Algolia index.
```php
$algolia->createPostSync( 'post_type', 'algolia_index_name' );
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

## Sync custom fields.

Use the filter `'algolia_integration_format_' . $post_type` to return an array with the data you want to sync.
```php
add_filter(
	'algolia_integration_format_' . $post_type,
	function( $record_format, $post_id ) {
		return [
			'acf_field1' => 'custom_data',
			'acf_field2' => 'custom_data',
		];
	},
	10, 2
);
```
