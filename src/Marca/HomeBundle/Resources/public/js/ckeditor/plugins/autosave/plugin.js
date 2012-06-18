/**
 * Autosave plugin for CKEditor
 * 
 * CKEDITOR.config.autosave_delay - delay in seconds before request is sent (located in config.js)
 * CKEDITOR.config.autosave_extra_post_data - object with extra data to be passed to the server with POST request
 * #AUTO_SAVE_URL - input (usually hidden) with save url (located on the page)
 * 
 * Server's supposed to send back {status: ok|error, (optional message: text)}
 *
 * Place it in plugins/autosave directory and don't forget to add 'autosave' to config.extraPlugins
 * @author Boris Shemigon <i@boris.co>
 */

(function() {
    var pluginName = 'autosave';

    var timeOutId = 0,
        url = document.getElementById("AUTO_SAVE_URL").value,
        ajaxActive = false;

    /**
     * Serializes a given object
     * @param data an object
     */
    function serialize(data) {
        var parts = [];
        for(var k in data) {
            parts.push(k + "=" + data[k]);
        }
        return parts.join("&");
    }

    /**
     * Makes a POST request to the server
     * @param url Make request to
     * @param data Serialized data
     * @param callback Function that will be executed on success
     */
    function post(url, data, callback) {
        var xhr = window.XMLHttpRequest?
            new XMLHttpRequest(): // IE7+, Firefox, Chrome, Opera, Safari
            new ActiveXObject("Microsoft.XMLHTTP"); // IE6, IE5

        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                if(xhr.status == 200) {
                    var response = {};
                    try {
                        response = eval("(" + xhr.responseText + ")");
                    }
                    catch(e) {
                        throw "Wrong JSON formatting";
                    }
                    if(typeof callback === "function") {
                        callback(response);
                    }
                }
                //TODO: Add handlers for other error codes
            }
        }
        xhr.open("POST", url, true);
        xhr.send(serialize(data));
    }

    if(url) {
        var startTimer = function(event) {
            if(timeOutId) {
                clearTimeout(timeOutId);
            }
            var delay = CKEDITOR.config.autosave_delay;
            timeOutId = setTimeout(onTimer, delay*1000, event);
        }
        var onTimer = function (event) {
            if(ajaxActive) {
                startTimer(event);
            }
            else if(event.editor.checkDirty()) {
                ajaxActive = true;
                var data = CKEDITOR.config.autosave_extra_post_data;
                data['content'] = encodeURIComponent(event.editor.getData());
                post(url,
                    data,
                    function(response) {
                        ajaxActive = false;
                        if(response.status !== 'ok') {
                            alert(response.message || 'Unknown error happened');
                        }
                    }
                );
            }
        }
        
        CKEDITOR.plugins.add( pluginName, {
            init : function( editor ) {
                editor.on('key', startTimer);
            }
        });
    }
    else {
        throw 'AUTO_SAVE_URL hidden input not found';
    }
})();

/**
 * Delay in seconds
 */
CKEDITOR.config.autosave_delay = 3;
/**
 * It serves to provide additional data for server in POST request
 */
CKEDITOR.config.autosave_extra_post_data = {};
