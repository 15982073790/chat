#!/usr/bin/env bash
basepath=$(cd `dirname $0`; pwd)
command="php $basepath/ymwl_pusher/start.php start -d"
result=$(ps -ef | grep -i workerman | grep -v grep)
if [ ! -n "$result" ]
then
echo "Starting the process."
str=$(nohup $command >/dev/null 2>&1 &)
echo -e "\033[32mOk.\033[0m"
else
echo "The process has been started ."
fi
