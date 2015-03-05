    var counter;
    $(document).ready(function(){
       changepage(1,qid);
      $("#submit_comm").click(function(){
        var comment = document.getElementById("comment").value;
        $.post("saveComment.php",{comment:comment,qid:qid},function(){
        changepage(1,qid);
        $("#comment").val("");
        });
      });
      $("#start_timer").click(function(){
        var countdown = document.getElementById("countdown").value;
        $.post("setTimer.php",{start_timer:countdown,qid:qid},function(data){
        clearTimeout(counter);
        $("#timer_place").html(data);
        });
        clearTimeout(graph);
        graph = setTimeout("loadGraph()",countdown*1000+1000);
      });
      $("#stop_timer").click(function(){
        $.post("setTimer.php",{stop_timer:"1",qid:qid},function(data){
        $("#timer_place").html(data);
        });
        clearTimeout(graph);
        if (count >= 0)
        {  
          graph = setTimeout("loadGraph()",500);
        }
      });
      $("#reset_timer").click(function(){
        $.post("setTimer.php",{reset_timer:"1",qid:qid},function(data){
        clearTimeout(counter);
        $("#timer_place").html(data);
        });
      });
      $("#extend_timer").click(function(){
        var countdown = document.getElementById("countdown").value;
        $.post("setTimer.php",{extend_timer:countdown,qid:qid},function(data){
        clearTimeout(counter);
        $("#timer_place").html(data);
        clearTimeout(graph);
        graph = setTimeout("loadGraph()",count*1000+2000);
        });
      });
      if (!(typeof(student) == "undefined"))
        loadGraph();
      else if (count > 0)
        graph = setTimeout("loadGraph()",count*1000+2000);
      else
        loadGraph();

    });

    function changepage(page,qid){
        $.post("comment.php",{page:page,qid:qid},function(data){
            $("#comment_list").html(data);
        });
    }
    
    function loadGraph(){
     $.post("graph.php",{qid:qid},function(data){
        $("#graph").html(data);
        });
     }
