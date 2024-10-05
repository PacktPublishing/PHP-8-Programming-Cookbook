#!/bin/bash
. /tmp/secrets.sh
export FN="$REPO_BACKUP_DIR/$DB_NAM.sql";
echo "Restoring database from $FN ..."
if [[ -f "$FN" ]]; then
    mysql -uroot -ppassword -e "SOURCE $FN;" $DB_NAM
    mysql -uroot -ppassword -e "SOURCE $FN;" "$DB_NAM"_test
fi


