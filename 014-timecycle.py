#!/usr/bin/python3.4
#coding=utf-8

import time
from datetime import datetime
import RPi.GPIO as GPIO
import os
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM) #Почему-то только в этом режиме


#для моей схемы подключения модуля из 4 реле. Пример работы распиновки: http://www.youtube.com/watch?v=Ln2owTgYv9M&index=4&list=PLTejl8qzLUsQuvwGsrdSC7KPgWu7mahWn
GPIO.setup(17, GPIO.OUT)
GPIO.setup(18, GPIO.OUT)
GPIO.setup(22, GPIO.OUT)
GPIO.setup(27, GPIO.OUT)
GPIO.output(17, 1)
GPIO.output(18, 1)
GPIO.output(22, 1)
GPIO.output(27, 1)

f = open("commonlog.txt", "a")
f.write("Перезапуск скрипта: " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + " \n")
f.close()

#Считывает план и возвращает список. Вызывается по ходу выполнения в контексте "по ЭТОМУ плану" для функций device_off и device_on
def planreader(plan):
    list = []
    f = open(plan, "r")
    line = f.read()
    list = line.split("\n")
    f.close()
    return list

#print (planreader("ONplan.txt")) #debug
#print (planreader("OFFplan.txt")) #debug

#print("Текущее время: ", datetime.strftime(datetime.now(), "%H:%M:%S")) #контроль времени

#Функция вЫключения. Принимает пин, план и логфайл
#Пин указывать номером согласно базовой схеме подключения. Если указать не тот, то неверное устройство будет работать по неверному плану.
#Логфайл лучше всего именовать по имени девайса, например, LampaLog.txt.
#В будущем лучше сделать единый лог без параметра функции, но уже сейчас можно писать в один файл, просто указав его для всех вызовов
def device_off(pin, offplan, logfile="commonlog.txt"):
    for moment in planreader(offplan): #Дадада, я псих, рекурсия в наличии. Вызывает считыватель плана ещё раз на всякий пожарный
        if moment == datetime.strftime(datetime.now(), "%H:%M:%S"):
            GPIO.output(pin,1)
            print("Выключено в ", moment) #при ручном запуске лучше раскомментировать, чтобы не смотреть в логи
            #Пишем в лог
            f = open(logfile, "a")
            f.write("Устройство на пине " + str(pin) + " выключено " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n")
            f.close()
    
#Функция Включения. Принимает пин, план и логфайл
#полностью аналогична предыдущей, только включает, а не выключает.
def device_on(pin, onplan, logfile="commonlog.txt"):
    for moment in planreader(onplan): #Дадада, я псих, рекурсия в наличии. Вызывает считыватель плана ещё раз на всякий пожарный
        if moment == datetime.strftime(datetime.now(), "%H:%M:%S"):
            GPIO.output(pin,0)
            print("Включено в ", moment) #при ручном запуске лучше раскомментировать, чтобы не смотреть в логи
            #Пишем в лог
            f = open(logfile, "a")
            f.write("Устройство на пине " + str(pin) + " включено " + str(datetime.strftime(datetime.now(), "%d.%m.%Y %H:%M:%S")) + "\n")
            f.close()



while True:
    time.sleep(0.1) #Слегка снижает нагрузку на процессор, сокращая активность до 9-10 проходов в секунду
    
    #----БЛОК РАЗМНОЖИТЬ ДЛЯ КАЖДОГО НУЖНОГО УСТРОЙСТВА---
    #Смотрим указанный план и вызываем функцию включения и выключения
    #План для КАЖДОГО устройства должен существовать!
    #Включение
    for moment in planreader("ONplan.txt"):
        if moment == datetime.strftime(datetime.now(), "%H:%M:%S"):
            #Вызываем функцию включения
            device_on(18, "ONplan.txt", "commonlog.txt")
            #Подавляет актвность блока после первой отработки в эту же секунду, иначе загадит лог очередным вызовом функции. На всякий случай лучше не использовать одно и то же время для всех устройств, мало ли что...
            while moment == datetime.strftime(datetime.now(), "%H:%M:%S"):
                continue
    #Выключение
    for moment in planreader("OFFplan.txt"):
        if moment == datetime.strftime(datetime.now(), "%H:%M:%S"):
            #Вызываем функцию включения
            device_off(18, "OFFplan.txt", "commonlog.txt")
            #Подавляет актвность блока после первой отработки в эту же секунду, иначе загадит лог очередным вызовом функции. На всякий случай лучше не использовать одно и то же время для всех устройств, мало ли что...
            while moment == datetime.strftime(datetime.now(), "%H:%M:%S"):
                continue
    #----Конец размножаемого блока---


