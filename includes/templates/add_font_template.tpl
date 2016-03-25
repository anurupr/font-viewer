<link href="{base-url}assets/css/lib/ajax-upload-stylesheet.css" rel="stylesheet" type="text/css">
<link href="{base-url}assets/css/lib/jquery.toolbar.css" rel="stylesheet" type="text/css">
<link href="{base-url}assets/css/lib/polaris.css" rel="stylesheet" type="text/css">
<style>
 #add-font-container{
  font-family : "PT Sans Narrow";
 }

 .toolbar{
 	box-sizing: content-box;
 }

 .tool-item{
 	width: 30px;
 	height:39px;
 }

 #file-list ul{
 	float:left;
 	width:100%;
 }

 .icheckbox_polaris{
 	float: right;
	top: 11px;
	left: 10px;
 }

 .select-deselect{
    width: 100%;
    float: left;
    background-color: rgb(66, 66, 66);
    padding: 2px;
    margin-top:5px;
    box-shadow:inset 0px 2px 6px -1px #111;
}

 .select-deselect .icheckbox_polaris{
 	float:left;
 	top:0px;
 	position:relative;
 }

 .icheckbox_polaris + span{
 	float: left;
    position: relative;
    top: 5px;
    left: 10px;
    color: rgb(255, 255, 255);
    margin-right: 10px;

 }

 .icheckbox_polaris:hover + span,
 .icheckbox_polaris + span:hover{
 	color: rgb(0, 205, 153);	
 	cursor:pointer;
 }
 


</style>
<div id="add-font-container">
  <form id="upload" method="POST" enctype="multipart/form-data" action="{add-font-url}" style="width:auto;margin: 5px auto 100px;">
    <div id="drop">
		Drop Here
		<a>Browse</a>
		<input type="file" name="font-file" multiple />
	</div>

	<ul>
		<!-- The file uploads will be shown here -->
	</ul>

	<div id="file-list">
		<div class="toolbar btn-toolbar" style="float:left;">
			<i class="fa fa-cog"></i>
		</div>
		<div class="legend" style="">
			<span>
				<span class="indicator relevant"></span>
				<span class="text">can upload</span>
			</span>
			<span>
				<span class="indicator working"></span>
				<span class="text">can't upload</span>
			</span>
		</div>
		<!--TODO -->
		<!-- Event handling is poor and buggy -->		
		<!-- <div class="select-deselect">
			<input type="checkbox" class="select-relevant"><span>Select all relevant</span>
			<input type="checkbox" class="deselect-relevant"><span>Deselect all relevant</span>
		</div> -->
		<ul></ul>
		<span class="loader">
			<img src="{base-url}assets/images/loader.gif">
		</span>
		<span class="response"></span>
		<a class="process-button disabled">Process</a>
	</div>
	<script src="{base-url}assets/js/lib/jquery.toolbar.js"></script>
    <script src="{base-url}assets/js/lib/jquery.knob.js"></script>
    <script src="{base-url}assets/js/lib/icheck.js"></script>

		<!-- jQuery File Upload Dependencies -->
		<script src="{base-url}assets/js/lib/jquery.ui.widget.js"></script>
		<script src="{base-url}assets/js/lib/jquery.iframe-transport.js"></script>
		<script src="{base-url}assets/js/lib/jquery.fileupload.js"></script>
		
		<!-- Ajax Upload JS file -->
		<script src="{base-url}assets/js/lib/ajax-upload-script.js"></script>
		<script type="text/javascript">
		$(document).ready(function(e){
			$("body").on("click",".view-files",function(e){				
				var form = $("#upload");
				var top = form.offset().top;
				var w = form.outerWidth();
				$("#file-list")
				.css("top",top+"px")
				.css("left",w+"px")
				.css("width",w+"px")
				.slideToggle();

			});

			$('.toolbar').toolbar({
				content: '#toolbar-options',
			});

			$(".close-list").on("click",function(e){
				$("#file-list")				
				.css("left","-9999px")				
				.slideToggle();

			});

			$('input[type=checkbox]').iCheck({
                checkboxClass: 'icheckbox_polaris',
                radioClass: 'iradio_polaris',
                increaseArea: '-10%'
            });

            $(".icheckbox_polaris + span").on("hover",function(e){
            	$(this).prev(".icheckbox_polaris").toggleClass("hover");
            });

            $(".icheckbox_polaris + span").on("click",function(e){
            	$(this).prev(".icheckbox_polaris").iCheck('toggle');
            });

            $("body").on("click","#file-list ul li.relevant",function(e){
            	$(this).find(".icheckbox_polaris").iCheck("toggle");
            });


            $(".select-deselect input[type=checkbox]").on("each",function(e){
            	$(this).iCheck("uncheck");
            });


            $("body").on("ifToggled","#file-list ul li input[type=checkbox]",function(e){
            	var checkedboxes = $("#file-list ul li input[type=checkbox]:checked").length;
            	var allcheckboxes = $("#file-list ul li.relevant").length;
            	var selectrelevant = $(".select-relevant");
            	var deselectrelevant = $(".deselect-relevant");

            	if(checkedboxes > 0)
            		$(".process-button").removeClass("disabled");
            	else
            		$(".process-button").addClass("disabled");

            	console.log('allcheckboxes == checkedboxes',allcheckboxes == checkedboxes);
            	if(allcheckboxes == checkedboxes){
            		if(!selectrelevant.prop("checked"))
            			selectrelevant.iCheck("check");
            	}
            	else{
            		if(selectrelevant.prop("checked"))
            			selectrelevant.iCheck("uncheck");
            	}

            	if(checkedboxes == 0){
            		if(!deselectrelevant.prop("checked"))
            			selectrelevant.iCheck("check");
            	}
            	else{
            		if(deselectrelevant.prop("checked"))
            			deselectrelevant.iCheck("uncheck");

            	}


            });

            $("body").on("ifChanged",".select-deselect input[type=checkbox]",function(e){
            	console.log("ifChanged fired");
            	var checkbox = $(this);
            	var fileListUL = $("#file-list ul");

            	// checked
            	if(checkbox.prop("checked")){
            		if(checkbox.hasClass("select-relevant")){
	            		//select all relevant
	            		// uncheck other checkbox 
	            		$(".deselect-relevant").iCheck('uncheck');
	            		fileListUL.find(".relevant input[type=checkbox]").each(function(e){
	            			$(this).iCheck("check");
	            		});
	            		$(".process-button").removeClass("disabled");

	            	}
	            	else if(checkbox.hasClass("deselect-relevant")){
	            		//deselect all relevant
	            		$(".select-relevant").iCheck('uncheck');
	            		fileListUL.find(".relevant input[type=checkbox]").each(function(e){
	            			$(this).iCheck("uncheck");
	            		});
	            		$(".process-button").addClass("disabled");
	            	}
            	}
            	// unchecked
            	else{

            		if(checkbox.hasClass("select-relevant")){
	            		//select all relevant
	            		// uncheck other checkbox 
	            		$(".deselect-relevant").iCheck('uncheck');
	            		fileListUL.find(".relevant input[type=checkbox]").each(function(e){
	            			$(this).iCheck("uncheck");
	            		});
	            		$(".process-button").addClass("disabled");

	            	}
	            	else if(checkbox.hasClass("deselect-relevant")){
	            		//deselect all relevant
	            		$(".select-relevant").iCheck('uncheck');
	            		fileListUL.find(".relevant input[type=checkbox]").each(function(e){
	            			$(this).iCheck("uncheck");
	            		});
	            		$(".process-button").addClass("disabled");
	            	}
            	}
	            	

            });

            $(".process-button").on("click",function(e){

            	if(!$(this).hasClass("disabled")){
            		// context related to this ajax call
            		var context = $("#upload");
            		// get upload id
            		var upload_id = $("#upload ul li.active").data("upload-id");
            		var files_to_process = [];
            		$("li.relevant input[type=checkbox]:checked").each(function(){
            			var filename = $(this).parents("li").find("p").text();
            			files_to_process.push(filename);
            		});
            		$.ajax({
            			type : "POST",
            			url  : "{base-url}ajax/add-font.php",
            			dataType : "json",
            			data : {
            				upload_id : upload_id,
            				files : files_to_process
            			},
            			beforeSend : function(){
            				context.find(".loader").show();
            			},
            			success : function(response){
            				console.log(response);
            				context.find(".loader").hide();
            				if(response.code == 0)
            					context
            					.find(".response")
            					.removeClass("error")
            					.addClass("success")
            					.text(response.message)
            					.show();
            				else
            					context
            					.find(".response")
            					.removeClass("success")
            					.addClass("error")
            					.text(response.message)
            					.show();
            			},
            			error : function(response){
            				console.error(response);
            				context.find(".loader").hide();
            				context.find(".response").removeClass("success").addClass("error").text(response.message).show();
            			}
            		})
            	}
            });

         });
            	
		</script>
  </form>
</div>

<div id="toolbar-options" class="hidden">
   <a href="#" class="close-list" title="Close list">
   		<i class="fa fa-close"></i>
   </a>  
</div>