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
import os, sys, signal
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM) 

version = "0.7.1-1a"
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

#Входные пины (управляющие) - кнопки для одиночных устройств
query = "SELECT pin FROM `pins` WHERE `direction`='input'"
cursor.execute(query)
inpins = cursor.fetchall()
for pin in inpins:
    GPIO.setup(int(pin[0]), GPIO.IN, pull_up_down=GPIO.PUD_UP)
    GPIO.add_event_detect(int(pin[0]), GPIO.FALLING, bouncetime=200)

#Входные пины (управляющие) - для блочных устройств кнопки вынесены в отдельный тип
query = "SELECT pin FROM `pins` WHERE `direction`='block'"
cursor.execute(query)
blockpins = cursor.fetchall()
for pin in blockpins:
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



### Обработчик блочных кнопок. Обновляет статусы по циклу при нажатии на кнопку согласно привязкам в БД button_block

def button_block(inpin, cursor=cursor, logfile=logpath):
    status_roll=[[0,0],[0,1],[1,0],[1,1]] #ролл-список статусов
    outarray = ()
    #узнаём привязанный выходной пин
    query = "SELECT outpin FROM `button_block` WHERE `inpin`='"+str(inpin)+"'"
    cursor.execute(query)
    outarray = cursor.fetchall() #забираем все выходные пины
    outarray = accu_list(outarray) #делаем аккуратный список выходных пинов
    statuses=[]
    for pin in outarray:
        query = "SELECT status FROM `pins` WHERE `pin`='"+str(pin)+"'"
        cursor.execute(query)
        statuses.append(cursor.fetchone())
    statuses = accu_list(statuses) #делаем аккуратный список статусов
    print(statuses)
    state = status_roll.index(statuses) #номер комбинации пинов в ролл-списке
    if state == 0:
        logquery = "%BUTTONON% " + str(inpin) + " + " + str(outarray[1]) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n"
        query = "UPDATE `pins` SET `status`='1' WHERE `pin`='" + str(outarray[1]) + "';"
    elif state == 1:
        logquery = "%BUTTONON% " + str(inpin) + " + " + str(outarray[0]) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n"
        logquery = logquery + "%BUTTONOFF% " + str(inpin) + " + " + str(outarray[1]) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n"
        query = "UPDATE `pins` SET `status`='1' WHERE `pin`='" + str(outarray[0]) + "';"
        query = query + "UPDATE `pins` SET `status`='0' WHERE `pin`='" + str(outarray[1]) + "';"
    elif state == 2:
        logquery = "%BUTTONON% " + str(inpin) + " + " + str(outarray[1]) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n"
        query = "UPDATE `pins` SET `status`='1' WHERE `pin`='" + str(outarray[1]) + "';"
    else:
        logquery = "%BUTTONOFF% " + str(inpin) + " + " + str(outarray[0]) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n"
        logquery = logquery + "%BUTTONOFF% " + str(inpin) + " + " + str(outarray[1]) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n"
        query = "UPDATE `pins` SET `status`='0' WHERE `pin`='" + str(outarray[0]) + "';"
        query = query + "UPDATE `pins` SET `status`='0' WHERE `pin`='" + str(outarray[1]) + "';"
    #Пишем лог
    lfile = open(logfile, "a")
    lfile.write(logquery)
    lfile.close()
    #и в базу
    cursor.execute(query)
    connection.commit()




### Слияние всяких списков в аккуратную линию

def accu_list(array):
    result=[]
    for element in array:
        result.append(element[0])
    return result



### Выключатель демона

def stop(signum, frame, logfile=logpath):
    GPIO.cleanup() 
    f = open(logfile, "a")
    f.write("%DOWN% > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n")
    f.close()
    sys.exit("STOPPED BY SIGTERM")
    






# ========= Главная программа ========= #

# Пишем в лог время старта скрипта
f = open(logpath, "a")
f.write("%UP% > " + str(ThisMoment) + "\n")
f.close()
signal.signal(signal.SIGTERM, stop)

# Подключаемся к БД
connection = MYSQL.connect(host=dbhost, database=dbbase, user=dbuser, password=dbpassword)
cursor = connection.cursor()

# Главный вечный цикл
while True:

# Ловим нажатие кнопок - одиночные
    for pin in inpins:
        #Если кнопка нажата
        if GPIO.event_detected(int(pin[0])):
            button(pin[0], cursor)

# Ловим нажатие кнопок - блочные
    for pin in blockpins:
        #Если кнопка нажата
        if GPIO.event_detected(int(pin[0])):
            button_block(pin[0], cursor)

# Проверяем статусы и обрабатываем их
    device(cursor, logpath)

# Спим, снижая нагрузку на сервер
    time.sleep(0.05)

# Планировщик срабатывает только один раз в секунду, больше нам и не надо.
    if ThisMoment != datetime.strftime(datetime.now(), "%H:%M:%S"):
        ThisMoment = datetime.strftime(datetime.now(), "%H:%M:%S")
        check_plan(cursor, logpath)
    else:
        continue



