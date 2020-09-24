#!/bin/bash
# Запуск тестов

CURRENDIR=pwd
DIRNAME=$(dirname $0)
cd ${DIRNAME}
./vendor/bin/codecept run
cd ${CURRENTDIR}