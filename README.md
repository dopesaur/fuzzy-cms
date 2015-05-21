# Fuzzy OctoCMS

One file CMS written in PHP. It's supposed to be in file in production, but in development it's a lot of files.

## One file, huh?

Current build version is in `build` folder, but if you need to make change in development version, you'll need to recompile CMS.

Run: 

```sh
php bin/build_core.php
```

To build the core.

Because it's in development, there's no builder for themes.