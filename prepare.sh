#!/bin/bash
./yii migrate/up
./yii migrate --migrationPath=@yii/rbac/migrations
./yii rbac/init
