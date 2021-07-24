My code is located in WP standart theme folder - "twentytwenty" you need to select this theme after WP installation:

Front end: `wp-content/themes/twentytwenty/template-parts/content.php`
Back end: `wp-content/themes/twentytwenty/functions.php`
and
`wp-content/themes/twentytwenty/content.json` - this is your JSON file

additionaly added plugins: 
`wp-content/plugins/advanced-custom-fields for` custom meta fields - setup:
![alt text](https://github.com/[username]/[reponame]/blob/[branch]/ACF.jpg?raw=true)

I use win10 for this task and I don't find a good/fast way to install WP-CLI, so
for the main import function, I use just WP init action here `wp-content/themes/twentytwenty/functions.php:61`
so you can just refresh any front page/admin page to init import.
Additionaly you have plugin `wp-content/plugins/wp-bulk-delete` for tests deleted posts.
