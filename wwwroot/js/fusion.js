var Fusion = function(gApiUri, fusionId){
    return {
        api : gApiUri,
        id : fusionId,

        limit : function(limit,token){
            if(typeof token == "undefined") token = null;
            var query = this.api+"?sql=SELECT * FROM "+this.id+" limit "+limit;
            var scope = this;
            return $.ajax({
                type: "GET",
                url : query+(token == null ? "" : "&access_token="+token),
                beforeSend : function(request){
                    request.setRequestHeader("Authorization", 'Bearer ' + token);
                }
            });
        },

        select : function(field,value,extra,token){
            if(typeof token == "undefined") token = null;
            var query = this.api+"?sql=SELECT * FROM "+this.id+" WHERE "+field+" = '"+this.safeValue(value)+"' "+extra;
            var scope = this;
            return $.ajax({
                type: "GET",
                url : query+(token == null ? "" : "&access_token="+token),
                beforeSend : function(request){
                    request.setRequestHeader("Authorization", 'Bearer ' + token);
                }
            });
        },

        queryWithOAuth : function(oAuth,scope,func,args){
            var self = this;
            $def = new $.Deferred();
            oAuth.getToken(function(token){
                args.push(token)
                func.apply(scope,args)
                    .fail(function(data){$def.fail(data)})
                    .done(function(data){$def.resolve(data)});
            });
            return $def.promise();
        },

        /**
         * Does ROWID lookup and update as a single chain of events.  Assigns the
         * function "fusionNext()" to the initial returned ajax object.  This function
         * returns a $.Deferred promise() object which you can then call .fail() and .done() on.  Or
         * other $.Deferred callbacks.
         * @param row A fusion table row object
         * @param field The unique field to lookup row on.
         * @param token The token needed for google fusion queries and updates.
         * @returns {*}
         */
        update : function(row, field, token){
            var scope = this;
            var q = this.qROWID(field+" = '"+this.safeValue(row[field].value)+"'");
            var ajax = this.doGetROWID(q,token);


            var def = $.Deferred();
            ajax.fusionNext = function(){ return def.promise();}

            ajax.done(function(data, textStatus, jqXHR){
                var id = data.rows[0][0];

                //make request and notify deffered of completion
                scope.doUpdate(id, row, token)
                    .success(function(data){def.resolve(data);})
                    .fail(function(){def.fail();})

            }).fail(function(){def.fail();})

            return ajax;
        },
        qROWID : function(whereClause){
            return this.api+"?sql=SELECT ROWID FROM "+this.id+" WHERE "+whereClause;
        },
        doGetROWID : function(query, token){
            var scope = this;
            return $.ajax({
                type: "GET",
                url : query+"&access_token="+token,
                beforeSend : function(request){
                    request.setRequestHeader("Authorization", 'Bearer ' + token);
                }
            });
        },
        doUpdate : function(rowId, row, token){
            return $.ajax({
                type: 'POST',
                url: this.api,
                beforeSend: function (request){
                    request.setRequestHeader("Authorization", 'Bearer ' + token);
                },
                data : this.qUpdate(rowId,row)
            });
        },
        safeValue : function(value){
            return value.replace(/\&/g,'%26');
        },
        qUpdate : function(ROWID, row){
            var update = "sql=UPDATE "+this.id+" SET ";

            var error = false;
            var comma = "";
            for(var x in row){
                try{
                    var fname = row[x].columnName;
                    var value = this.safeValue(row[x].value);
                    update += comma+fname+" = '"+value+"'";
                }catch(e){
                    error = true;
                    this._log("Failed building update string",row[x],e)
                };
                comma = " , ";
            }
            update+=" WHERE ROWID = '"+ROWID+"'";

            if(error){
                this._log("FAILED building query: "+update);
            }
            return update;
        },
        _log : function(){
            console.log(arguments);
        }
    }
};