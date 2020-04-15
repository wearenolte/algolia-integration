# Algolia integration WordPress plugin

This plugin for WordPress wraps the [Algolia Search Client v2.6](https://www.algolia.com/doc/integration/wordpress/getting-started/quick-start/?language=php) for initializing an Algolia app.

Select which posts types to sync to Algolia. It will create the Algolia index using the post type name. 

The data uploaded to Algolia can be customized with a filter. 

This plugin will also install [instantsearch.js 2.10.4](https://community.algolia.com/instantsearch.js/v2/getting-started.html)

It will replace WordPress default search box for the Instant Search Search Box widget.

## Prerequisites
- PHP 7.2

## Setup the Algolia App

Go to Settings -> Algolia Integration

Add the Algolia app ID and admin api key.

Add the Algolia test app ID and test admin api key.

## Enable post types to sync

Go to Settings -> Algolia Integration

Select which posts types will be uploaded to Algolia on Post publised
or deleted from Algolia when the post status is different than published.

## Default post fields to sync

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

## Posts sync statuses.
By default this plugin only saves to Algolia when a post is saved with `publish` status.

If the post is thrashed, deleted or changed to other status than `publish`, it will be deleted from Algolia. 


## Sync custom fields.

Use the filter `'algolia_integration_format_' . $post_type` to return an array with the data you want to sync.

Example:
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

## Customize the index settings:

You can set the index settings with this filter.

By default, the searchable attributes is only the title.

```php
add_filter(
	'algolia_integration_index_settings_' . $post_type,
	function() {
	 return [
        'searchableAttributes' => ['content'],
     ];
	}
);
```

### Disable Instant Search assets loading
Disable Algolia Search CSS file
```php
add_filter('algolia_integration_disable_instant_search_css', '__return_true' );
```
Disable Algolia Search JS file
```php
add_filter('algolia_integration_disable_instant_search_js', '__return_true' );
```
Disable Algolia Search custom JS file that initializes the Search Box and Hits widgets.
```php
add_filter('algolia_integration_disable_instant_search_custom_js', '__return_true' );
```

## Shortcodes
### Search Box
Print the Instant Search Box widget using this shortcode:

`print_algolia_search_box`

Print the Instant Search Box hits (results) widget using this shortcode:

`print_algolia_results`

## Change the hits item template
```php
add_filter( 
    'algolia_integration_hits_template', 
    function() { 
        return '<li>{{{_highlightResult.title.value}}}</li>'; 
    } 
);
```
