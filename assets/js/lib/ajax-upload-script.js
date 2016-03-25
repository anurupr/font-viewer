$(function(){

    var ul = $('#upload ul');

    var relevantExt = ["ttf","otf","eot","woff"];



    $('#drop a').click(function(){
        // Simulate a click on the file input button
        // to show the file browser dialog
        $(this).parent().find('input').click();
    });

    // Initialize the jQuery File Upload plugin
    $('#upload').fileupload({

        // This element will accept file drag/drop uploading
        dropZone: $('#drop'),

        // This function is called when a file is added to the queue;
        // either via the browse button, or via drag/drop:
        add: function (e, data) {

            var tpl = $('<li class="working"><input type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span><a href="#" class="view-files">View Files</div></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = tpl.appendTo(ul);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },

        fail:function(e, data){
            // Something has gone wrong!
            data.context.addClass('error');            
        },
        done: function (e, data) {
            //console.log('e',e);
            //console.log('data',data);
            var response = $.parseJSON(data.result);
            if(response != undefined){
                var files = response.files;
                var upload_id = response.upload_id;
                // store the upload id as a data attribute
                data.context.addClass("active").data("upload-id",upload_id);

                // definitely an archive file
                if(files.length > 1){
                    clearZipFileList();
                    data.context.find(".view-files").show();
                    for(var i=0,len = files.length ; i < len ; i++){
                        var file = files[i];
                        var ext  = file.substring(file.lastIndexOf(".")+1).toLowerCase();
                        if(relevantExt.indexOf(ext) != -1){
                            // it is a relevant extension
                            //var tpl = $('<li class="relevant"><p></p><span></span><input type="checkbox"></li>');
                            var tpl = $('<li class="relevant"><p></p><input type="checkbox"></li>');
                            tpl.find("p").text(file);
                            $("#file-list ul").append(tpl);
                        }
                        else if(relevantExt.indexOf(ext) == -1){
                                // it is a relevant extension
                                //var tpl = $('<li class="working"><p></p><span></span></li>');
                                var tpl = $('<li class="working"><p></p></li>');
                                tpl.find("p").text(file);
                                $("#file-list ul").append(tpl);
                                $('input[type=checkbox]').iCheck({
                                    checkboxClass: 'icheckbox_polaris',
                                    radioClass: 'iradio_polaris',
                                    increaseArea: '-10%'
                                });

                            
                        }
                    }
                }
            }
        }

    });


    // Prevent the default action when a file is dropped on the window
    $(document).on('drop dragover', function (e) {
        e.preventDefault();
    });

    // Helper function that formats the file sizes
    function formatFileSize(bytes) {
        if (typeof bytes !== 'number') {
            return '';
        }

        if (bytes >= 1000000000) {
            return (bytes / 1000000000).toFixed(2) + ' GB';
        }

        if (bytes >= 1000000) {
            return (bytes / 1000000).toFixed(2) + ' MB';
        }

        return (bytes / 1000).toFixed(2) + ' KB';
    }

    // Helper function to clear zip file list after upload is successful and is a zip file
    function clearZipFileList(){
        $("#file-list ul").empty();
    }

});