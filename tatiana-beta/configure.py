#!/usr/bin/python3
#coding=utf8
#Татьяна - умная домохозяйка
#Конфигурационный скрипт для Татьяны
#Распространяется свободно на условиях лицензии GNU GPLv2.
#Автор: Алексей Butylkus, https://vk.com/butpub, https://www.youtube.com/user/butylkus


# ========= Импортируем модули ========= #

import time
import os, sys, getpass, hashlib, random
from datetime import datetime
import pymysql as MYSQL #используем более короткий синоним, ибо нех


global stop #флаг остановки, нужен для выхода из конфигуратора
stop = False

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
    task = str(input("# > "))
    if (task == "1"):
        userlist() #вызываем список пользователей и операции
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
        stop=True
        os.system("clear")
        sys.exit("Ну покедова! Заглядывай ещё =)")
        return




#управляем пользователями
def userlist():
    db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    task = "spam" #объявим переменную для условного выхода
    while (task != "0"):
        os.system('clear')
        print("""======== УПРАВЛЕНИЕ ПОЛЬЗОВАТЕЛЯМИ ========
Этот раздел позволяет управлять пользователями.
Вот список всех, кто имеет доступ в систему. Если вы видите что-то, что не предусматривалось ранее... ВРЕМЯ БИТЬ ТРЕВОГУ!""")
        query = "SELECT * FROM users;"
        cursor.execute(query)
        all_users = cursor.fetchall()
        len1=1 #ширина поля ID
        len2=3 #ширина поля login
        len3=3 #ширина поля username
        #Определяем максимальные значения для красивой таблицы
        for user in all_users:
            if (len1 < len(str(user[0]))):
                len1 = len(str(user[0]))
            if (len2 < len(user[1])):
                len2 = len(user[1])
            if (len3 < len(user[3])):
                len3 = len(user[3])
        #Рисуем таблицу
        print("====\nID, логины и имена пользователей")
        border="+-"+"-"*len1+"-+-"+"-"*len2+"-+-"+"-"*len3+"-+"
        print(border)
        for user in all_users:
            out = "| "+ str(user[0])+" "*(len1-len(str(user[0])))+" | "+user[1]+" "*(len2-len(user[1]))+" | "+user[3]+" "*(len3-len(user[3]))+" |"
            print(out)
        print(border)
        print("""=====
Введите:
    ID (столбец 1) пользователя для редактирования
    0 для возврата
    add - добавить нового пользователя""")
        task=input("# > ")
        if (task == "0"):
            return
        if (task == "add"):
            query = newuser() #вызываем функцию добавляения пользователя
            cursor.execute(query)
            db.commit()
            print("Отлично, одним пользователем больше!")
            time.sleep(3)
            continue
        os.system('clear')
        query = "SELECT * FROM users WHERE user_id="+task+";"
        cursor.execute(query)
        user = cursor.fetchone()
        #Запишем явные переменные для простоты манипуляций
        userid = user[0]
        login = user[1]
        username = user[3]
        password=user[2]
        print("Пользователь #{0} под логином {1} и именем {2}\nЧто делать с ним?".format(userid,login,username))
        print("""Вот что можно сделать:
    1. Изменить логин
    2. Изменить имя
    3. Изменить пароль
    4. УДАЛИТЬ
    0. Вернуться""")
        task_edit=input("# > ")
        if (task_edit == "0"):
            db.commit()
            continue
        elif (task_edit == "1"):
            print("Изменение логина. Это то, что необходимо вводить при входе в веб-интерфейс вместе с паролем. Например, supervisor")
            string="Было "+login+", станет: >"
            newlogin = input(string)
            query = "UPDATE users SET login='{0}' WHERE login='{1}';".format(newlogin,login)
            cursor.execute(query)
            db.commit()
            print("Готово, новый логин записан!\nПереходим обратно в меню пользователей")
            time.sleep(3)
            continue
        elif (task_edit == "2"):
            print("Изменение имени. Это то, что показывается в приветствии веб-интерфейса в меню.")
            string="Было "+username+", станет: >"
            newusername = input(string)
            query = "UPDATE users SET username='{0}' WHERE username='{1}';".format(newusername,username)
            cursor.execute(query)
            db.commit()
            print("Готово, пользователь переименован!\nПереходим обратно в меню пользователей")
            time.sleep(3)
            continue
        elif (task_edit == "4"):
            print("УДАЛЕНИЕ ПОЛЬЗОВАТЕЛЯ #{0} - {1}, известного как {2}".format(userid,login,username))
            code = str(random.randint(100,999)) #для подтверждения простого согласия мало. Попросим ввести код
            string="Вы уверены?! Введите {0}, если уверены: >".format(code)
            confirm = input(string)
            if (confirm == code):
                query = "DELETE FROM users WHERE user_id={0};".format(userid)
                cursor.execute(query)
                db.commit()
                print("Готово, пользователь #{0} - {1}, известнй ранее как {2}, больше не имеет доступа в систему!\nПереходим обратно в меню пользователей".format(userid,login,username))
            else:
                print("Уффф, пользователь #{0} - {1}, известнй как {2}, не пострадал...\nПереходим обратно в меню пользователей".format(userid,login,username))
            time.sleep(3)
            continue
        elif (task_edit == "3"):
            print("""Изменение пароля для веб-интерфейса.
    Проявите фантазию и благоразумие - ЭТО ВАШ ДОМ! Вы же не кладёте ключи под коврик?""")
            newpass = passcheck()
            query = "UPDATE users SET password='{0}' WHERE user_id={1};".format(newpass,userid)
            cursor.execute(query)
            db.commit()
    db.close()


#Рекурсию вынесем в отдельный цикл. Мучим юзера паролем
def passcheck():
    passone = getpass.getpass("Введите пароль (его не будет видно): ")
    passtwo = getpass.getpass("Повторите (чтобы не ошибиться): ")
    if (passone != passtwo):
        print("Пароли не совпадают, повторите ввод!")
        passcheck
    if (passone == passtwo):
        newpass = hashlib.md5(passone.encode()).hexdigest()
        return newpass
            


def newuser():
    os.system('clear')
    print("""======== ДОБАВЛЕНИЕ ПОЛЬЗОВАТЕЛЯ ========
Чтобы добавить нового пользователя, нам понадобится ввести:
ЛОГИН - для входа в веб-интерфейс
ПАРОЛЬ - для логина
ИМЯ - для приветствия в веб-интерфейсе.
Итак, приступим!""")
    newlogin = input("login: ")
    newpass = passcheck()
    newname = input("Имя: ")
    print ("Данные получены, заносим в базу...")
    string = "INSERT INTO users SET login='{0}', password='{1}', username='{2}', last_login='01.01.1970 00:00:00';".format(newlogin,newpass,newname)
    return string
    
    
    
    
    
    
    
    
    
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

#Добавим пафоса...
os.system("clear")
print (
"""========
Приступаем к конфигурированию Татьяны!
========""")    
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
    print("Успешно!")
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



#цепляемся к базе
db = MYSQL.connect(host="localhost", database="tatiana", user=dbuser, password=dbpass, use_unicode=True, charset="utf8")
cursor = db.cursor()


############################################
### запускаем главную функцию
### выводим меню
############################################



while (stop==False):
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


