# ZF2 MergeModuleConfig module

This module automated merging config files in modules.

If you have many "....config.php" files in one module, for sample:
<pre>
    - route.config.php
    - service_manger.config.php
    - controller.config.php
</pre>
    
Marge Module Config merged all files in config folder, and you don't need to write some like this 
```php
<?php
    return array(
        include "route.config.php",
        include "service_manger.config.php",
        include "controller.config.php",
    );
```

## Problem

You have some similar file structure:

Application
  - config
    - module.config.php
    - route.config.php
    - service_manger.config.php
    - controller.config.php
  - ....

and some similar code in module.config.php file:

```php
<?php
    return array(
        require 'sebaks-view.config.php',
        require 'router.config.php',
    
       'view_manager' => array(
            'template_path_stack' => array(
                __DIR__ . '/../view',
            ),
        ),
    );
```

## Solution

After including `LabCoding\MergeModuleConfig` your code in module.config.php file will look like:

```php
<?php
    return array(
          'view_manager' => array(
            'template_path_stack' => array(
                __DIR__ . '/../view',
            ),
        ),
    );
```

and file structure will remain the same.


## Installation

Add this project in your composer.json:

```json
"require": {
    "labcoding/merge-module-config": "~0.0.1"
}
```

Now tell composer to download library by running the command:

```bash
$ php composer.phar update
```

OR 

Run command in console

```bash
$ php composer.phar require "labcoding/merge-module-config"
```

#### Post installation

Enabling it in your `application.config.php`file.

```php
<?php
    return array(
        'modules' => array(
            // ...
            'LabCoding\MergeModuleConfig',
        ),
        // ...
    );
```