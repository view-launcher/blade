# Laravel Blade View Launcher
A Laravel package for inspecting and opening blade template in your code editor from browsers directly.

## Installation 📦
### 1. Install via composer :

```shell script
composer require --dev view-launcher/blade-view-launcher
php artisan view:clear
```

### 2. Include the assets
Put the `@viewLauncherAssets` into your `head` element. Be sure place `@viewLauncherAssets` after your **last script tag**.

```blade
...
    <script defer src="/your-scripts.js"></script>
    @viewLauncherAssets
</head>
```

### 3. Set your "local view directory path"
If you are using Docker or Vagrant, to open your view file in your code editor correctly you must provide an absolute path to the view directory in your ***host machine***.

To do so, in your `.env` file, add a new item `LOCAL_VIEW_DIR`.

For example :

```dotenv
LOCAL_VIEW_DIR=/Users/michael/Documents/htdocs/laravel/resources/views
```

### 4. Specify your Code Editor
By default, PhpStorm is set to the default code editor, you can change it by adding a new item `LOCAL_VIEW_DIR` in your `.env` file.

For example:
```dotenv
VIEW_LAUNCHER_EDITOR=vscode
```

See the list of supported editors at [here](https://github.com/view-launcher/view-launcher#supported-editors).

To make it be able to open file in your editor from browsers directly, you have to set up a "protocol handler" first.
Many editors like PHPStorm or TextMate has protocol handler support by default.

If your favorite code editor doesn't have support for protocol handler, you must set up it by yourself.

To do so, you might want to read [this article](https://tracy.nette.org/en/open-files-in-ide).

## Usage
Use shortcuts to access all the functions.

Here are the default shortcuts (These are configurable via the config file).

* `AA`  →  Inspect view
* `D`  →  Open the view in your code editor

## Limitation
Tags without new line won't get detected.
This is because tags are detected via a regular expression which requires to match an open tag followed by a new line character.

For example:

```html
<!-- does not match -->
<div>foo</div>

<!-- matches -->
<div>
    foo
</div>

<!-- matches -->
<img src="" alt="">

<!-- matches -->
<a href="/foo">
    <img src="/foo.jpg">
</a>

<!-- does not match -->
<a href="/foo"><img src="/foo.jpg"></a>
```
