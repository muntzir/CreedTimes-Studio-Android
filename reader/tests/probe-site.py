import json, urllib.request
from pathlib import Path
out=Path(__file__).parent/'results';out.mkdir(exist_ok=True)
summary={}
for route in ['posts?_embed=1&per_page=2','categories?per_page=100','tags?per_page=100','users?per_page=100','types']:
 try:
  request=urllib.request.Request('https://creedtimes.com/wp-json/wp/v2/'+route, headers={'User-Agent':'CreedTimesAndroid/1.1','Accept':'application/json'})
  with urllib.request.urlopen(request,timeout=25) as r:data=json.load(r)
  (out/(route.split('?')[0]+'.json')).write_text(json.dumps(data,ensure_ascii=False))
  summary[route]={'ok':True,'count':len(data),'shape':type(data).__name__}
 except Exception as e:summary[route]={'ok':False,'error':str(e)}
(out/'site-check.json').write_text(json.dumps(summary,indent=2))
print(json.dumps(summary,indent=2))
