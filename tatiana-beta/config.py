#!/usr/bin/python3
#coding=utf-8
# Файл конфигурации. Многие переменные имеют довольно очевидные имена, так что разобраться совсем несложно.

# Путь к файлу лога. ВАЖНО: аналогичный путь должен быть задан в конфигурационном файле фронтенда!
logpath = "/home/pi/tatiana/commonlog.txt"
recordpath = "/home/pi/tatiana/alarmvideos/"

# Подключение к базе данных. Доступы. 
dbhost = 'localhost'
dbbase='tatiana'
dbuser='tatiana'
dbpassword='tatiana'

# Интервал опроса датчиков температуры-влажности, в секундах. По умолчанию 1800 секунд (30 минут)
dht_interval = 1800
