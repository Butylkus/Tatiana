#!/usr/bin/python3
#coding=utf-8
#Татьяна - умная домохозяйка
#Программа для ЭВМ, выполняющаяся по команде пользователя или в режиме системной службы ОС GNU/Linux Raspbian.
#Предназначется для считывания и интерпретации электрических импульсов на физических портах GPIO микрокомпьютера Raspberry Pi 2.
#Распространяется свободно на условиях лицензии GNU GPLv2.
#Автор: Алексей Butylkus, https://vk.com/butpub, https://www.youtube.com/user/butylkus


# ========= Импортируем модули, настраиваем их ========= #

import time
from datetime import datetime
import pymysql as MYSQL #используем более короткий синоним, ибо нех
import RPi.GPIO as GPIO
import os, sys
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM) 

version = "0.7.0-1a"
logpath = "/home/pi/tatiana/commonlog.txt"
dbhost = 'localhost'
dbbase='tatiana'
dbuser='tatiana'
dbpassword='tatiana'


# ========= Настраиваем пины согласно базе данных ========= #
# ========= ВАЖНО ========= #
# При перенастройке системы её НЕОБХОДИМО перезапустить!
# Настройки нельзя переопределить на лету, они НЕ вступят в силу до перезапуска данной программы.

connection = MYSQL.connect(host=dbhost, database=dbbase, user=dbuser, password=dbpassword)
cursor = connection.cursor()

#Выходные пины (управляемые)
query = "SELECT pin FROM `pins` WHERE `direction`='output'"
cursor.execute(query)
outpins = cursor.fetchall()
for pin in outpins:
    GPIO.setup(int(pin[0]), GPIO.OUT)

#Входные пины (управляющие) - кнопки и тд
query = "SELECT pin FROM `pins` WHERE `direction`='input'"
cursor.execute(query)
inpins = cursor.fetchall()
for pin in inpins:
    GPIO.setup(int(pin[0]), GPIO.IN, pull_up_down=GPIO.PUD_UP)
    GPIO.add_event_detect(int(pin[0]), GPIO.FALLING, bouncetime=200)

cursor.close()
connection.close()


ThisMoment = datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")



# ========= Главные функции ========= #

### Проверяет и включает-выключает устройства согласно записям в БД
def device(cursor, logfile=logpath):
    query = "SELECT pin, status FROM `pins` WHERE `direction`='output'"
    cursor.execute(query)
    string = cursor.fetchall()
    for pairs in string:
        GPIO.output(int(pairs[0]), pairs[1])



### Обработчик плана. Обновляет статусы при наступлении запланированного момента

def check_plan(cursor, logfile=logpath):
    query = "SELECT pin, ontime, offtime, calendar FROM `plan`"
    cursor.execute(query)
    planarray = cursor.fetchall()
    ThisMoment = datetime.strftime(datetime.now(), "%H:%M:%S")
    for moment in planarray:
        
#Проверяем календарь: 1 = только по будням, 2 = только по выходным, 3 = ежедневно
        operable = False
        if moment[3] == 1 and (datetime.isoweekday(datetime.now()) <= 5):
            operable = True
        elif moment[3] == 2 and (datetime.isoweekday(datetime.now()) >= 6):
            operable = True
        elif moment[3] == 3:
            operable = True
        else:
            operable = False
        
        if operable == True:
            pin = moment[0]
            ontime = moment[1]
            offtime = moment[2]

#Если настал момент включения/выключения, то пишем в базу соответствующую пару пин-статус, а также делаем запись в логе
            f = open(logfile, "a")
            if ThisMoment == ontime:
                query = "UPDATE `pins` SET `status`=1 WHERE `pin`='" + str(pin) + "'"
                f.write("%PLANON% " + str(pin) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n")
            if ThisMoment == offtime:
                query = "UPDATE `pins` SET `status`=0 WHERE `pin`='" + str(pin) + "'"
                f.write("%PLANOFF% " + str(pin) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n")
            f.close()
            cursor.execute(query)
            connection.commit()




### Обработчик кнопок. Обновляет статус при нажатии на кнопку согласно привязкам в БД

def button(inpin, cursor=cursor, logfile=logpath):
    #узнаём привязанный выходной пин
    query = "SELECT outpin FROM `button_device` WHERE `inpin`='"+str(inpin)+"'"
    cursor.execute(query)
    outarray = cursor.fetchone() #Только один!
    #Узнаём текущий статус искомого выходного пина
    query = "SELECT status FROM `pins` WHERE `pin`='"+str(outarray[0])+"'"
    cursor.execute(query)
    status = cursor.fetchone()
    #Предформирование строки лога
    logquery = str(inpin) + " + " + str(outarray[0]) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n"
    if status[0] == 1:
        logquery ="%BUTTONOFF% " + logquery #Доформируем строку лога
        status = 0
    elif status[0] == 0:
        logquery ="%BUTTONON% " + logquery #Доформируем строку лога
        status = 1
    #Обновляем статус для привязанного выходного пина
    query = "UPDATE `pins` SET `status`='" + str(status) + "' WHERE `pin`='" + str(outarray[0]) + "'"
    cursor.execute(query)
    connection.commit()
    #Пишем лог
    lfile = open(logfile, "a")
    lfile.write(logquery)
    lfile.close()






# ========= Главная программа ========= #

# Пишем в лог время старта скрипта
f = open(logpath, "a")
f.write("%UP% > " + str(ThisMoment) + " \n")
f.close()

# Главный вечный цикл
while True:

# Подключаемся к БД
    connection = MYSQL.connect(host=dbhost, database=dbbase, user=dbuser, password=dbpassword)
    cursor = connection.cursor()

# Ловим нажатие кнопок
    for pin in inpins:
        #Если кнопка нажата
        if GPIO.event_detected(int(pin[0])):
            button(pin[0], cursor)

# Проверяем статусы и обрабатываем их
    device(cursor, logpath)

    cursor.close()
    connection.close()

    
    time.sleep(0.05)

# Планировщик срабатывает только один раз в секунду, больше нам и не надо.
    if ThisMoment != datetime.strftime(datetime.now(), "%H:%M:%S"):
        connection = MYSQL.connect(host=dbhost, database=dbbase, user=dbuser, password=dbpassword)
        cursor = connection.cursor()
        ThisMoment = datetime.strftime(datetime.now(), "%H:%M:%S")
#        print (str(ThisMoment))
        check_plan(cursor, logpath)
        cursor.close()
        connection.close()
    else:
        continue


# ========= Прибираемся при выходе ========= #

GPIO.cleanup() 
f = open(default_path + "commonlog.txt", "a")
f.write("%DOWN% > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + " \n")
f.close()
sys.exit()
