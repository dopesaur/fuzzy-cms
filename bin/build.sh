#!/bin/bash

php bin/build_core.php
php bin/build_theme.php "default"
php bin/build_theme.php "admin"