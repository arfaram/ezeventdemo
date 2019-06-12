## Installation

 - Add this bundle to src/ folder 
 - Register the bundle in AppKernel.php
 ```
 new EzSystems\DemoBundle\EzSystemsDemoBundle(),
 ```
 - Add following to routing.yml
 ```
 ez_systems_demo:
     resource: "@EzSystemsDemoBundle/Resources/config/routing.yml"
     prefix:   /

 ```
 - Add following to config.yml
 ```
 # Webpack Encore Configuration
 webpack_encore:
     builds:
         #....
         demoConfigName: "%kernel.project_dir%/web/assets/demobundle/build"
 ```
 
 - Clear cache
 ```
 bin/console cache:clear
 ```
 - Generate assets
 
 ```
 yarn encore dev
 ```