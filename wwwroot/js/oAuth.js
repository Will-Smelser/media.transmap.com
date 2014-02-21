/**
 * Created by Will on 2/2/14.
 */
/**
 * The gapi from google can do all this, but it is a little more complicated and requires the
 * apiKey, which seems like a bad idea.
 */
var oAuth = {
    client_id : '16942626072-oqu5fdjnaed93hua437avv7k5skb5jgl.apps.googleusercontent.com',
    redirect_uri : 'http://media.transmap.com/oauth2/client.php',
    scope : 'https://www.googleapis.com/auth/fusiontables',
    token : null, expires : null, win : null,
    winHeight : 300, winWidth : 500,
    setToken : function(token, expires){
        this.token = token;
        this.expires = expires;
    },
    getToken : function(callback){
        var now = new Date();
        if(this.token !== null && this.expires !== null && now < this.expires){
            callback(this.token,this.expires);
            return;
        }

        var obj = this;
        this.makeRequest(this.client_id,this.redirect_uri,this.scope);
        this.onLoad(function(parsedUrl){
            try{
                obj.token = parsedUrl['access_token'];
                obj.expires = new Date(now.getTime() + parseInt(parsedUrl['expires_in'])*1000);
            }catch(e){
                callback("_NO_TOKEN_",now);
                return;
            }
            callback(obj.token,obj.expires);
        });
    },
    getUrl : function(){
        return this.win.document.location.href;
    },
    /**
     * Parses the url for uri variables.  Checks directly against the
     * window object looking after the # symbol.
     * @returns {{}}
     */
    parseUrl : function(){
        var result = {};
        try{
            var queryString = this.win.location.hash.substring(1),
                regex = /([^&=]+)=([^&]*)/g, m;
            while (m = regex.exec(queryString)) {
                result[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
            }
        }catch(e){
            //do nothing, cannot access uri of closed windows or cross domain
            console.log(e);
        }
        return result;
    },
    checkForToken : function(){
        return (this.getUrl().indexOf('#access_token=')<0)?false:true;
    },
    /**
     * Open the child windows and make oAuth request.
     * @param client_id
     * @param redirect
     * @param scope
     */
    makeRequest : function(client_id, redirect, scope){
        var request = 'https://accounts.google.com/o/oauth2/auth?'+
            'client_id='+client_id+'&'+'redirect_uri='+redirect+'&'+
            'scope='+scope+'&'+'response_type=token';
        this.win = window.open(request,"",'left=100,top=100,width='+this.winWidth+",height="+this.winHeight);
    },
    /**
     * Make an object containing members: access_token, expires_in
     * These are valid values, but may not be a working token if
     * the URI failed to parse.
     * @returns {*}
     * @private
     */
    _makeInfoObj : function(){
        var results = this.parseUrl();
        if(typeof results['access_token'] == "undefined"
            || typeof results['expires_in'] == "undefined"){
            results['access_token'] = "_INVALID_TOKEN_";
            results['expires_in'] = -99999;
        }
        return results;
    },
    /**
     * Polls the child windows waiting for changes in state, calls
     * the callback with a token and expire time offset.
     * @param callback
     */
    onLoad : function(callback){
        //make sure we dont keep calling
        if(this.win && this.win.closed){
            callback(this._makeInfoObj());
            return;
        }

        var obj = this;
        var url = null;
        try{
            //can cause cross origin issue if on google login page
            //can fail if window is closed
            url = this.getUrl();
        }catch(e){
            setTimeout(function(){obj.onLoad.call(obj,callback);},1500);
            return;
        }

        if(url.substr(0,Math.min(url.length,this.redirect_uri.length)) != this.redirect_uri){
            console.log("not the correct url");
            setTimeout(function(){obj.onLoad.call(obj,callback);},1500);
            return;
        }

        callback(this._makeInfoObj());
        this.win.close();
    }
};
