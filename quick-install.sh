#!/usr/bin/env bash
set -euo pipefail

echo "Mengunduh source code ILDIS dari Pazella21..."
git clone https://github.com/bphndigitalservice/ildis.git /tmp/ildis-source
cd /tmp/ildis-source

bash install.sh "$@"
