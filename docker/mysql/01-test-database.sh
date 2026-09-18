#!/bin/sh
set -eu
# Invoked only when the official MySQL image initialises a fresh data volume.
case "$MYSQL_TEST_DATABASE" in
    ''|*[!a-zA-Z0-9_]*) echo 'MYSQL_TEST_DATABASE must contain only letters, digits and underscores' >&2; exit 1 ;;
esac
case "$MYSQL_USER" in
    ''|*[!a-zA-Z0-9_]*) echo 'MYSQL_USER must contain only letters, digits and underscores' >&2; exit 1 ;;
esac
case "$MYSQL_TEST_DATABASE" in
    *_test) ;;
    *) echo 'MYSQL_TEST_DATABASE must end in _test' >&2; exit 1 ;;
esac
if [ "$MYSQL_TEST_DATABASE" = "$MYSQL_DATABASE" ]; then
    echo 'Main and test databases must be different' >&2
    exit 1
fi
MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql --protocol=socket -uroot <<SQL
CREATE DATABASE IF NOT EXISTS \`$MYSQL_TEST_DATABASE\` CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
GRANT ALL PRIVILEGES ON \`$MYSQL_TEST_DATABASE\`.* TO '$MYSQL_USER'@'%';
SQL
