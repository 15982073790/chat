#!/usr/bin/env bash
basepath=$(cd `dirname $0`; pwd)
[ $(id -u) != "0" ] && echo "Error: You must be root to run this script" && exit 1
result=$(crontab -l|grep -i "* * * * * sh $basepath/run.sh"|grep -v grep)
if [ ! -n "$result" ]
then
crontab -l > conf && echo "* * * * * sh $basepath/run.sh >/dev/null 2>&1" >> conf && crontab conf && rm -f conf
echo -e "\033[32mOk.\033[0m"
else
echo "The process has been add ."
fi
