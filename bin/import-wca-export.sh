#!/usr/bin/env bash
set -e

# Download and unzip export.
rm -Rf wca-export
mkdir wca-export
curl https://www.worldcubeassociation.org/export/results/WCA_export.sql.zip --output "wca-export/export.zip"
echo "Unzipping..."
unzip wca-export/export.zip -d wca-export
# Import SQL file into db.
echo "Importing..."
mysql --host="host.docker.internal" --user=root --password=root wca < wca-export/WCA_export.sql