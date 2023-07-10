Infosection to display all references to a page in a list.

# Install
## Download Zip file

Copy plugin folder into `site/plugins`

## Composer
not yet available

# Usage
Add a section `usageReference` to your blueprint to show references to the current page.
Add a `template` key to define the type of pages you are looking for.


## Example
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

## todos
- [ ] make it pick up on text links

## License

[MIT](https://opensource.org/licenses/MIT)

It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia animal abuse, violence or any other form of hate speech.
