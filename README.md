# Fuzzy CMS

PHP procedural/functional CMS that can be compressed down to one file or static HTML website.

## "So wut the point?"

The purpose of this CMS is to play well with FTP and git and be efficient.

I know there's deployment systems (based on git), and other cool stuff for cool guys, **but** 
one tiny CMS in a file (in few files, actually, see the disclaimer) with ability to version **everything** is quite cool. Just grab one compressed index.php and create anything you want.

## Features

Fuzzy has following features:

* Very simple (procedural/functional) code
* Can be compressed to one file with extensions and themes (core size is 10-20kb)
* Has a Phar builder
* CMS can be converted to static website (plain .html files)
* Support of different text processors and API for adding processors (Markdown, etc.)
* Extension API

## Pros of using this CMS

Following points are the pros of using this CMS:

1. It's fast, 'cuz there's no real database
2. It's simple, no complicated settings or so, just grab copy and set `777` permission to content folder, and that's it!
3. It's tiny, few files and that's it!
4. Less chances of somebody being dig into your code (haha compressed file)

## Build CMS

Go into console to the repository directory and run: 

```
# Make it
chmod a+x bin/build.sh 

# Build it
./bin/build.sh
```

To build everything (core and two themes).

## Compressed bundled files

There are also already built files which are located in `build` directory.

* `index.php` is core file
* `themes/default.php` is default template
* `themes/admin.php` is admin template

To install this CMS, you need to copy those files (`index.php` and folder `themes`) to your website folder. 

Then you need to copy `content` and `assets` directories to your new website.

# License

```
The MIT License (MIT)

Copyright (c) 2015+ dopesaur <dopesaur5@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
```
