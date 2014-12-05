# World of Events

[![Build Status](https://travis-ci.org/nfqakademija/worldofevents.svg?branch=master)](https://travis-ci.org/nfqakademija/worldofevents) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nfqakademija/worldofevents/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nfqakademija/worldofevents/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/nfqakademija/worldofevents/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/nfqakademija/worldofevents/?branch=master) [![Deployment status from dploy.io](https://worldofevents.dploy.io/badge/88313865878582/16003.png)](http://dploy.io)

## Requirements

  * NodeJS with NPM
    * `sudo apt-get install nodejs` on Debian based systems
    * Use [Installer from nodejs.org](http://nodejs.org/download/) on Windows
    * `brew install node` on Mac OS X
  * Globally installed Grunt-CLI and Bower packages via NPM
    * `sudo npm install -g grunt-cli bower`
  * Ruby
    * `sudo apt-get install ruby` on Debian based systems
    * Use [Ruby Installer](http://rubyinstaller.org/) on Windows
    * Mac OS X is shipped with Ruby preinstalled
  * Compass
    * `sudo gem install compass`

## Setting up

  * `composer install` to install composer dependencies which are defined in composer.json
  * `npm install` to install node packages which are defined in package.json file
  * `bower install` to install bower components which are defined in bower.json file

## Grunt tasks

  * `grunt watch` runs a watcher which listens for stylesheets or javascripts changes and recompiles them
  * `grunt clean` deletes /web/{css,fonts,img,js} directories
  * `grunt jshint` runs js syntax checker against /app/Resources/scripts/*.js files
  * `grunt concat` concatinates all js files which are defined in /app/Resources/scripts/concat.json
  * `grunt compass` runs compass against /app/Resources/style/ and puts compiled stylesheets to /web/css/
  * `grunt autoprefixer` runs [autoprefixer](https://github.com/postcss/autoprefixer) against /web/css/*.css files
  * `grunt imagemin` optimizes and copies images from /app/Resources/images/ to /web/img/
  * `grunt svgmin` optimizes and copies svg from /app/Resources/images/ to /web/img/ 
  * `grunt uglify` uglify js files in /web/js/ directory
  * `grunt copy` copies bootstrap fonts from bower_components to /web/fonts/
  * `grunt build` runs clean, copy, concat, uglify, compass, autoprefixer, imagemin, svgmin
  * `grunt` runs jshint and build tasks
