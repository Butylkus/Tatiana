#!/usr/bin/python3
#coding=utf8
#Татьяна - умная домохозяйка
#Конфигурационный скрипт для Татьяны
#Распространяется свободно на условиях лицензии GNU GPLv2.
#Автор: Алексей Butylkus, https://vk.com/butpub, https://www.youtube.com/user/butylkus


# ========= Импортируем модули ========= #

import time
import os, sys, getpass
from datetime import datetime
import pymysql as MYSQL #используем более короткий синоним, ибо нех

#создание базы
def dbcreate(dbuser, dbpass):
    print("Создаю базу данных tatiana...")
    db = MYSQL.connect(host="localhost", user=dbuser, password=dbpass, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    cursor.execute("CREATE DATABASE IF NOT EXISTS tatiana COLLATE utf8_unicode_ci CHARACTER SET utf8")
    db.commit()
    db.close()
    print("База создана!")


#Заливка дампа
def dbimport(dbuser, dbpass):
    print("Загружаю образец...")
    dump = open('./tatiana.sql', 'r')
    string = dump.read()
    db = MYSQL.connect(host="localhost", user=dbuser, password=dbpass, database="tatiana", use_unicode=True, charset="utf8")
    cursor = db.cursor()
    print("Наполняю базу...")
    cursor.execute(string)
    db.commit()
    db.close()
    print("Готово!")

#Создание пользователя с правами
def dbadduser(dbuser, dbpass):
    print("Создаю пользователя БД согласно конфигурации...")
    db = MYSQL.connect(host="localhost", user=dbuser, password=dbpass, database="tatiana", use_unicode=True, charset="utf8")
    cursor = db.cursor()
    string = "CREATE USER '" + config.dbuser + "'@'localhost' IDENTIFIED BY '" + config.dbpassword + "';"
    cursor.execute(string)
    db.commit()
    print("OK, пользователь создан!")
    print("Задаём ему права на использование базы...")
    string = "GRANT SELECT, INSERT, UPDATE, DELETE, ALTER ON tatiana.* TO '" + config.dbuser + "'@'localhost' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;"
    cursor.execute(string)
    db.commit()
    db.close()
    print("Готово!")
    
#Большая главная функция
def mainmenu(cursor):
    os.system("clear")
    print("""======== ГЛАВНОЕ МЕНЮ ========
Навигация осуществляется через ввод цифр. Татьяна олдскульная!
Разделы управления:
    1. Пользователи (для веб-интерфейса)
    2. Пины (имена и разводка, что куда подключено)
    3. Одинокие кнопки
    4. Блочные устройства (1 кнопка = 2 выхода)
    5. Датчики климата
    6. Охранная система
    0. Выход
Введите номер и нажмите [ВВОД]""")
    task = str(input("# :> "))
    print (task)
    if (task == "1"):
        print ("Юзерс")
    if (task == "2"):
        print ("Глагне таблицо")
    if (task == "3"):
        print ("Кнопулюшечки")
    if (task == "4"):
        print ("Люстрорули")
    if (task == "5"):
        print ("ДыХоТа")
    if (task == "6"):
        print ("Алярмы")
    if (task == "0"):
        os.system("clear")
        sys.exit("Ну покедова! Заглядывай ещё =)")




#Возврат таблицы pins








# ========= Проверка существования конфига и БД ========= #
os.system("clear")
print("""========
   Импортируем конфигурационный скрипт config.py ...
========""")
try:
    import config
    print("""   OK, конфигурация в наличии.""")
except ImportError:
    print (
    """ЕГГОГ! Что-то пошло не так =(
    Убедитесь, что:
        - файл config.py существует;
        - находится в этом же каталоге.
    Если что, вот образец: https://github.com/Butylkus/Tatiana/blob/develop/tatiana-beta/config.py
    Приведите файл в порядок и перезапустите этот скрипт.
========""")
    exit(0)
time.sleep(3)

#Добавим пафоса...
os.system("clear")
print (
"""========
Приступаем к конфигурированию Татьяны!
========""")
time.sleep(3)
os.system("clear")


print("""
======== КОНФИГУРИРУЕМ ТАТЬЯНУ ========
Введите данные для доступа к БД:
""")
dbuser=str(input("Логин (обычно root): "))
dbpass=getpass.getpass("Пароль: ")


############################################
### Проверка наличия базы
############################################

print("""
======== ПРОВЕРЯЕМ НАЛИЧИЕ БАЗЫ ДАННЫХ ========
Используем предоставленные доступы для проверки.
Ждите...
""")

try:
    db = MYSQL.connect(host="localhost", database="tatiana", user=dbuser, password=dbpass, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    db.close()
except:
    print("""
========
Еггог!
Видимо, у нас нет базы данных! \(О_о)/
* Можете импортировать самостоятельно через клиент MySQL или PHPMyAdmin,
но мы можем попытаться сделать это сейчас.
Попробуем импортировать образцовую базу?
** Убедитесь, что образец tatiana.sql где-то рядом
""")
    permit = str(input("Скажите yes или no: "))
    if (permit == "yes"):
        dbcreate(dbuser=dbuser, dbpass=dbpass)
        dbimport(dbuser=dbuser, dbpass=dbpass)
    else :
        os.system("clear")
        sys.exit("Ну как скажете... Вы держитесь там, всего доброго")

        

############################################
### Проверка наличия пользователя
############################################

print("""
======== ПРОВЕРЯЕМ ДОСТУП ДЛЯ ТАТЬЯНЫ ========
Пробуем подключиться к БД так, словно это делает Татьяна.
Ждите...
""")

try:
    dbtat = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
    cursor = dbtat.cursor()
    dbtat.close()
except:
    print("""
========
Еггог!
Вот это прикол, Татьяна не может подключиться к БД! \(О_о)/
Можете добавить пользователя БД согласно config.py вручную, но
может стоит попробовать сделать это автоматически?
""")
    permit = str(input("Скажите yes или no: "))
    if (permit == "yes"):
        dbadduser(dbuser=dbuser, dbpass=dbpass) #это запрашивалось ранее
    else :
        os.system("clear")
        sys.exit("Ну как скажете... Вы держитесь там, всего доброго")

############################################
### Теперь у нас есть база,
### продолжаем настройку
############################################


#цепляемся к базе
print("""
======== ПРИГОТОВЬТЕСЬ ========
Начнём через пару секунд!
""")
time.sleep(3)


#цепляемся к базе
db = MYSQL.connect(host="localhost", database="tatiana", user=dbuser, password=dbpass, use_unicode=True, charset="utf8")
cursor = db.cursor()

#запускаем главную функцию
mainmenu(cursor)


#цепляемся к базе
#цепляемся к базе
#цепляемся к базе
#цепляемся к базе
#цепляемся к базе








# примеры работы с БД, памятка
#db = MYSQL.connect(host="localhost", database="tatiana", user=dbuser, password=dbpass, use_unicode=True, charset="utf8")
# подключение как в конфиге:
#db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
#cursor = db.cursor()
#query = "SELECT pin FROM `pins` WHERE `direction`='output'"
#query = "UPDATE `pins` SET `status`=1 WHERE `pin`='" + str(pin) + "'"
#cursor.execute(query)
#connection.commit()


