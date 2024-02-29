# Kirby usage reference plugin
Infosection to display all references to a page or image  in a list.

![preview](preview.png)

## Install
### Download Zip file

Copy plugin folder into `site/plugins`

### Git submodule
```
git submodule add https://github.com/visionbites/kirby-usage-reference.git site/plugins/usage-reference
```

### Composer
```
composer require visionbites/usage-reference
```

## Usage
Add a section `usageReference` to your blueprint to show references to the current page.
Add a `template` key to define the type of pages you are looking for.


### Example
Basic setup:

```yaml
sections:
    references:
        headline: References to this page
        type: usageReference
        template: template-name
```

Setup for files:

```yaml
sections:
    file_data:
        type: fields
        fields:
            title:
                type: text
                label: Title
            alt:
                type: text
                label: Alternative title
            caption:
                type: textarea
                label: Image caption
    references:
        headline: References to this file
        type: usageReference
```

## Options

there are only two options at the moment:

| Option   | Default | Description                                                                              |
|----------|---------|------------------------------------------------------------------------------------------|
| `expire` | `15`    | the cache will expire after n minutes. Minimum is 1 as a indefinite cache makes no sense |
| `cache`  | `true`  | if set to false will disable the caching of references                                   |


set the options in `config.php`:

```php
return [
	'visionbites.usage-reference' => [
		'expire' => 15 // in minutes
		'cache' => true
	],
];
```

please keep in mind that this might lead to showing out of date data to the editor if there was another reference added.

## Usage in plugins, models and other places

you can use the `ReferenceService` that is provided in other places to access the data that is presented in the panel.

E.g. if you have events attached to a place that are not children of the place itself you can 
have a `events()` method in the `PlacePage` model:
```php
public function events()
{
	$refService = new ReferenceService();
	// pass the uuid of the place and the template that you are looking for
	$events = $refService->findReferencesForUuid($this->uuid()->toString(), 'event');
	// depending on what you want to do with the events you can either return them here or
	// resolve the pages and return a pages collection
	$eventsPages = new Pages();
	foreach ($events as $event) {
		$eventPage = $this->kirby()->page($event['uuid']);
		$eventsPages->append($eventPage);
	}
	return $eventsPages;
}
```


## todos
- [ ] clear cache for a page on update of a referencing page
- [ ] make it pick up on text links

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia animal abuse, violence or any other form of hate speech.
