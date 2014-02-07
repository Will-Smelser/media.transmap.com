/**
 * Created by Will on 2/2/14.
 */

var Store = function(prefix){

    var length = 0;

    var getPrefix = function(key){
        return key.substring(0,prefix.length);
    };

    for(var i=0; i< localStorage.length; i++){
        var key = localStorage.key(i);
        if(getPrefix(key) === prefix){
            length++;
        }
    }

    return {
        length : length,
        jsonToStr : function(obj) {
            return JSON.stringify(obj);
        },
        _prefix : prefix,
        _key : function(key){
            return prefix+key;
        },
        _toKey : function(key){
            return key.substring(prefix.length);
        },
        iterate : function(func, start, stop){
            if(typeof start == "undefined") start = 0;
            if(typeof stop == "undefined" || stop > this.length) stop = this.length;

            var j = 0;
            for(var i=0; i <localStorage.length; i++){
                var key = localStorage.key(i);
                if(key != null && getPrefix(key) == prefix){
                    if(start <= j && j < stop){
                        var obj = localStorage.getItem(key);
                        func(this._toKey(key),JSON.parse(obj));
                    }
                    j++;
                }
            }
        },
        setItem : function(key, obj){
            var key2 = this._key(key);
            if(localStorage.getItem(key2) == null)
                this.length++;
            try{
                localStorage.setItem(key2,this.jsonToStr(obj));
            }catch(e){
                this._log(e);
                this.length--;
            }
        },
        /* get an item */
        getItem : function(key){
            return JSON.parse(localStorage.getItem(this._key(key)));
        },
        removeItem : function(key){
            var key2 = this._key(key);
            if(localStorage.getItem(key2) != null)
                this.length--;
            try{
                this._log("Remove "+key);
                localStorage.removeItem(key2);
            }catch(e){
                this._log(e);
            }
        },
        clear : function(){
            var scope = this;
            var keys = [];
            //cannot iterate and remove in-place
            this.iterate(function(key,obj){
                keys.push(key);
            });
            for(var x in keys){
                this.removeItem(keys[x]);
            }
        },
        _log : function(msg){
            console.log("Store: "+msg);
        }
    };
};