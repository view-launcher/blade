# Laravel Blade View Launcher
A Laravel package for inspecting and opening blade template in your code editor from browsers directly.

## Installation 📦
### 1. Install via composer :

```shell script
composer require yaquawa/laravel-view-launcher
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

### 3. Set your "local base path"
If you are using Docker or Vagrant, to open your view file in your code editor correctly you must provide an absolute path to the project directory in your host machine.

To do so, in your `.env` file, add a new item `LOCAL_BASE_PATH`.

For example :

```dotenv
LOCAL_BASE_PATH=/Users/michael/Documents/htdocs/foobar/
```

## How to Use
Use shortcuts to access all the functions.

Here are the default shortcuts (These are configurable via config file).

* `AA`  →  Inspect view
* `D`  →  Open the view in your code editor

## Configurations

## Specify your Code Editor
By default, you can choose between the following editors.

* PhpStorm
* Sublime
* Visual Studio Code
* Atom
* Textmate
* Emacs
* MacVim
* idea

To make it be able to open file in your editor from browsers directly, you have to set up a "protocol handler" first.
Many editors like PHPStorm or TextMate has protocol handler support by default.

If your favorite code editor doesn't have support for protocol handler, you must set up it by yourself.

To do so, you might want to read [this article](https://tracy.nette.org/en/open-files-in-ide).

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
