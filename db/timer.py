import time
import sqlite3
from random import randint
import sys

dbfile = sys.argv[1]
coon = sqlite3.connect(dbfile)
c = coon.cursor()

def rd_hunger():
    c.execute("SELECT id from horo_info")
    for ID in c.fetchall():
        ID = ID[0]
        c.execute("SELECT hunger from horo_info WHERE id = ?", (ID,))
        hunger = c.fetchone()[0]
        hunger += randint(5, 10)
        c.execute("UPDATE horo_info SET hunger = ? WHERE id = ?", (hunger, ID))
        coon.commit()
        print("锟斤拷")
    #coon.close()

def start_timer(sleeptime = 28800):

    while 1:
        rd_hunger()
        time.sleep(sleeptime)

start_timer()
