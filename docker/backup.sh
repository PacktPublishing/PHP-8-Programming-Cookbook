#!/bin/bash
. /tmp/secrets.sh
echo "Backing up database ..."
export FN="$REPO_BACKUP_DIR/$DB_NAM.sql";
export TEST_FN="$REPO_BACKUP_DIR/$DB_NAM.sql";
mysqldump -uroot -ppassword "$DB_NAM" > "$FN"
cp $FN "$REPO_BACKUP_DIR/$DB_NAM"_`date +%Y-%m-%a`.sql
