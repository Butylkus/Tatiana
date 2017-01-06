#!/usr/bin/python3
#coding=utf-8
#Скрипт для подписчиков канала http://www.youtube.com/user/butylkus
#Лицензия GPL
#Автор: Алексей Butylkus, https://vk.com/butpub

# ========= Импортируем модули, настраиваем их ========= #

import time
from datetime import datetime
import pymysql as MYSQL #используем более короткий синоним, ибо нех
import RPi.GPIO as GPIO
import os, sys
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM) 

version = "0.7.0-0a"
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

def check_button(inpins=inpins, cursor=cursor, logfile=logpath):
    
    #"Слушаем" все входные кнопки из таблицы привязок
    for pin in inpins:
        moment = "" #инициализируем временную отсечку

        #Если кнопка нажата
        if GPIO.input(int(pin[0])) == False:

            #узнаём привязанный выходной пин
            query = "SELECT outpin FROM `button_device` WHERE `inpin`='"+str(pin[0])+"'"
            cursor.execute(query)
            outarray = cursor.fetchone() #Только один!

            #Узнаём текущий статус искомого выходного пина
            query = "SELECT status FROM `pins` WHERE `pin`='"+str(outarray[0])+"'"
            cursor.execute(query)
            status = cursor.fetchone()
            
            #Предформирование строки лога
            logquery = str(pin[0]) + " + " + str(outarray[0]) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n"
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
            #Засекаем момент момент нажатия и игнорим его до конца секунды
            moment = datetime.strftime(datetime.now(), "%H%M%S")
    while moment == datetime.strftime(datetime.now(), "%H%M%S"):
        continue





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
    check_button(inpins, cursor, logpath)

# Проверяем статусы и обрабатываем их
    device(cursor, logpath)

    cursor.close()
    connection.close()
    os.system("sync")
    
    time.sleep(0.05)

# Планировщик срабатывает только один раз в секунду, больше нам и не надо.
    if ThisMoment != datetime.strftime(datetime.now(), "%H:%M:%S"):
        connection = MYSQL.connect(host='localhost', database='tatiana', user='tatiana', password='tatiana')
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
