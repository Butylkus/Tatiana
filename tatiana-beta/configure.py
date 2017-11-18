#!/usr/bin/python3
#coding=utf8
#Татьяна - умная домохозяйка
#Конфигурационный скрипт для Татьяны
#Распространяется свободно на условиях лицензии GNU GPLv2.
#Автор: Алексей Butylkus, https://vk.com/butpub, https://www.youtube.com/user/butylkus


# ========= Импортируем модули ========= #

import time
import os, sys, getpass, hashlib, random, re
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
    3. Одноканальные кнопки (1 кнопка = 1 выход)
    4. Двухканальные кнопки (1 кнопка = 2 выхода)
    5. Датчики климата  
    6. Охранная система
    0. Выход
Введите номер и нажмите [ВВОД]""")
    task = str(input("# > "))
    if (task == "1"):
        userlist() #вызываем список пользователей и операции
    elif (task == "2"):
        mainpins() #настраиваем пины
    elif (task == "3"):
        buttons()
    elif (task == "4"):
        buttons_block()
    elif (task == "5"):
        dht()
    elif (task == "6"):
        print ("Алярмы")
    else:
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
    
    
def mainpins():
    os.system('clear')
    print("""======== ЦОКОЛЁВКА, РАСПИНОВКА, ИМЕНА УСТРОЙСТВ ========
Это Самая Главная Таблица!
Она отвечает за имена устройств, управляет типами подключений и вообще является важнейшей частью Татьяны.
Вы должны понимать, как, что, куда и зачем вы подключили и корректно передать эти сведения Татьяне.
Не волнуйтесь, здесь нет ничего страшного, просто не забывайте перезапускать Татьяну после настройки.""")
    db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    query = "SELECT * FROM pins;"
    cursor.execute(query)
    pins = cursor.fetchall()
    db.close()
    len1=2 #ширина поля pin
    len2=20 #ширина поля name
    len3=8 #ширина поля direction
    #Определяем максимальные значения для красивой таблицы
    for pin in pins:
        if (len2 < len(pin[1])):
            len2 = len(pin[1])
        if (len3 < len(pin_director(pin[2]))):
            len3 = len(pin_director(pin[2]))
    #Рисуем таблицу
    print("====\nПины, их названия и назначения")
    border="+-"+"-"*len1+"-+-"+"-"*len2+"-+-"+"-"*len3+"-+"
    print(border)
    for pin in pins:
        out = "| "+ str(pin[0])+" "*(len1-len(str(pin[0])))+" | "+pin[1]+" "*(len2-len(pin[1]))+" | "+pin_director(pin[2])+" "*(len3-len(pin_director(pin[2])))+" |"
        print(out)
    print(border)
    print ("""Введите:
    число от 1 до 27 и вы настроите выбранный пин;
    127 - настройка ВСЕХ пинов по порядку (при первоначальной установке очень полезно);
    0 - для возврата в меню.""")
    current = "spam" #Текущий выбор
    current = int(input("#: > "))
    if (current in range(1,28)):
        pin_namer(current) #Вызываем один раз для выбранного пина
        mainpins() #И самовызов для красоты
    if (current > 27):
        for pin in range(1,28):
            pin_namer(pin) #Фигачим циклом по всем подряд
        mainpins() #И опять самовызов
    if (current == 0):
        return

#Возвращает строку с номером пина и его именем
def pin_namer(pin):
    db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    query = "SELECT name, direction FROM pins WHERE pin={0};".format(pin)
    cursor.execute(query)
    selectedpin = cursor.fetchone()
    print("Пин " + str(pin) + " '" + selectedpin[0] + "': " + pin_director(selectedpin[1]))
    query = pin_renamer(pin)
    cursor.execute(query)
    db.commit()
    db.close()

#Переназывает пин
def pin_renamer(pin):
    print("1 - кнопка, 2 - реле, 3 - кнопка на два реле, 4 - DHT-датчик, 5 - PIR-датчик, 6 - не используется")
    newdirection = pin_redirector(input("это? > ")) #Спрашиваем новое направление
    newname = input("имя? > ") #спрашиваем новое имя
    string = "UPDATE pins SET direction='{0}', name='{1}' WHERE pin={2};".format(newdirection,newname,pin)
    return string


#Преобразуем направление из татьяниного формата в человеческий
def pin_director(direction):
    if (direction == "none"):
        return("свободен")
    if (direction == "dht"):
        return("датчик DHT11/22")
    if (direction == "pir"):
        return("датчик pir")
    if (direction == "input"):
        return("кнопка на один выход")
    if (direction == "block"):
        return("кнопка на два выхода")
    if (direction == "output"):
        return("выходное реле")

#Преобразуем направление из человеческого формата в татьянин
def pin_redirector(direction):
    if (direction == "6"):
        return("none")
    if (direction == "4"):
        return("dht")
    if (direction == "5"):
        return("pir")
    if (direction == "1"):
        return("input")
    if (direction == "3"):
        return("block")
    if (direction == "2"):
        return("output")
    else:
        return("none")


#Возвращает имя пина
def pinname(pin):
    if (pin == ""):
        return ("НЕТ ВХОДА")
    db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    query = "SELECT name FROM pins WHERE pin='{0}';".format(pin)
    cursor.execute(query)
    name = cursor.fetchone()
    realname = name[0]
    db.close()
    if (realname == ""):
        return "<NONAME>"
    else:
        return realname

#Проверка связанности кнопок
def button_linked(pin):
    db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    query = "SELECT * FROM button_device WHERE inpin='{0}';".format(pin)
    cursor.execute(query)
    linked = cursor.fetchone()
    #Если есть выходные пины, покажем их
    if (linked):
        return (linked[1], pinname(linked[1]))
    else:
        #Приверим на блочность
        query = "SELECT * FROM button_block WHERE inpin='{0}';".format(pin)
        cursor.execute(query)
        linked_in_block = cursor.fetchall()
        #Если есть в блочных, просто скажем об этом
        if (linked_in_block):
            return (0, "** двухканальная")
        #А если кнопка ещё нигде не привязана, то это главный кандадат на настройку
        return (0, "* нет привязки")
    db.close()

#управляем одиночными кнопками
def buttons():
    db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    task = "spam" #объявим переменную для условного выхода
    while (task != "0"):
        os.system("clear")
        print("""======== ОДИНОЧНЫЕ КНОПКИ ========
Это таблица с привязками "кнопка - реле", когда одна кнопка управляет ОДНИМ выходным реле.
Иными словами, одна кнопка = одна лампочка. Двухканальные кнопки настраиваются в разделе "Двухканальные кнопки".
Структура таблицы:
+ Пин кнопки + Имя кнопки + Пин реле + Имя реле +""")
   
        query = "SELECT pin FROM pins WHERE direction='input';"
        cursor.execute(query)
        inpins = cursor.fetchall()
        #print (inpins[0])
        len1=2 #ширина поля pin
        len2=10 #ширина поля имени кнопки
        len3=10 #ширина поля direction
        
        #Определяем максимальные значения для красивой таблицы
        for pin in inpins:
            if (len2 < len(pinname(pin[0]))):
                len2 = len(pinname(pin[0]))
            outpin = button_linked(pin[0])
            if (len3 < len(outpin[1])):
                len3 = len(outpin[1])

        #Рисуем таблицу с данными
        border="+-"+"-"*len1+"-+-"+"-"*len2+"-+-"+"-"*len1+"-+-"+"-"*len3+"-+"
        print(border)
        #Заодно свернём многомерный список в аккуратную линеечку
        pinarray = []
        for pin in inpins:
            pinarray.append(pin[0]) #вынимаем и складываем
            outpin = button_linked(pin[0])
            out = "| "+ str(pin[0])+" "*(len1-len(str(pin[0])))+" | "+pinname(pin[0])+" "*(len2-len(pinname(pin[0])))+" | "+str(outpin[0])+" "*(len1-len(str(outpin[0])))+" | "+outpin[1]+" "*(len3-len(outpin[1]))+" |"
            print(out)
        print(border)
        
        
        query = ""
        print("""* - свободно для привязки. ** - перейдите к управлению двухканальными реле.
    Для привязки/перепривязки кнопки введите её пин (только цифры)
    0 - для выхода.""")
        newin = input("# >")
        #Проверим ввод
        try:
            #Если введён 0 - выходим из раздела
            if (newin == "0"):
                task = "0"
                db.close()
                continue
            #Если ввдён пин не направления input
            if (int(newin) not in pinarray):
                print ("Нет такой кнопки! Попробуйте ещё раз, будьте внимательны!")
                time.sleep(3)
                continue
        except:
            print ("Что-то не то... Ну-ка ещё разик...")
            time.sleep(3)
            continue
        #print (newin)
        
        print("К чему привязываем? Введите выходной пин (реле) или DEL - для освобождения кнопки")
        newout = input("# >")
        print (newout)
        try:
            if ((newout == "del") or (newout == "DEL")):
                print("Удаляётся привязка кнопки на пине {0} ({1})...".format(newin, pinname(newin)))
                query = "DELETE FROM button_device WHERE inpin={0};".format(int(newin))
                cursor.execute(query)
                db.commit()
                time.sleep(3)
                continue
            if (int(newout) not in range(1,28)):
                os.system('clear')
                print ("Нет такого пина! Попробуйте ещё раз, будьте внимательны!")
                time.sleep(3)
                continue
        except:

            os.system('clear')
            print ("Некорректный ввод, придётся повторить...")
            time.sleep(3)
            continue
        #print (newout)

        
        #Формируем строку запроса в БД для создание и обновления привязки
        query = "INSERT INTO button_device (inpin, outpin) VALUES({0},'{1}') ON DUPLICATE KEY UPDATE outpin='{1}';".format(int(newin), newout)
        #И записываем её
        cursor.execute(query)
        db.commit()
        print("Готово, мы привязали пин {0} ({1}) к пину {2} ({3})".format(newin, pinname(newin), newout, pinname(newout)))
        time.sleep(3)    
    
    


#
def dht_type(cursor, pin):
    if (pin == None):
        return ("* ???")
    query = "SELECT model FROM dht_sensors WHERE pin='{0}';".format(pin)
    cursor.execute(query)
    model = cursor.fetchone()
    if (model == None):
        return ("* ???")
    if (str(model[0]) == "11"):
        return ("DHT11")
    elif (str(model[0]) == "22"):
        return ("DHT22")
    else:
        return ("* ???")


#управляем датчиками DHT
def dht():
    db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    task = "spam" #объявим переменную для условного выхода
    while (task != "0"):
        os.system("clear")
        print ("""======== ДАТЧИКИ DHT ========
Это таблица с датчиками климата DHT11/22. Собственно, кроме модели Татьяне ничего не нужно, она всё сделает сама.
Интервал опроса датчиков (по умолчанию 30 минут) настраивается в ФАЙЛЕ КОНФИГУРАЦИИ config.py!
Структура таблицы:
+ Пин датчика + Имя датчика + Модель датчика +""")
   
        query = "SELECT pin, name FROM pins WHERE direction='dht';"
        cursor.execute(query)
        sensors = cursor.fetchall()
        len1=2 #ширина поля pin
        len2=8 #ширина поля name
        len3=5 #ширина поля direction

        #Определяем максимальные значения для красивой таблицы
        for sensor in sensors:
            #print (str(sensor[0]) + " ::: " + sensor[1])
            if (len2 < len(str(sensor[1]))):
                len2 = len(str(sensor[1]))
            if (len3 < len(dht_type(cursor,sensor[0]))):
                len3 = len(dht_type(cursor,sensor[0]))
            #print (len1, len2, len3, sep="::")
            #print (dht_type(cursor,sensor[0]))

 
        #Рисуем таблицу
        border="+-"+"-"*len1+"-+-"+"-"*len2+"-+-"+"-"*len3+"-+"
        print (border)
        for sensor in sensors:
            out = "| "+ str(sensor[0])+" "*(len1-len(str(sensor[0])))+" | "+sensor[1]+" "*(len2-len(str(sensor[1])))+" | "+dht_type(cursor, sensor[0])+" "*(len3-len(dht_type(cursor, sensor[0])))+" |"
            print(out)
        print (border)
        print ("""* ??? - тип датчика не задан и нуждается в настройке.
Чтобы изменить тип датчика, введите пин и модель, 11 для DHT11 и 22 для DHT22
0 - для выхода в главное меню:""")

        #Переформируем многомерку в список пинов для проверки
        allowedpins = []
        for sensor in sensors:
            allowedpins.append(sensor[0])
        #print (allowedpins)
        
        #Запрашиваем ввод пина
        task = input("ПИН > ")

        #Защита от идиотов и возврат
        if ((task == "0") or (task == "")):
            print ("Возвращаемся в меню...")
            db.close()
            time.sleep(1)
            break
        elif (re.search('[a-zA-Zа-яА-Я]', task)):
            print ("Пожалуйста, вводите ТОЛЬКО ЦИФРЫ!")
            time.sleep(3)
        elif (int(task) not in allowedpins):
            print ("Не пойдёт такое! Повнимательнее, нужен пин из таблицы!")
            time.sleep(3)
            
        elif (int(task) in allowedpins):
            print ("К пину {0} ({1}) подключен датчик DHT:".format(task,pinname(task)))
            
            #Всё в порядке, запрашиваем модель датчика
            newtype = input("МОДЕЛЬ (11 или 22), DEL - удалить, 0 - отмена: > ")
            if (newtype not in ("11", "22", "0", "del", "DEL")):
                print ("Перестаньте баловаться! Татьяна натура тонкая, а ну как сознание потеряет? Навсегда...")
            elif (newtype == "0"):
                print ("Хорошо, не трогаем пин {0} ({1})".format(task,pinname(task)))
                continue
            elif (newtype in ("del", "DEL")):
                print ("Отключить пин {0} ({1}) от сбора статистики и перевести в неактивный режим?".format(task, pinname(task)))
                print ("""Сбор статистики остановится, а пину будет присвоено направление NONE.
Для включения необходимо будет снова настроить его в меню 2 (Пины) и здесь.
Собранная ранее статистика не удаляется и будет храниться до особого распоряжения.""")
                code = str(random.randint(100,999)) #для подтверждения простого согласия мало. Попросим ввести код
                string = "Вы уверены?! Введите {0}, если уверены: >".format(code)
                confirm = input(string)
                if (confirm == code):
                    query1 = "DELETE FROM dht_sensors WHERE pin={0};".format(task)
                    query2 = "UPDATE pins SET direction='none' WHERE pin={0};".format(task)
                    cursor.execute(query1)
                    print ("Таблица сенсоров обновлена...")
                    cursor.execute(query2)
                    print ("Таблица пинов обновлена!")
                    db.commit()
                    time.sleep(5)
                continue
            else:
                if (newtype in ("11", "22")):
                    query = "INSERT INTO dht_sensors (pin, model) VALUES({0},'{1}') ON DUPLICATE KEY UPDATE pin={0}, model='{1}';".format(int(task), newtype)
                    cursor.execute(query)
                    print("Готово, мы настроили датчик {0} (DHT{1}) на сбор данных".format(pinname(task), newtype))
                else:
                    print ("Так... Либо вы начинаете подходить к делу серьёзно, либо Татьяна обидится и уйдёт!")
                    db.close()
                    time.sleep(5)
                    break
        
        db.commit() #чтобы лишний раз не прописывать
        time.sleep(3)


#Возвращает кортеж с кнопками в блочных_устройствах
def button_linked_block(pin):
    db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    query = "SELECT * FROM button_block WHERE inpin='{0}';".format(pin)
    cursor.execute(query)
    linked = cursor.fetchall()
    #Если есть выходные пины, покажем их
    if (linked):
        result = []
        for res in linked:
            result.append(res[1])
            result.append(pinname(res[1]))
        return (result)
    else:
        #Приверим на одиночность
        query = "SELECT * FROM button_device WHERE inpin='{0}';".format(pin)
        cursor.execute(query)
        linked_in_solo = cursor.fetchall()
        #Если есть в одиночных, просто скажем об этом
        if (linked_in_solo):
            return (0, "** обычная")
        #А если кнопка ещё нигде не привязана, то это главный кандадат на настройку
        return (0, "* нет привязки")
    db.close()

#Управляем блочными кнопками
def buttons_block():
    db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
    cursor = db.cursor()
    task = "spam" #объявим переменную для условного выхода
    while (task != "0"):
        os.system("clear")
        print("""======== ДВУХКАНАЛЬНЫЕ КНОПКИ ========
Это таблица с привязками "одна кнопка - ДВА реле", когда одна кнопка управляет ДВУМЯ выходными реле.
Идеально для ЛЮСТР, которые обычно имеют два канала (1+2, 2+2, 2+3 и тд лампочки) и два выключателя.
Переключение будет происходить циклически по схеме "00 > 01 > 10 > 11" по нажатию ОДНОЙ кнопки.
На веб-панели выходные устройства будут переключаться как РАЗДЕЛЬНЫЕ, то есть работать как обычные кнопки.
В таблице вы видите ВСЕ кнопки, это чтобы не накосячить =)
Структура таблицы:
+ Пин кнопки + Имя кнопки + Пин реле + Имя реле +""")
   
        query = "SELECT pin FROM pins WHERE direction='block';"
        cursor.execute(query)
        inpins = cursor.fetchall()
        #print (inpins[0])
        len1=2 #ширина поля pin
        len2=10 #ширина поля имени кнопки
        len3=10 #ширина поля первого выхода
        len4=10 #ширина поля первого выхода 
        
        #Определяем максимальные значения для красивой таблицы
        for pin in inpins:
            if (len2 < len(pinname(pin[0]))):
                len2 = len(pinname(pin[0]))
            outpin = []
            outpin = button_linked_block(pin[0])
            #print (outpin)
            if (len3 < len(outpin[1])):
                len3 = len(outpin[1])
            #Перехватываем косяк настройки, когда в таблице button_block нет второго выхода
            try:
                if (len3 < len(outpin[3])):
                    len3 = len(outpin[3])
                no_other_pin = True
            except IndexError:
                no_other_pin = False

        #Рисуем таблицу с данными
        border="+-"+"-"*len1+"-+-"+"-"*len2+"-+-"+"-"*len1+"-+-"+"-"*len3+"-+"
        #Заодно свернём многомерный список в аккуратную линеечку
        pinarray = []
        out = border + "\n" #Объявим явно пустым, чтобы проще было лепить строку циклом
        for pin in inpins:
            pinarray.append(pin[0]) #вынимаем и складываем
            outpin = button_linked_block(pin[0])
            out = out + "| "+ str(pin[0])+" "*(len1-len(str(pin[0])))+" | "+pinname(pin[0])+" "*(len2-len(pinname(pin[0])))+" | "+str(outpin[0])+" "*(len1-len(str(outpin[0])))+" | "+outpin[1]+" "*(len3-len(outpin[1]))+" |\n"
            try: #Аналогично блоку в предыдущем цикле
                out = out + "| "+ str(pin[0])+" "*(len1-len(str(pin[0])))+" | "+pinname(pin[0])+" "*(len2-len(pinname(pin[0])))+" | "+str(outpin[2])+" "*(len1-len(str(outpin[2])))+" | "+outpin[3]+" "*(len3-len(outpin[3]))+" |\n"
            except:
                continue
        out = out + border
        print(out)

        
        
        query = ""
        print("""* - свободно для привязки. ** - перейдите к управлению одноканальными реле.
    Для привязки/перепривязки кнопки введите её пин (только цифры)
    0 - для выхода.""")
        newin = input("# >")
        #Проверим ввод
        try:
            #Если введён 0 - выходим из раздела
            if (newin == "0"):
                task = "0"
                db.close()
                continue
            #Если ввдён пин не направления input
            if (int(newin) not in pinarray):
                print ("Нет такой кнопки! Сначала настройте данный пин как БЛОЧНУЮ кнопку и затем попробуйте ещё раз, будьте внимательны!")
                time.sleep(3)
                continue
        except:
            print ("Что-то не то... Ну-ка ещё разик...")
            time.sleep(3)
            continue
        #print (newin)
        
        print("К чему привязываем? Введите выходные пины по очереди или DEL - для освобождения кнопки")
        newout1 = input("Первая >")
        if ((newout1 == "del") or (newout1 == "DEL")):
            print("Удаление привязок на кнопке {0} ({1})!".format(newin, pinname(newin)))
            newout2 = input("Вы уверены? Y/N >")
            if (((newout1 == "del") or (newout1 == "DEL")) and ((newout2 == "y") or (newout2 == "Y") or (newout2 == "Yes") or (newout2 == "yes") or (newout2 == "YES"))):
                print("Удаляётся привязка кнопки на пине {0} ({1})...".format(newin, pinname(newin)))
                query = "DELETE FROM button_block WHERE inpin={0};".format(int(newin))
                cursor.execute(query)
                db.commit()
                time.sleep(3)
                continue
            else: 
                print("Ну ладно, давайте начнём с самого начала...")
                time.sleep(3)
                continue       
        newout2 = input("Вторая >")
        print (newout2)
        try:
            if ((int(newout1) not in range(1,28)) or (int(newout2) not in range(1,28))):
                os.system('clear')
                print ("Нет такого пина! Попробуйте ещё раз, будьте внимательны!")
                time.sleep(3)
                continue
        except:
            os.system('clear')
            print ("Некорректный ввод, придётся повторить...")
            time.sleep(3)
            continue
        #print (newout)

        
        #Формируем строку запроса в БД для создания и обновления привязки
        query = "DELETE FROM button_block WHERE inpin={0};".format(int(newin))
        cursor.execute(query)
        print("Сбросили старые привязки...")
        db.commit()
        query = "INSERT INTO button_block (inpin, outpin) VALUES({0},'{1}') ON DUPLICATE KEY UPDATE outpin='{1}';".format(int(newin), newout1)
        cursor.execute(query)
        print("Привязали пин {0} ({1}) к пину {2} ({3})...".format(newin, pinname(newin), newout1, pinname(newout1)))
        db.commit()
        query = "INSERT INTO button_block (inpin, outpin) VALUES({0},'{1}') ON DUPLICATE KEY UPDATE outpin='{1}';".format(int(newin), newout2)
        cursor.execute(query)
        print("... и к пину {0} ({1})".format(newout2, pinname(newout2)))
        db.commit()
        time.sleep(3)
        


############################################
### ОСНОВНАЯ ЛОГИКА
############################################


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
        - находится в этом же каталоге;
        - содержит необходимые данные.
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



############################################
### Проверка наличия базы
############################################

print("""
======== КОНФИГУРИРУЕМ ТАТЬЯНУ ========
Введите данные для доступа к БД:
""")
dbuser=str(input("Логин (обычно root): "))
dbpass=getpass.getpass("Пароль: ")


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

db.close()
sys.exit("Ну что же, видимо, конфигурация завершена... Возвращайтесь в любое время =)")






# примеры работы с БД, памятка
#db = MYSQL.connect(host="localhost", database="tatiana", user=dbuser, password=dbpass, use_unicode=True, charset="utf8")
# подключение как в конфиге:
#db = MYSQL.connect(host="localhost", database=config.dbbase, user=config.dbuser, password=config.dbpassword, use_unicode=True, charset="utf8")
#query = "INSERT INTO button_device (inpin, outpin) VALUES({0},'{1}') ON DUPLICATE KEY UPDATE outpin='{1}';".format(int(newin), newout)
#cursor = db.cursor()
#query = "SELECT pin FROM `pins` WHERE `direction`='output'"
#query = "UPDATE `pins` SET `status`=1 WHERE `pin`='" + str(pin) + "'"
#cursor.execute(query)
#connection.commit()


