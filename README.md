# Fuzzy OctoCMS

One file CMS written in PHP. It's supposed to be in file in production, but in development it's a lot of files.

## One file, huh?

Current build version is in `build` folder, but if you need to make change in development version, you'll need to recompile CMS.

Go in console to the repository directory and run: 

```sh
# Make it executable
chmod a+x bin/build.sh 

./bin/build.sh
```

To build everything.

## Already builded files

There are also already builded files are in `build` directory.

* `index.php` is core file
* `themes/default.php` is default template
* `themes/admin.php` is admin template