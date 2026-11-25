# Save page HTML for every URL. Usage: python3 crawl.py urls.txt outdir
import sys, os, urllib.request, hashlib
urls=[u for u in open(sys.argv[1]).read().split() if u]; out=sys.argv[2]; os.makedirs(out, exist_ok=True)
for u in urls:
    h=urllib.request.urlopen(u, timeout=60).read().decode()
    open(os.path.join(out, hashlib.md5(u.encode()).hexdigest()+'.html'),'w').write(u+'\n'+h)
print(len(urls))
