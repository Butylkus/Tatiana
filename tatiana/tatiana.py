#!/usr/bin/python3.4
#coding=utf-8
#
#
#
# НАСТРОЙКА ПОД СЕБЯ:
# 1. Убедитесь, что существуют и имеют соответствующие права каталоги по умлочанию. Или пропишите свой дефолтный путь (в блоке НАСТРОЙКИ: default_path)
# 2. Пропишите все используемые вами порты GPIO на ввод и вывод 
# 3. Дублируйте нужные функции в блоке ГЛАВНЫЙ ЦИКЛ с нужными вам параметрами (какая кнопка какое устройство включает, по какому плану должны работать определённые устройства)
# 4. Автозапуск описан в README
# 5*. Желательно также заранее создать просто пустые статус-файлы по пути %default_path%/status/XX, где ХХ номера используемых пинов вывода. Можно создать просто подряд с 1 по 27.
#


version = "0.5.1b" 

import time
from datetime import datetime
import RPi.GPIO as GPIO
import os, sys
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM) #Почему-то только в этом режиме

# ------------- НАСТРОЙКИ ----------------

#ВЫХОДНЫЕ контакты (управление реле)
#для моей схемы подключения модуля из 4 реле. Пример работы распиновки: http://www.youtube.com/watch?v=Ln2owTgYv9M&index=4&list=PLTejl8qzLUsQuvwGsrdSC7KPgWu7mahWn
GPIO.setup(17, GPIO.OUT)
GPIO.setup(18, GPIO.OUT)
GPIO.setup(27, GPIO.OUT)
GPIO.setup(22, GPIO.OUT)
GPIO.output(17, 1)
GPIO.output(18, 1)
GPIO.output(27, 1)
GPIO.output(22, 1)

#ВХОДНЫЕ контакты (кнопки-выключатели)
GPIO.setup(21, GPIO.IN) #тестовая кнопка

#Базовый путь к статусам и планам. СОЗДАТЬ РУКАМИ при первом запуске! Добавить подкаталоги status и plans, дать права 777 рекурсивно.
default_path = "/home/pi/.tatiana/"


# ------------- ФУНКЦИИ ----------------

# Функция планировщика. Вклюаети выключает устройства согласно заданному плану.
# Формат плана:
# PIN>ONTIME<OFFTIME\n
# 10>чч:мм:сс<чч:мм:сс\n
# %NEWLINE%
# Файл обязательно должен заканчиваться строкой %NEWLINE% (без конечного \n) - это необходимо для работы веб-интерфейса!
# Вообще лучше не трогать этот файл руками, а пользоваться только веб-интерфейсом.

def plan(planfile=default_path + "plans/plan.txt", statusfile=default_path + "status/", logfile=default_path + "commonlog.txt"):
    planfile = open(planfile, "r")
    #print("Дескриптор:\n", planfile, "\n\n") #дебаг при ручном запуске
    fullplan = planfile.read()
    #print("Содержимое плана: \n", fullplan, "\n\n") #дебаг при ручном запуске
    planstrings = fullplan.split("\n")
    #print("Построчно:\n", planstrings, "\n\n") #дебаг при ручном запуске
    index = len(planstrings)
    
        
    for stringnumber in range(0,index-1):
        pins = planstrings[stringnumber].split(">")
        # Получаем время включения и выключения
        times = pins[1].split("<")
        pin = pins[0] #это номер пина
        ontime = times[0] #Время включения
        offtime = times[1] #Время выключения
        moment = datetime.strftime(datetime.now(), "%H:%M:%S")
        
        #Проверяем время включения, пишем статус при совпадении, включаем девайс
        if moment == ontime :
            f = open(statusfile+pin, "w")
            f.write("0")
            f.close()
            device(int(pin))
            f = open(logfile, "a")
            f.write("%PLANON% " + str(pin) + ">" + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n")
            f.close()
            while moment == datetime.strftime(datetime.now(), "%H:%M:%S"):
                continue
        if moment == offtime :
            f = open(statusfile+pin, "w")
            f.write("1")
            f.close()
            device(int(pin))
            f = open(logfile, "a")
            f.write("%PLANOFF% " + str(pin) + ">" + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n")
            f.close()
            while moment == datetime.strftime(datetime.now(), "%H:%M:%S"):
                continue

#Главная функция. Включает и отключает согласно статусу из соответствующего пину файла
def device(pin):
    f_reader = default_path + "status/"+str(pin) #Цепляем правильный файл
    f = open(f_reader,"r") #читаем статус-файл пина
    status = int(f.read())
    GPIO.output(pin, status) #Выключаем/выключаем устройство
    f.close()


#Детектор кнопок
#Принимает пин кнопки и передаёт на реле инвертированный статус по логике:
#Если кнопка нажата и реле выключено, то включить. Если нажата и реле включено - отключить
def button(pin_in, pin_out, logfile=default_path + "commonlog.txt"):
    if GPIO.input(pin_in) == False:
        f = ""
        #Сверяем и переключаем статус выходного устройства
        path = default_path + "status/"+str(pin_out)
        try:
            f = open(path, "r")
            status = f.read()
            f.close()
        except FileNotFoundError:
            f = open(path, "w")
            status = f.write("0")
            f.close()
            status="0"
        else:
            f = open(path, "r")
            status = f.read()
            f.close()
        #print (status) #Дебаг, отлов нажатия
        f = open(path, "w")
        lfile = open(logfile, "a")
        if status == "0":
            f.write("1")
            lfile.write("%BUTTONOFF% " + str(pin_in) + " > " + str(pin_out) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n")
        if status == "1":
            f.write("0")
            lfile.write("%BUTTONON% " + str(pin_in) + " > " + str(pin_out) + " > " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n")
        lfile.close()
        f.close()
        device(pin_out)
        moment = datetime.strftime(datetime.now(), "%H:%M:%S")
        while moment == datetime.strftime(datetime.now(), "%H:%M:%S"):
            continue



    
    
    
    
    
    
    
    

# ------------- ИСПОЛНЕНИЕ ----------------

f = open(default_path + "commonlog.txt", "a")
f.write("Татьяна проснулась: " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + " \n")
f.close()

# ------------- ГЛАВНЫЙ ЦИКЛ ----------------

while True:
    time.sleep(0.1) #Слегка снижает нагрузку на процессор, сокращая активность до 9-10 проходов в секунду
    
    #Проверяем файл плана и если время для какой-либо операции пришло, функция выполнит нужную операцию
    plan()
    
    #Привязываем кнопку к устройству. Одна кнопка может управлять любыми устройствами. И наоборот, любое устройство может управляться любой кнопкой.
    #КАЖДАЯ кнопка должна быть привязана к устройству, для этого просто дублируем функцию с нужными параметрами
    button(21, 27, default_path + "commonlog.txt") #По сигналу кнопки (пин 21) управляется устройство (27 пин), пишется лог работы кнопки
    
    #Активация веб-интерфейса. Дублируем для каждого устройства, управляемого через веб-интерфейс
    device(17)
    device(18)
    device(22)
    device(27)


# ------------- ВЫХОД ----------------

#Прибираемся при перезагрузке/рестарте
GPIO.cleanup() 
f = open(default_path + "commonlog.txt", "a")
f.write("Татьяна засыпает: " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + " \n")
f.close()
sys.exit()