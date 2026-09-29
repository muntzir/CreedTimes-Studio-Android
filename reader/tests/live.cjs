const {chromium}=require('playwright');const fs=require('fs'),path=require('path');
(async()=>{
 const browser=await chromium.launch({headless:true,args:['--no-sandbox']});
 const page=await browser.newPage({viewport:{width:390,height:844}});
 const root=path.resolve(__dirname,'../app/src/main/assets'),output=path.resolve(__dirname,'results');fs.mkdirSync(output,{recursive:true});
 await page.route('https://app.creedtimes.local/**',route=>{let p=new URL(route.request().url()).pathname.slice(1);if(!fs.existsSync(path.join(root,p)))return route.abort();return route.fulfill({path:path.join(root,p),contentType:p.endsWith('.js')?'application/javascript':p.endsWith('.css')?'text/css':p.endsWith('.png')?'image/png':'text/html'});});
 await page.goto('https://app.creedtimes.local/index.html');
 await page.waitForFunction(()=>!state.loading && state.cats.length>0 && state.templates.length>0 && state.posts.length>0,{},{timeout:60000});
 await page.screenshot({path:path.join(output,'live-latest.png'),fullPage:false});
 const summary=[];
 for(const tab of ['articles','video','audio','artworks']){
  await page.click(`[data-tab="${tab}"]`);await page.waitForFunction(t=>state.tab===t&&!state.loading,tab,{timeout:60000});
  const info=await page.evaluate(()=>({tab:state.tab,error:state.error,posts:state.posts.length,query:query(),first:state.posts[0]?{id:state.posts[0].id,title:title(state.posts[0]),media:mediaLinks(state.posts[0])}:null}));
  if(info.error)throw Error(JSON.stringify(info));summary.push(info);
  await page.screenshot({path:path.join(output,'live-'+tab+'.png'),fullPage:false});
  if(['video','audio'].includes(tab)&&info.first){await page.locator('[data-post="'+info.first.id+'"]').first().click();await page.locator('[data-media]').first().waitFor();await page.click('[data-action="back"]');}
 }
 fs.writeFileSync(path.join(output,'live-tabs.json'),JSON.stringify(summary,null,2));console.log('LIVE TABS',JSON.stringify(summary));
 await browser.close();
})().catch(e=>{console.error(e);process.exit(1)});
