// Runs without Android SDK. Tests real feed-query and bridge-response logic.
const vm=require('node:vm'),fs=require('node:fs'),assert=require('node:assert/strict');
const source=fs.readFileSync(__dirname+'/../app/src/main/assets/app.js','utf8');
const storage=new Map();
const context=vm.createContext({console,URL,URLSearchParams,setTimeout,clearTimeout,setInterval:()=>0,Date,Map,Set,Promise,JSON,Error,Number,String,Object,Array,Math,RegExp,
 window:{addEventListener(){}},document:{body:{classList:{toggle(){}}},documentElement:{style:{setProperty(){}}},addEventListener(){}},
 matchMedia:()=>({matches:false,addEventListener(){}}),localStorage:{getItem:k=>storage.get(k)||null,setItem:(k,v)=>storage.set(k,v)},
 DOMParser:class{},fetch(){throw Error('Network forbidden in logic tests');}});
vm.runInContext(source.replace('render();load();taxonomy();',''),context);
const run=s=>vm.runInContext(s,context);
assert.equal(run("safeURL('')"),'');assert.equal(run("safeURL('javascript:alert(1)')"),'');assert.equal(run("safeURL('http://creedtimes.com')"),'');assert.equal(run("safeURL('/story')"),'https://creedtimes.com/story');
run("state.cats=[{id:2,name:'Video',slug:'video'},{id:3,name:'Podcast',slug:'podcast'}];state.tags=[{id:10,name:'Videos',slug:'videos'}];state.tab='video';");
let query=new URLSearchParams(run('query()').split('?')[1]);assert.equal(query.get('categories'),'2');assert.equal(query.get('tags'),'10');assert.equal(query.get('tax_relation'),'OR');
run("state.tab='audio'");query=new URLSearchParams(run('query()').split('?')[1]);assert.equal(query.get('categories'),'3');assert.equal(query.has('tags'),false);
run("state.tab='all';state.filter={type:'author',id:8};state.search='Gaza & Iran';state.page=4");query=new URLSearchParams(run('query()').split('?')[1]);assert.equal(query.get('author'),'8');assert.equal(query.get('search'),'Gaza & Iran');assert.equal(query.get('page'),'4');
run("state.tab='video';state.cats=[];state.tags=[]");assert.equal(run('query()'),null);
run("write('prefs',{theme:'dark'})");assert.equal(run("read('prefs',{}).theme"),'dark');
assert.equal(run("esc('<script>')"),'&lt;script&gt;');
run("pending['7']={resolve:r=>window.result=r,reject:e=>window.error=e.message,timer:0};window.nativeReply('7','[{\"id\":1}]',null)");assert.equal(run('window.result[0].id'),1);
run("pending['8']={resolve:r=>{},reject:e=>window.error=e.message,timer:0};window.nativeReply('8','not json',null)");assert.match(run('window.error'),/valid article data/);
console.log('PASS: media category/tag OR, podcast separation, author/search/pagination, absent taxonomy, URL policy, escaping, preference persistence, native JSON response/error handling.');
run("state.filter=null;state.search='';state.page=1;state.cats=[{id:2,name:'Video',slug:'video'},{id:3,name:'Podcast',slug:'podcast'},{id:4,name:'Art',slug:'art'}];state.tags=[{id:11,name:'Artwork',slug:'artwork'}];state.tab='artworks'");
query=new URLSearchParams(run('query()').split('?')[1]);assert.equal(query.get('categories'),'4');assert.equal(query.get('tags'),'11');assert.equal(query.get('tax_relation'),'OR');
run("state.tab='articles'");query=new URLSearchParams(run('query()').split('?')[1]);assert.equal(query.get('categories_exclude'),'2,3,4');assert.equal(query.get('tags_exclude'),'11');
run("state.cats.push({id:8,name:'Articles',slug:'articles'})");query=new URLSearchParams(run('query()').split('?')[1]);assert.equal(query.get('categories'),'8');assert.equal(query.has('categories_exclude'),false);
console.log('PASS: Artworks category/tag union, Articles taxonomy and non-media fallback.');

run("state.cats=[];state.tags=[];state.templates=[{id:70,name:'Podcast',slug:'podcast'},{id:71,name:'Video',slug:'video'}];state.tab='audio'");query=new URLSearchParams(run('query()').split('?')[1]);assert.equal(query.get('post_template'),'70');
run("state.tab='articles'");query=new URLSearchParams(run('query()').split('?')[1]);assert.equal(query.get('post_template_exclude'),'71,70');
console.log('PASS: website post_template taxonomy mapping and article exclusions.');
