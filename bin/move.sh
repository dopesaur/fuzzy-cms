#!/bin/sh

if [ ! -f ~index.php ]; then
    mv index.php ~index.php
fi

mv build/themes/default.php themes/default.php
mv build/themes/admin.php themes/admin.php
mv build/index.php index.php