#!/bin/bash -eux

# Which console commands exist depends on the ORM major: ORM 3 dropped the
# schema conversion, code generation and production settings commands.
run_if_available() {
    local command_name="$1"
    shift

    if ! ./vendor/bin/doctrine-module list --raw | awk '{print $1}' | grep -qx "$command_name"; then
        echo "Skipping ${command_name}: not provided by the installed ORM/DBAL version."
        return 0
    fi

    ./vendor/bin/doctrine-module "$command_name" "$@"
}

mysql -h 127.0.0.1 -u root --password='' database < ci/dummy-import.sql
run_if_available dbal:run-sql "SELECT 1"
run_if_available orm:clear-cache:metadata
run_if_available orm:clear-cache:query
run_if_available orm:clear-cache:result
run_if_available orm:generate-proxies
run_if_available orm:ensure-production-settings
run_if_available orm:info
run_if_available orm:schema-tool:create
run_if_available orm:schema-tool:update
run_if_available orm:validate-schema
run_if_available dbal:run-sql "SELECT COUNT(a.id) FROM entity a"
run_if_available orm:run-dql "SELECT COUNT(a) FROM DoctrineORMModule\Ci\Entity\Entity a"
run_if_available orm:schema-tool:drop --dump-sql
run_if_available orm:schema-tool:drop --force
