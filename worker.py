import os
import time
import signal
import sys
import subprocess
import uuid
import oursql
import shutil
import requests
from dbsettings import dbconfig

wid = 'id0'
if len(sys.argv) >= 2:
    wid = sys.argv[1]
print "working with", wid

connect = oursql.connect(host=dbconfig['host'], user=dbconfig['user'], passwd=dbconfig['password'], db=dbconfig['database'])
cursor = connect.cursor(oursql.DictCursor)

handler = ['/bin/sh', '/home/dd/ddd-' + wid + '/go.sh', wid]
pid = -1

def cur_time():
        return time.strftime("[%Y.%m.%d %H:%M:%S] ")

def is_running(pid):
        return os.path.exists("/proc/" + str(pid))

def is_really_running():
        cmd = 'ps x | grep /home/dd/ddd-' + wid + '/deep | grep -v "grep"'
        process = subprocess.Popen(cmd, shell=True,
                           stdout=subprocess.PIPE,
                           stderr=subprocess.PIPE)
        out, err = process.communicate()
        return out.strip(' \t\n\r')

def recycle_pid():
    while True:
        try:
            pid, status, _ = os.wait3(os.WNOHANG)
            if pid == 0:
                break
            print(cur_time() + "----- child %d terminated with status: %d" %(pid, status))
        except OSError,e:
            break

src_dir = '/var/www/dream/web/images/'
process_dir = '/home/dd/ddd-' + wid + '/out/'
prepare_img = '/home/dd/ddd-' + wid + '/src.jpg'
dest_dir = '/var/www/dream/web/ready/'
mask = 'inception_4c-output-$1-$2.png'
pic_id_file = '/home/dd/ddd-' + wid + '/pic.txt'
algo_file = '/home/dd/ddd-' + wid + '/algo.txt'

ps = is_really_running()
if len(ps) > 0:
    print cur_time() + "Process is already running, pid = " + ps.split()[0] + "..."
    pid = int(ps.split()[0])
else:
    f = open(pic_id_file, "w")
    f.write("0")
    f.close()

while True:
    print cur_time() + ": pid = " + str(pid) + ", run = " + str(None if pid <= 0 else is_running(pid))

    if is_running(pid):
        cur_picture_id = open(pic_id_file, "r").read(100).replace("\n", "")

        cnt = 0
        for x1 in range(0, 4):
            for x2 in range(0, 10):
                if os.path.exists(process_dir + mask.replace("$1", str(x1)).replace("$2", str(x2))):
                    cnt += 1
                else:
                    break
        print cur_time() + ": state = " + str(cnt)
        cursor.close()
        cursor = connect.cursor(oursql.DictCursor)
        cursor.execute("UPDATE Picture SET status = ?, updated = NOW() WHERE id = ? LIMIT 1", (str(cnt), cur_picture_id))
        # print cur_time() + ": vrode upd"
    else:
        cur_picture_id = open(pic_id_file, "r").read(100).replace("\n", "")
        cursor.close()
        cursor = connect.cursor(oursql.DictCursor)
        cursor.execute("SELECT * FROM Picture WHERE id = ? LIMIT 1", (int(cur_picture_id), ))
        row = cursor.fetchone()
        if row is None:
            print cur_time() + ": #" + cur_picture_id + " does not exists."
        else:
            # print cur_time() + ": " + str(row)
            if row['state'] == 'pending':
                print cur_time() + ": pending, pushing."
                print cur_time() + ": pic #" + cur_picture_id + " finished. pushing."
                out_name = str(uuid.uuid4()) + '.jpg'
                os.rename(process_dir + mask.replace("$1", "3").replace("$2", "9"), dest_dir + out_name)
                cursor.close()
                cursor = connect.cursor(oursql.DictCursor)
                cursor.execute("UPDATE Picture SET status = '0', state = 'finishing', output = ?, updated = NOW() WHERE id = ? LIMIT 1", (out_name, cur_picture_id))

                requests.get("http://localhost/pong?id=" + str(cur_picture_id))
            time.sleep(1)

        for x1 in range(0, 4):
            for x2 in range(0, 10):
                pth = process_dir + mask.replace("$1", str(x1)).replace("$2", str(x2))
                if os.path.exists(pth):
                    os.remove(pth)

        print cur_time() + ": starting new process..."
        cursor.close()
        cursor = connect.cursor(oursql.DictCursor)
        cursor.execute("SELECT * FROM Picture WHERE state = 'new' ORDER BY id ASC LIMIT 1")
        row = cursor.fetchone()
        if row is None:
            print cur_time() + ": no pics, waiting for it..."
        else:
            print cur_time() + ": new job info:", row
            if os.path.exists(prepare_img):
                os.remove(prepare_img)
            shutil.copyfile(src_dir + row['source'], prepare_img)
            cursor.close()
            cursor = connect.cursor(oursql.DictCursor)
            cursor.execute("UPDATE Picture SET status = '0', state = 'pending', updated = NOW() WHERE id = ? LIMIT 1", (row['id'], ))
            f = open(pic_id_file, "w")
            f.write(str(row['id']))
            f.close()
            f = open(algo_file, 'w')
            f.write(row['algorithm'])
            f.close()
            mask = row['algorithm'].replace("/", "-") + '-$1-$2.png'

            proc = subprocess.Popen(handler)
            pid = proc.pid
            print cur_time() + "started with PID " + str(pid)
            time.sleep(1)

    recycle_pid()
    time.sleep(3)

