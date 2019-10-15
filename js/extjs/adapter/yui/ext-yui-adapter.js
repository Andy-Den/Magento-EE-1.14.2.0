/*
 * Ext JS Library 1.1 Beta 1
 * Copyright(c) 2006-2007, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://www.extjs.com/license
 */


Ext={};window["undefined"]=window["undefined"];Ext.apply=function(o,c,defaults){if(defaults){Ext.apply(o,defaults);}
if(o&&c&&typeof c=='object'){for(var p in c){o[p]=c[p];}}
return o;};(function(){var idSeed=0;var ua=navigator.userAgent.toLowerCase();var isStrict=document.compatMode=="CSS1Compat",isOpera=ua.indexOf("opera")>-1,isSafari=(/webkit|khtml/).test(ua),isIE=ua.indexOf("msie")>-1,isIE7=ua.indexOf("msie 7")>-1,isGecko=!isSafari&&ua.indexOf("gecko")>-1,isBorderBox=isIE&&!isStrict,isWindows=(ua.indexOf("windows")!=-1||ua.indexOf("win32")!=-1),isMac=(ua.indexOf("macintosh")!=-1||ua.indexOf("mac os x")!=-1),isSecure=window.location.href.toLowerCase().indexOf("https")===0;if(isIE&&!isIE7){try{document.execCommand("BackgroundImageCache",false,true);}catch(e){}}
Ext.apply(Ext,{isStrict:isStrict,isSecure:isSecure,isReady:false,enableGarbageCollector:true,SSL_SECURE_URL:"javascript:false",BLANK_IMAGE_URL:"http:/"+"/extjs.com/s.gif",emptyFn:function(){},applyIf:function(o,c){if(o&&c){for(var p in c){if(typeof o[p]=="undefined"){o[p]=c[p];}}}
return o;},addBehaviors:function(o){if(!Ext.isReady){Ext.onReady(function(){Ext.addBehaviors(o);});return;}
var cache={};for(var b in o){var parts=b.split('@');if(parts[1]){var s=parts[0];if(!cache[s]){cache[s]=Ext.select(s);}
cache[s].on(parts[1],o[b]);}}
cache=null;},id:function(el,prefix){prefix=prefix||"ext-gen";el=Ext.getDom(el);var id=prefix+(++idSeed);return el?(el.id?el.id:(el.id=id)):id;},extend:function(){var io=function(o){for(var m in o){this[m]=o[m];}};return function(sb,sp,overrides){if(typeof sp=='object'){overrides=sp;sp=sb;sb=function(){sp.apply(this,arguments);};}
var F=function(){},sbp,spp=sp.prototype;F.prototype=spp;sbp=sb.prototype=new F();sbp.constructor=sb;sb.superclass=spp;if(spp.constructor==Object.prototype.constructor){spp.constructor=sp;}
sb.override=function(o){Ext.override(sb,o);};sbp.override=io;sbp.__extcls=sb;Ext.override(sb,overrides);return sb;};}(),override:function(origclass,overrides){if(overrides){var p=origclass.prototype;for(var method in overrides){p[method]=overrides[method];}}},namespace:function(){var a=arguments,o=null,i,j,d,rt;for(i=0;i<a.length;++i){d=a[i].split(".");rt=d[0];eval('if (typeof '+rt+' == "undefined"){'+rt+' = {};} o = '+rt+';');for(j=1;j<d.length;++j){o[d[j]]=o[d[j]]||{};o=o[d[j]];}}},urlEncode:function(o){if(!o){return"";}
var buf=[];for(var key in o){var ov=o[key];var type=typeof ov;if(type=='undefined'){buf.push(encodeURIComponent(key),"=&");}else if(type!="function"&&type!="object"){buf.push(encodeURIComponent(key),"=",encodeURIComponent(ov),"&");}else if(ov instanceof Array){for(var i=0,len=ov.length;i<len;i++){buf.push(encodeURIComponent(key),"=",encodeURIComponent(ov[i]===undefined?'':ov[i]),"&");}}}
buf.pop();return buf.join("");},urlDecode:function(string,overwrite){if(!string||!string.length){return{};}
var obj={};var pairs=string.split('&');var pair,name,value;for(var i=0,len=pairs.length;i<len;i++){pair=pairs[i].split('=');name=decodeURIComponent(pair[0]);value=decodeURIComponent(pair[1]);if(overwrite!==true){if(typeof obj[name]=="undefined"){obj[name]=value;}else if(typeof obj[name]=="string"){obj[name]=[obj[name]];obj[name].push(value);}else{obj[name].push(value);}}else{obj[name]=value;}}
return obj;},each:function(array,fn,scope){if(typeof array.length=="undefined"||typeof array=="string"){array=[array];}
for(var i=0,len=array.length;i<len;i++){if(fn.call(scope||array[i],array[i],i,array)===false){return i;};}},combine:function(){var as=arguments,l=as.length,r=[];for(var i=0;i<l;i++){var a=as[i];if(a instanceof Array){r=r.concat(a);}else if(a.length!==undefined&&!a.substr){r=r.concat(Array.prototype.slice.call(a,0));}else{r.push(a);}}
return r;},escapeRe:function(s){return s.replace(/([.*+?^${}()|[\]\/\\])/g,"\\$1");},callback:function(cb,scope,args,delay){if(typeof cb=="function"){if(delay){cb.defer(delay,scope,args||[]);}else{cb.apply(scope,args||[]);}}},getDom:function(el){if(!el){return null;}
return el.dom?el.dom:(typeof el=='string'?document.getElementById(el):el);},getCmp:function(id){return Ext.ComponentMgr.get(id);},num:function(v,defaultValue){if(typeof v!='number'){return defaultValue;}
return v;},destroy:function(){for(var i=0,a=arguments,len=a.length;i<len;i++){var as=a[i];if(as){if(as.dom){as.removeAllListeners();as.remove();continue;}
if(typeof as.purgeListeners=='function'){as.purgeListeners();}
if(typeof as.destroy=='function'){as.destroy();}}}},isOpera:isOpera,isSafari:isSafari,isIE:isIE,isIE7:isIE7,isGecko:isGecko,isBorderBox:isBorderBox,isWindows:isWindows,isMac:isMac,useShims:((isIE&&!isIE7)||(isGecko&&isMac))});})();Ext.namespace("Ext","Ext.util","Ext.grid","Ext.dd","Ext.tree","Ext.data","Ext.form","Ext.menu","Ext.state","Ext.lib","Ext.layout","Ext.app");Ext.apply(Function.prototype,{createCallback:function(){var args=arguments;var method=this;return function(){return method.apply(window,args);};},createDelegate:function(obj,args,appendArgs){var method=this;return function(){var callArgs=args||arguments;if(appendArgs===true){callArgs=Array.prototype.slice.call(arguments,0);callArgs=callArgs.concat(args);}else if(typeof appendArgs=="number"){callArgs=Array.prototype.slice.call(arguments,0);var applyArgs=[appendArgs,0].concat(args);Array.prototype.splice.apply(callArgs,applyArgs);}
return method.apply(obj||window,callArgs);};},defer:function(millis,obj,args,appendArgs){var fn=this.createDelegate(obj,args,appendArgs);if(millis){return setTimeout(fn,millis);}
fn();return 0;},createSequence:function(fcn,scope){if(typeof fcn!="function"){return this;}
var method=this;return function(){var retval=method.apply(this||window,arguments);fcn.apply(scope||this||window,arguments);return retval;};},createInterceptor:function(fcn,scope){if(typeof fcn!="function"){return this;}
var method=this;return function(){fcn.target=this;fcn.method=method;if(fcn.apply(scope||this||window,arguments)===false){return;}
return method.apply(this||window,arguments);};}});Ext.applyIf(String,{escape:function(string){return string.replace(/('|\\)/g,"\\$1");},leftPad:function(val,size,ch){var result=new String(val);if(ch===null||ch===undefined||ch===''){ch=" ";}
while(result.length<size){result=ch+result;}
return result;},format:function(format){var args=Array.prototype.slice.call(arguments,1);return format.replace(/\{(\d+)\}/g,function(m,i){return args[i];});}});String.prototype.toggle=function(value,other){return this==value?other:value;};Ext.applyIf(Number.prototype,{constrain:function(min,max){return Math.min(Math.max(this,min),max);}});Ext.applyIf(Array.prototype,{indexOf:function(o){for(var i=0,len=this.length;i<len;i++){if(this[i]==o)return i;}
return-1;},remove:function(o){var index=this.indexOf(o);if(index!=-1){this.splice(index,1);}}});Date.prototype.getElapsed=function(date){return Math.abs((date||new Date()).getTime()-this.getTime());};

if(typeof YAHOO=="undefined"){throw"Unable to load Ext, core YUI utilities (yahoo, dom, event) not found.";}
(function(){var E=YAHOO.util.Event;var D=YAHOO.util.Dom;var CN=YAHOO.util.Connect;var ES=YAHOO.util.Easing;var A=YAHOO.util.Anim;var libFlyweight;Ext.lib.Dom={getViewWidth:function(full){return full?D.getDocumentWidth():D.getViewportWidth();},getViewHeight:function(full){return full?D.getDocumentHeight():D.getViewportHeight();},isAncestor:function(haystack,needle){return D.isAncestor(haystack,needle);},getRegion:function(el){return D.getRegion(el);},getY:function(el){return this.getXY(el)[1];},getX:function(el){return this.getXY(el)[0];},getXY:function(el){var p,pe,b,scroll,bd=document.body;el=Ext.getDom(el);if(el.getBoundingClientRect){b=el.getBoundingClientRect();scroll=fly(document).getScroll();return[b.left+scroll.left,b.top+scroll.top];}else{var x=el.offsetLeft,y=el.offsetTop;p=el.offsetParent;var hasAbsolute=false;if(p!=el){while(p){x+=p.offsetLeft;y+=p.offsetTop;if(Ext.isSafari&&!hasAbsolute&&fly(p).getStyle("position")=="absolute"){hasAbsolute=true;}
if(Ext.isGecko){pe=fly(p);var bt=parseInt(pe.getStyle("borderTopWidth"),10)||0;var bl=parseInt(pe.getStyle("borderLeftWidth"),10)||0;x+=bl;y+=bt;if(p!=el&&pe.getStyle('overflow')!='visible'){x+=bl;y+=bt;}}
p=p.offsetParent;}}
if(Ext.isSafari&&(hasAbsolute||fly(el).getStyle("position")=="absolute")){x-=bd.offsetLeft;y-=bd.offsetTop;}}
p=el.parentNode;while(p&&p!=bd){if(!Ext.isOpera||(Ext.isOpera&&p.tagName!='TR'&&fly(p).getStyle("display")!="inline")){x-=p.scrollLeft;y-=p.scrollTop;}
p=p.parentNode;}
return[x,y];},setXY:function(el,xy){el=Ext.fly(el,'_setXY');el.position();var pts=el.translatePoints(xy);if(xy[0]!==false){el.dom.style.left=pts.left+"px";}
if(xy[1]!==false){el.dom.style.top=pts.top+"px";}},setX:function(el,x){this.setXY(el,[x,false]);},setY:function(el,y){this.setXY(el,[false,y]);}};Ext.lib.Event={getPageX:function(e){return E.getPageX(e.browserEvent||e);},getPageY:function(e){return E.getPageY(e.browserEvent||e);},getXY:function(e){return E.getXY(e.browserEvent||e);},getTarget:function(e){return E.getTarget(e.browserEvent||e);},getRelatedTarget:function(e){return E.getRelatedTarget(e.browserEvent||e);},on:function(el,eventName,fn,scope,override){E.on(el,eventName,fn,scope,override);},un:function(el,eventName,fn){E.removeListener(el,eventName,fn);},purgeElement:function(el){E.purgeElement(el);},preventDefault:function(e){E.preventDefault(e.browserEvent||e);},stopPropagation:function(e){E.stopPropagation(e.browserEvent||e);},stopEvent:function(e){E.stopEvent(e.browserEvent||e);},onAvailable:function(el,fn,scope,override){return E.onAvailable(el,fn,scope,override);}};Ext.lib.Ajax={request:function(method,uri,cb,data){return CN.asyncRequest(method,uri,cb,data);},formRequest:function(form,uri,cb,data,isUpload,sslUri){CN.setForm(form,isUpload,sslUri);return CN.asyncRequest(Ext.getDom(form).method||'POST',uri,cb,data);},isCallInProgress:function(trans){return CN.isCallInProgress(trans);},abort:function(trans){return CN.abort(trans);},serializeForm:function(form){var d=CN.setForm(form.dom||form);CN.resetFormState();return d;}};Ext.lib.Region=YAHOO.util.Region;Ext.lib.Point=YAHOO.util.Point;Ext.lib.Anim={scroll:function(el,args,duration,easing,cb,scope){this.run(el,args,duration,easing,cb,scope,YAHOO.util.Scroll);},motion:function(el,args,duration,easing,cb,scope){this.run(el,args,duration,easing,cb,scope,YAHOO.util.Motion);},color:function(el,args,duration,easing,cb,scope){this.run(el,args,duration,easing,cb,scope,YAHOO.util.ColorAnim);},run:function(el,args,duration,easing,cb,scope,type){type=type||YAHOO.util.Anim;if(typeof easing=="string"){easing=YAHOO.util.Easing[easing];}
var anim=new type(el,args,duration,easing);anim.animateX(function(){Ext.callback(cb,scope);});return anim;}};function fly(el){if(!libFlyweight){libFlyweight=new Ext.Element.Flyweight();}
libFlyweight.dom=el;return libFlyweight;}
if(Ext.isIE){YAHOO.util.Event.on(window,"unload",function(){var p=Function.prototype;delete p.createSequence;delete p.defer;delete p.createDelegate;delete p.createCallback;delete p.createInterceptor;});}
if(YAHOO.util.Anim){YAHOO.util.Anim.prototype.animateX=function(callback,scope){var f=function(){this.onComplete.unsubscribe(f);if(typeof callback=="function"){callback.call(scope||this,this);}};this.onComplete.subscribe(f,this,true);this.animate();};}
if(YAHOO.util.DragDrop&&Ext.dd.DragDrop){YAHOO.util.DragDrop.defaultPadding=Ext.dd.DragDrop.defaultPadding;YAHOO.util.DragDrop.constrainTo=Ext.dd.DragDrop.constrainTo;}
YAHOO.util.Dom.getXY=function(el){var f=function(el){return Ext.lib.Dom.getXY(el);};return YAHOO.util.Dom.batch(el,f,YAHOO.util.Dom,true);};if(YAHOO.util.AnimMgr){YAHOO.util.AnimMgr.fps=1000;}
YAHOO.util.Region.prototype.adjust=function(t,l,b,r){this.top+=t;this.left+=l;this.right+=r;this.bottom+=b;return this;};})();
