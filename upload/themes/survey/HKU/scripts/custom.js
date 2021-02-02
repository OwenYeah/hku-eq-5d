/******************
    User custom JS
    ---------------

   Put JS-functions for your template here.
   If possible use a closure, or add them to the general Template Object "Template"
*/


$(document).on('ready pjax:scriptcomplete',function(){
    /**
     * Code included inside this will only run once the page Document Object Model (DOM) is ready for JavaScript code to execute
     * @see https://learn.jquery.com/using-jquery-core/document-ready/
     */
    $("#ls-button-skip").click(function(){
        $("input[type=radio]").attr("checked", "");
        $("input[type=radio]").attr("checked", false);
        // $("#navigator-container button[type=submit]").trigger("click");
    })
    $("#ls-button-skip-agesexs").click(function(){
        $("input[name=age]").val("");
        $("input[name=sno]").val("");
        $("input[type=radio]").attr("checked", "");
        $("input[type=radio]").attr("checked", false);
        //$("#ls-button-submit").trigger("click");
    })
    $(".question-valid-container").appendTo("#append-box");
    $(".answer-container").appendTo("#append-box");
    $("button[type=submit]").click(function(e){
        if($(this).hasClass("disabled")) {
            e.preventDefault();
        } else {
            if($(this).attr("id") != "ls-button-skip" && $(this).attr("id") != "ls-button-skip-agesexs") $("button").addClass("disabled");
        } 
    })
});
