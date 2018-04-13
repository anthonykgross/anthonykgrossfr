$(document).ready(function(){

    var extraKeys = {
        "F11": function(cm) {
            cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
            if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
        }
    };

    var extraJS = document.getElementById('page_extraJS');
    CodeMirror.fromTextArea(extraJS, {
        mode:  "javascript",
        lineNumbers: true,
        theme: 'material',
        extraKeys: extraKeys
    });

    var extraCSS = document.getElementById('page_extraCSS');
    CodeMirror.fromTextArea(extraCSS, {
        mode:  "css",
        lineNumbers: true,
        theme: 'material',
        extraKeys: extraKeys
    });

    var content = document.getElementById('page_content');
    CodeMirror.fromTextArea(content, {
        mode:  "htmlmixed",
        lineNumbers: true,
        theme: 'material',
        extraKeys: extraKeys
    });
});