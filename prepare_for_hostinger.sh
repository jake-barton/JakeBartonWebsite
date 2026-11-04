#!/bin/bash

# Use the same complete site/game build as the regular deployment mirror.
set -e
exec "$(dirname "$0")/sync_to_hostinger.sh"
