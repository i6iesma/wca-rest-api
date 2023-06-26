#!/usr/bin/env bash
set -e

# Check if there's a new version
##NEW_VERSION=$(curl -s -L https://www.worldcubeassociation.org/api/v0/export/public)
##CURRENT_VERSION="`cat api/version.json`"

##if [ "$NEW_VERSION" == "$CURRENT_VERSION" ]; then
##    echo "No new version detected"
##    exit 0
##fi

# Download and unzip WCA export.
##rm -Rf wca-export
##mkdir wca-export
##curl https://www.worldcubeassociation.org/export/results/WCA_export.sql.zip --output "wca-export/export.zip"
##echo "Unzipping WCA export..."
##unzip wca-export/export.zip -d wca-export
# Import SQL file into db.
##echo "Importing WCA export to database..."
##mysql --host="host.docker.internal" --user=root --password=root wca < wca-export/WCA_export.sql
# Add indexes for faster processing
##mysql --host="host.docker.internal" --user=root --password=root wca -e "CREATE INDEX personId_index ON Results (personId)"
##mysql --host="host.docker.internal" --user=root --password=root wca -e "CREATE INDEX personId_index ON RanksSingle (personId)"
##mysql --host="host.docker.internal" --user=root --password=root wca -e "CREATE INDEX personId_index ON RanksAverage (personId)"

# Delete all existing API files
echo "Building API..."
rm -Rf api/*
# Build API.
bin/console app:api:build