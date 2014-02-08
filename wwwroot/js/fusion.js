var Fusion = function(gApiUri, fusionId, oAuth){
    return {
        api : gApiUri,
        id : fusionId,
        oAuth : oAuth,
        _getToken : function(callback){
            this.oAuth.getToken(callback);
        },
        _qROWID : function(whereClause){
            return this.api+"?sql=SELECT ROWID FROM "+this.fusionTableId+" WHERE "+whereClause;
        },
        _doGetROWID : function(query, token, callback){
            var scope = this;
            return $.ajax({
                type: "GET",
                url : query+"&access_token="+token,
                beforeSend : function(request){
                    request.setRequestHeader("Authorization", 'Bearer ' + token);
                }
            }).done(function(data){
                try{
                    callback(data.rows[0][0]);
                }catch(e){
                    scope._log("Failed getting unique row id",query,e);
                }
                callback(null);
            });
        },
        _doUpdate : function(query, rowId, token){
            query += " WHERE ROWID = '"+rowId+"'";
            return $.ajax({
                type: 'POST',
                url: this.api,
                beforeSend: function (request){
                    request.setRequestHeader("Authorization", 'Bearer ' + token);
                },
                data : query
            });
        },
        _safeValue : function(value){
            return value.replace(/\&/g,'%26');
        },
        update : function(row, wField, wValue, callback){
            var scope = this;
            this._getToken(function(token){
                var qRow = this._qROWID(wField + " = '" + this._safeValue(wValue) + "'",token);
                var jQAjax = null;
                jQAjax = scope._doGetROWID(qRow, token, function(rowid){
                    //not sure this can possibly work
                    if(rowid == null){
                        callback(jQAjax);
                    }
                    var update = scope._qUpdate(rowid, row);
                    callback(scope._doUpdate(update, rowid, token));
                });
            });
        },
        qUpdate : function(ROWID, row){
            var update = "sql=UPDATE "+scope.fusionTableId+" SET ";

            var error = false;
            var comma = "";
            for(var x in row){
                try{
                    var fname = row[x].columnName;
                    var value = this._safeValue(row[x].value);
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