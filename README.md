# Fuzzy CMS

One file CMS written in PHP (not really, see the disclaimer).

## "So wut the point?"

The point is... wait for it... play well with FTP and git, and be efficient, pretty much that.

I know there's deployment systems (based on git), and other cool stuff for cool guys, and bla bla bla, **but** 
one tiny CMS in a file (in few files, actually, see the disclaimer) with ability to version **everything** is quite cool.

What do you think of that?

## Benefits of using this CMS

There are some benefits of using this CMS:

1. ~~No one will try to modify or steal CMS from you~~
2. ~~Sometimes it's tiny, simple, and fast~~

![Reasons to date me](http://cdn.themetapicture.com/pic/images/2014/09/02/funny-reasons-date-Zach-Galifianakis.jpg)

Not really :smile:

There are some **real** benefits of using this CMS: 

1. It's fast, 'cuz there's no real database
2. It's simple, no complicated settings or so, just grab copy and set `777` permission to content folder, and that's it!
3. It's tiny, few files and that's it!
4. Less chances of somebody being dig into your code (haha compressed file, phhh)

That's everything I can think of.

## "I wanna build it myself!"

Go into console to the repository directory and run: 

```
# Make it
chmod a+x bin/build.sh 

# Build it
./bin/build.sh
```

To build everything (core and two themes).

## "No, thanks, I'll take two compressed ones."

There are also already built files which are located in `build` directory.

* `index.php` is core file
* `themes/default.php` is default template
* `themes/admin.php` is admin template

To install this CMS, you need to copy those files (`index.php` and folder `themes`) to your website folder. 

Then you need to copy `content` and `assets` directories to your new website.

# Disclaimer 

This CMS is not actually one file. Its core and themes can be compressed down to one file, but it never would be one file because of `assets` and `content` (How would you put SQLite database in *one file with CMS*?! I'm crazy, but not that way crazy).

# License

License is, of course, a good ol' MIT license (the one that allows to do everything, but I'm not legible for any damage, and you're responsible for everything):

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