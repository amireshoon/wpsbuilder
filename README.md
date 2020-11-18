# WordPress Settings Builder

[![Top Langs](https://github-readme-stats.vercel.app/api/top-langs/?username=amireshoon&layout=compact)](https://github.com/anuraghazra/github-readme-stats)
[![Open Source Love svg3](https://badges.frapsoft.com/os/v3/open-source.svg?v=103)](https://github.com/ellerbrock/open-source-badges/)

WpsBuilder is a tool to create wordpress like setting page or edit page.

  - Can add Menu or submenu
  - Support most of input types
  - Support callback response function

# Supported inputs
All the inputs uses wordpress classes.
  - Text Input
  - Hidden input
  - Text Area
  - Select box
  - Radio Groups
  - CheckBoxes
  - Media Selector (Still in development)
  - Text (p tag)

You can also:
  - Set forms method (post,get,put,...)
  - Set Page and menu Title
  - Set page description

# Installation

WpsBuilder requires [WordPress](https://wordpress.com/) v4+ and [php](https://php.net) v5.6+ to run.

Install using composer.

```php
$ composer require amirhwsin/wpsbuilder
```

or install manually, first download package.

```php
require_once('wpsBuilder/wpsBuilder.php');
```

# Usage

WpsBuilder have simple and easy syntax, you can see some examples for menu or submenu cases.

### Create Menu


```php
$builder = new wpsBuilder();
$builder->addMenu('magical_menu')
        ->setPosition(6)
        ->setCapability('manage_options')
        ->setIconUrl('dashicons-editor-code')
        ->setPageTitle('Magic is real')
        ->setMenuTitle('See Magic')
        ->setPageDescription('This page can do some magics for you.')
        ->setFormMethod('post')
        ->input('pass', 'Password',  array(['key' => 'type', 'value' => 'password']))
        ->textArea('describe_ys', 'Describe yourself to me')
        ->hiddenInput('hidden_value','thats_right')
        ->text('yu','Username', 'amirhwisn *You cant edit this')
        ->checkbox('cbid', 'Do magic can happen?', 'Shall we play magic?', true)
        ->radio('radio', array('field_1' => 'this one', 'field_2' => 'that one'),'Which you choose?', 'that one')
        ->select('selectbox', array('key1'=> 'one', 'key2' => 'two'), 'title', 'two')
        ->media('media_id', 'Choose Profile picture')
        ->build();
```

### Create Submenu
```php
$builder = new wpsBuilder();
$builder->attachToMenu('magical_menu')
         ->setPageTitle('This is a little magic')
         ->setMenuTitle('Menu Title')
         ->setCapability('manage_options')
         ->setMenuSlug('magical_submenu')
         ...
         ->build();
```

### Get Response and store data
You can call ```response``` function and pass a function as an argument and do your stuff.
When you call ```response``` you get a variable from your declared function can called anything thats your data that collected from form.
Remember at the end you should pass an array from your ```function``` that first element is response message like unvalid errors or success message and second one should be ```true``` or ```false``` as success or failed; In case of null passed or nothing passed no messages will be shown at the page.
```php
$builder->response(function($res) {
        print_r($res);
        return array('message', true);
});
```


# Todos

 - Develope media functions
 - Create a usefull document
 - Add more inputs

# License
GPL2

**Free Software, Hell Yeah!**
**Feel free to contribute**
