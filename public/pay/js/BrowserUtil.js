var browserUtil={isIeOrEdge:null,userAgent:null,isPc:null,init:function(){try{this.userAgent=this.getUserAgent()
this.isIeOrEdge=this.isIEorEdgeFun()
this.isPc=this.isPcFun()}catch(e){throw new Error("Load BrowserUtilModule Fail")}},getUserAgent:function(){return navigator.userAgent},isIEorEdgeFun:function(){if(!!window.ActiveXObject||"ActiveXObject"in window){return true;}else if(navigator.userAgent.indexOf("Edge")>-1){return true;}
return false;},isPcFun:function(){let userAgentInfo=navigator.userAgent;let Agents=["Android","iPhone","SymbianOS","Windows Phone","iPad","iPod"];let flag=true;for(var v=0;v<Agents.length;v++){if(userAgentInfo.indexOf(Agents[v])>0){flag=false;break;}}
return flag;}}