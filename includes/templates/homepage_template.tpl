<style>
.homepage-container{
	width:100%;
	height:500px;
	float:left;
	border-bottom:1px solid #aaa;
}

.font-list-container,
.view-container{
	float:left;	
	height:100%;
}

.font-list-container{
	width:35%;
	border-right:1px solid #aaa;
}

.view-container{
	width:65%;
}

.font-list-container li{
	box-shadow: inset 0px 0px 15px -2px #aaa;
    height: 100px;
    background: white;
}

.font-list-container li:hover{
	cursor:pointer;
	box-shadow:none;
}


.text-input{
	width: 50%;
    margin-left: auto;
    margin-right: auto;    
    height: 10%;
    box-shadow: 0px 3px 11px -2px #444;
}

.text-input input{
	width:100%;
	height:100%;
	font-size:xx-large;
	font-family:"Arial";
}

.text-output{
    float: left;
    width: 100%;
    height: 87%;
    background-color: #fff;
    margin-top: 1em;
    box-shadow: inset 0px 0px 15px -2px #aaa;
}

</style>

<div class="homepage-container">
  <div class="font-list-container">
  	<ul>
  		{font-list}
  	</ul>
  </div>
  <div class="view-container">
  	<div class="text-input"><input type="text" placeholder="Type here" autofocus></div>
  	<div class="text-output"></div>
  </div>
  <script type="text/javascript">

    function showOutput(text){
      var textOutput = $("div.text-output");
      var activeLi = $(".font-list-container li.active");
      textOutput.css('font-family',activeLi.data('font-family')).text(text);
    }

    function checkText(){
      var textInput = $("div.text-input input");
      var text = textInput.val();
      if(text != undefined && text.length > 0){
        showOutput(text);
      }
    }

    $(document).ready(function(e){
      $(".font-list-container li").on("click",function(e){
        var d = $.parseJSON($(this).attr('data-obj'));
        if(!$(this).hasClass("active")){
          $(".font-list-container li").not($(this)).removeClass("active");        
          $(this).addClass("active");

          $(this).data('font-family',d.name);          

          $.ajax({
            type : "POST",
            url  : "{base-url}ajax/view-font.php",
            dataType : "json",
            data : d,
            success : function(msg){
              $("body").append('<style>'+msg.css+'</style>');

              checkText();

            },
            error : function(msg){
              console.log(msg);
            }                  
          });
        }
        
      });


      $(".text-input input").on("keyup change",function(e){
        showOutput($(this).val());
      });
    });


  </script>
</div>