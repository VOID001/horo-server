import time
import sqlite3
from random import randint
import sys

dbfile = sys.argv[1]
coon = sqlite3.connect(dbfile)
c = coon.cursor()

def rd_hunger():
    for ID in c.execute("SELECT id from horo_info "):
        c.execute("SELECT hunger from horo_info WHERE id = ?", ID)
        hunger = c.fetchone()
        hunger -= randint(5, 10)
        c.execute("UPDATE horo_info SET hunger = ? WHERE id = ?", (hunger, ID))
        coon.commit()
    coon.close()

def start_timer(sleeptime = 28800):

    while 1:
        rd_hunger()
        time.sleep(sleeptime)

start_timer()
