#!/usr/bin/env bash
set -e

if [ $# -lt 1 ]; then
    echo -e "${COLOR_RED}Provide a comma separated list of the APIs you want to rebuild ${NC}"
    exit 1;
fi

APIS_TO_REBUILD=$1

COLOR_RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if there's a new version
NEW_VERSION=$(curl -s -L https://www.worldcubeassociation.org/api/v0/export/public)
CURRENT_VERSION="`cat api/version.json 2>/dev/null`"

if [ "$NEW_VERSION" == "$CURRENT_VERSION" ]; then
    echo "No new version detected, exiting, bye."
    ##exit 0
fi

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

if [[ "$APIS_TO_REBUILD" == *"country"* ]]; then
  rm -Rf api/countries.json
fi
if [[ "$APIS_TO_REBUILD" == *"event"* ]]; then
  rm -Rf api/events.json
fi
if [[ "$APIS_TO_REBUILD" == *"competition"* ]]; then
  rm -Rf api/competition*
fi
if [[ "$APIS_TO_REBUILD" == *"person"* ]]; then
  rm -Rf api/person*
fi
if [[ "$APIS_TO_REBUILD" == *"rank"* ]]; then
  rm -Rf api/rank*
fi
if [[ "$APIS_TO_REBUILD" == *"result"* ]]; then
  rm -Rf api/result*
fi

# Build API.
bin/console app:api:build $APIS_TO_REBUILD