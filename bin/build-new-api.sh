#!/usr/bin/env bash
set -e

# Download and unzip WCA export.
rm -Rf wca-export
mkdir wca-export
curl https://www.worldcubeassociation.org/export/results/WCA_export.sql.zip --output "wca-export/export.zip"
echo "Unzipping WCA export..."
unzip wca-export/export.zip -d wca-export
# Import SQL file into db.
echo "Importing WCA export to database..."
mysql --host="host.docker.internal" --user=root --password=root wca < wca-export/WCA_export.sql
# Delete all existing API files
echo "Building API..."
rm -Rf 'api/*'
# Build API.
bin/console app:api:build