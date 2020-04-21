# Algolia integration WordPress plugin

This plugin for WordPress wraps the [Algolia Search Client v2.6](https://www.algolia.com/doc/integration/wordpress/getting-started/quick-start/?language=php) for initializing an Algolia app.

Select which posts types to sync to Algolia. It will create the Algolia index using the post type name. 

The data of each post type uploaded to Algolia can be customized with a filter. 

This plugin will also install [instantsearch.js 2.10.4](https://community.algolia.com/instantsearch.js/v2/getting-started.html)

It will enable 2 shortcodes for adding the Instant Search `Search Box` and the `Hits` widget.

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

Example: 
```php
add_filter(
	'algolia_integration_index_settings_' . $post_type,
	function() {
        return [
            'searchableAttributes'  => ['content'],
            'attributesToHighlight' => [ 'title', 'excerpt' ],
            'attributesForFaceting' => [ 'category' ]
        ];
    }
);
```

View More Info: https://www.algolia.com/doc/api-reference/settings-api-parameters/

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

## Instant Search widgets
The plugin comes with 2 Instant Search widgets preconfigured: [Search Box](https://community.algolia.com/instantsearch.js/v2/widgets/searchBox.html) and [Hits](https://community.algolia.com/instantsearch.js/v2/widgets/hits.html) widgets.

This widgets will search by default on all post types that you marked for syncing in the Plugin's Settings.

### Search Box widget
Print the Instant Search Box widget using this shortcode:

`print_algolia_search_box`

##Hits widget (Results)

Print the Instant Search Box hits (results) widget using this shortcode:

`print_algolia_results`

### Customize the widgets settings

## Set Post Types

By default, the widgets will search on the post types selected on the Setting's page.

To change the post types used by the widgets, use the following filter:

Example:
```php
add_filter( 
    'algolia_integration_widgets_post_types', 
    function() { 
        return [
                'post',
                'custom_post_type_1',
                'custom_post_type_2',
            ],
        ];
    } 
);

Note: Make sure the post types added are selected in the Plugin's Settings page or the widgets won't initialize. 

## Instant Search initialization
To be able to use the Search Box to work with all the post types, a special configuration with [the Instant Search initialization](https://community.algolia.com/instantsearch.js/v2/instantsearch.html) was required:

The widgets are configured with one "main" Instant Search initialization (Main Search) and multiple "secondary" Instant Search initializations (Secondary Search).

The Main Search is set with the first Algolia index which is the first Post Type in the Post Type list in the Setting's page. This Main Search is important because it contains the SearchFunction method which ties all the post types indexes with the Search Box widget.

Note: the SearchFunction method can't be overwritten.

The Secondary Search is set with the remaining of the post types.

## Change the Main Search settings
Example: 
```php
add_filter( 
    'algolia_integration_main_search_config', 
    function() { 
        return [
            'searchParameters' => [
                'hitsPerPage' => 3,
                'filters' => 'category:term',
            ],
        ];
    } 
);
```

View More Info: https://www.algolia.com/doc/api-reference/api-parameters/searchableAttributes/

## Change the Secondary Search settings
Example: 
```php
add_filter( 
    'algolia_integration_secondary_search_config', 
    function() { 
        return [
            'searchParameters' => [
                'hitsPerPage' => 3,
                'filters' => 'category:term',
            ],
        ];
    } 
);
```

## Change the Search Box widget settings
Example: 
```php
add_filter( 
    'algolia_integration_search_box_config', 
    function() { 
        return [
            'autofocus'   => false,
            'placeholder' => 'Type here to search',
        ]; 
    } 
);
```

## Change the Hits widget settings
Example: 
```php
add_filter( 
    'algolia_integration_hits_config', 
    function() { 
        return [
            'templates' => [
                'empty' => 'No results',
                'item'  => '<li>{{{_highlightResult.title.value}}}</li>',
            ]
        ];
    } 
);
```
