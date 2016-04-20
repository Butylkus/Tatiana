#!/usr/bin/python3.4
#coding=utf-8

import time
from datetime import datetime
import RPi.GPIO as GPIO
import os
GPIO.setwarnings(False)
GPIO.setmode(GPIO.BCM) #Почему-то только в этом режиме

sw2 = 18

GPIO.setup(sw2, GPIO.OUT)
GPIO.output(sw2, 1)

def planreader(plan):
    list = []
    f = open(plan, "r")
    line = f.read()
    list = line.split("\n")
    f.close()
    return list

print (planreader("ONplan.txt"))
print (planreader("OFFplan.txt"))


while True:
    for moment in planreader("ONplan.txt"):
        if moment == datetime.strftime(datetime.now(), "%M:%S"):
            GPIO.output(sw2,0)
            print("Включено в ", moment)
            while moment == datetime.strftime(datetime.now(), "%M:%S"):
                continue
    print("Текущее время: ", datetime.strftime(datetime.now(), "%H:%M:%S"))

    for moment in planreader("OFFplan.txt"):
        if moment == datetime.strftime(datetime.now(), "%M:%S"):
            GPIO.output(sw2,1)
            print("Выключено в ", moment)
            while moment == datetime.strftime(datetime.now(), "%M:%S"):
                continue


