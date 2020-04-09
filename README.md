# Algolia integration plugin

This plugin for WordPress wraps the Algolia Search Client for initializing an Algolia app.

Add the production app and a test app which will be used when DEBUG is on.

It enables a settings page for selecting which posts types to sync to Algolia.
The data uploaded to Algolia can be customized with a filter. 

## Prerequisites
- PHP 7.2

## Setup the Algolia App.

Go to Settings -> Algolia Integration

Add the Algolia app ID and admin api key.
Add the Algolia test app ID and test admin api key.

## Enable post types to syns

Go to Settings -> Algolia Integration

Select which posts types will be uploaded to Algolia on Post publised
or deleted from Algolia when the post status is different than published.

## Default post fields to sync.

Default fields that are synced:
```php
[
    'title'              => 'post_title',
    'author'             => [
        'id'             => 'author_id',
        'name'           => 'author_name',
    ],
    'excerpt'            => 'post_excerpt',
    'content'            => wp_strip_all_tags( 'post_content' ),
    'tags'               => [ 'tag1_name', 'tag2_name' ],
    'url'                => 'post_permalink',
    'featured_image_url' => 'featured_image_url',
]
```

## Sync custom fields.

Use the filter `'algolia_integration_format_' . $post_type` to return an array with the data you want to sync.
```php
add_filter(
	'algolia_integration_format_' . $post_type,
	function( $record_format, $post_id ) {
		return array_merge(
		    $record_format,
            [
                'acf_field1' => 'custom_data',
                'acf_field2' => 'custom_data',
            ]
		);
	},
	10, 2
);
```
