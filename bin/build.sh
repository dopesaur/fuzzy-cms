#!/bin/bash

php bin/build_core.php
php bin/build_template.php 'default'
php bin/build_template.php 'admin'