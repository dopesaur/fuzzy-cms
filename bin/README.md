# The `bin` folder

This folder is for compiling stuff (core, themes, ~~extensions~~) into one file.

There's a bash script (`build.sh`) to compile everything in the `build` folder.

Run it, from repository root, to compile everything (core, and default and admin themes) into the `build` folder:

```
# Make it
chmod a+x bin/build.sh 

# Build it
./bin/build.sh
```

After that, compiled files should appear in the `build` directory. 