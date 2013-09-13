import OleFileIO_PL as stickyread
import re
from os.path import expanduser


home = expanduser("~")
home = home + "\\AppData\\Roaming\\Microsoft\\Sticky Notes\\StickyNotes.snt"
assert stickyread.isOleFile(home)
ole = stickyread.OleFileIO(home)
print'Root mtime=%s ctime=%s' % (ole.root.getmtime(), ole.root.getctime())
i=0
for obj in ole.listdir(streams=True, storages=False):
    if(len(obj) == 2 and obj[1] == '0'):
        strm = ole.openstream(obj)
        print "obj %d" % i
        note =  strm.read()
        note = note.replace("\n", "")
        note = note.replace("\\par", "\n")
        note = note[note.find("\\fs22 ")+6:]
        note = note[:note.find("}")]
        note = re.sub(r"\\.*\s", "", note)
        print note #re.search(r'\\fs22[\s.]*', note).group(0)
        i+=1
# Close the OLE file:
ole.close()
