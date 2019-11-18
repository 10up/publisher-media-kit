#!/bin/bash

# Catch Errors
set -euo pipefail

rsync  -vrxc --delete --dry-run ./ gitlab@10uplabs.com:/var/www/html/wordpress/publishermediakit/wp-content/plugins/publisher-media-kit/  --exclude-from=$CI_PROJECT_DIR/deploy-scripts/rsync-excludes-10uplabs.txt
