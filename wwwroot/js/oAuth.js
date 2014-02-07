/**
 * Created by Will on 2/2/14.
 */
/**
 * The gapi from google can do all this, but it is a little more complicated and requires the
 * apiKey, which seems like a bad idea.
 */
var oAuth = {
    client_id : '16942626072-oqu5fdjnaed93hua437avv7k5skb5jgl.apps.googleusercontent.com',
    redirect_uri : 'http://media.transmap.us/oauth2/client.php',
    scope : 'https://www.googleapis.com/auth/fusiontables',
    token : null, expires : null, win : null,
    winHeight : 300, winWidth : 500,
    setToken : function(token, expires){
        this.token = token;
        this.expires = expires;
    },
    getToken : function(callback){
        var now = new Date();
        if(this.token !== null && now < this.expires){
            callback(this.token);
            return;
        }

        var obj = this;
        this.makeRequest(this.client_id,this.redirect_uri,this.scope);
        this.onLoad(function(parsedUrl){
            obj.token = parsedUrl['access_token'];
            obj.expires = new Date(now.getTime() + parseInt(parsedUrl['expires_in'])*1000);
            callback(obj.token,obj.expires);
        });
    },
    getUrl : function(){
        return this.win.document.location.href;
    },
    parseUrl : function(url){
        var result = {}, queryString = this.win.location.hash.substring(1),
            regex = /([^&=]+)=([^&]*)/g, m;
        while (m = regex.exec(queryString)) {
            result[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
        }
        return result;
    },
    checkForToken : function(){
        return (this.getUrl().indexOf('#access_token=')<0)?false:true;
    },
    makeRequest : function(client_id, redirect, scope){
        var request = 'https://accounts.google.com/o/oauth2/auth?'+
            'client_id='+client_id+'&'+'redirect_uri='+redirect+'&'+
            'scope='+scope+'&'+'response_type=token';
        this.win = window.open(request,"",'left=100,top=100,width='+this.winWidth+",height="+this.winHeight);
    },
    onLoad : function(callback){
        if(this.getUrl().indexOf(this.redirect_uri)<0){
            var obj = this;
            setTimeout(function(){obj.onLoad.call(obj,callback);},1500);
            return;
        }
        callback(this.parseUrl(this.getUrl()));
        this.win.close();
    }
};
