var last_response_general_json = {"5001": {"a": 0}};
var last_response_specific_json = {"a": 0};

var pieChart = undefined;
var last_completadas = -999;
var last_abandonadas = -999;



function getQueueStates(){
    $.ajax({
        type: "POST",
        url: ref_url+"?url=colas/get_queues_states",
        data: {
            info: "infocolas"
        }, 
        success:function(html) {
            // filter to evade HTML rubish. This is a app custom protocol.
           

            try {
                full_response = extractJSONData(html)

                infocolas = full_response.infocolas;
                global_summary = full_response.global_summary;
                queue_detailed = full_response.queue_detailed;

                // console.log("infocolas", infocolas)
                // console.log("global_summary", global_summary)
                // console.log("queue_detailed", queue_detailed)

                
                
                if(existNewDataInTableStatesGeneral(last_response_general_json, infocolas)){
                    resetTable("table-queue-state");
                    fillTableStates(infocolas, global_summary, queue_detailed, false)
                }


                if(global_summary != null){
                    if(global_summary.total_completadas != null){
                        // only update if is diferent
                        if(last_completadas != global_summary.total_completadas || last_abandonadas != global_summary.total_abandonadas){
                            if(pieChart != undefined){
                                pieChart.destroy();
                            }
            
                            
                            pieChart = plotPieChartAbandonadasCompletadas([global_summary.total_completadas, global_summary.total_abandonadas])
                            last_completadas = global_summary.total_completadas
                            last_abandonadas = global_summary.total_abandonadas
                            $(".chart-container").show()
                        }
                    }
                    else{
                        if(pieChart != undefined){
                            pieChart.destroy();
                        }
                        $(".chart-container").hide()
                    }
                }
                else{
                    if(pieChart != undefined){
                        pieChart.destroy();
                    }
                    $(".chart-container").hide()
                }
                
                
                

                
                resetTable("table-entry-calls");
                resetElement("operators_in_call")


                var agents = [];

                for (const [key, element] of Object.entries(infocolas)){
                    
                    if(element['agentes'] != undefined){
                        
                        if(element['agentes'].length > 0){
                            agents = agents.concat(element['agentes'])
                        }
                    }
                }

                if(agents.length > 0){
                    fillAgentsState(agents, false)
                }

                var calls = [];

                for (const [key, element] of Object.entries(infocolas)){


                    if(element['llamadas'] != undefined){
                        if(element['llamadas'].length > 0){
                            calls = calls.concat(element['llamadas'])
                        }
                    }
                }

                if(calls.length > 0)
                {
                    
                    fillEntryCalls(calls)
                }



                

                last_response_general_json = infocolas;
            
            } catch (error) {
                console.log(html);
                console.log("ERROR: " + error + "READ HTML ABOVE FOR MORE INFO")
            }
            
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log("ERROR");
            console.log("STATUS: ", xhr.status);
            console.log(ajaxOptions);
            console.log(thrownError);
        }
    })
}

function getSpecificQueueState(queue_val){



    $.ajax({
        type: "POST",
        url: ref_url+"?url=colas/get_queue_state",
        data: {
            info: "infocolas",
            queue: queue_val
        }, 
        success:function(html) {
            // filter to evade HTML rubish. This is a app custom protocol.


            console.log(html)
            try {

                full_response = extractJSONData(html)


                infocola = full_response.infocola;
                queue_detailed = full_response.queue_detailed;


                // console.log(queue_detailed)

                if(infocola == null){
                    console.log("no se encontraron datos de ese id")
                    return;
                }

                if(existNewDataInTableStatesSpecific(last_response_specific_json, infocola)){
                    resetTable("table-queue-state");
                    fillTableStates({queue_val: infocola}, {queue_val: queue_detailed}, {queue_val: queue_detailed}, true)
                }

                if(queue_detailed != null){
                     // only update if is diferent
                    if(last_completadas != queue_detailed.total_completadas || last_abandonadas != queue_detailed.total_abandonadas){
                        if(pieChart != undefined){
                            pieChart.destroy();
                        }
        
                        
                        pieChart = plotPieChartAbandonadasCompletadas([parseInt(queue_detailed.total_completadas), parseInt(queue_detailed.total_abandonadas)])
                        last_completadas = queue_detailed.total_completadas
                        last_abandonadas = queue_detailed.total_abandonadas

                        $(".chart-container").show()
                        
                    }
                }
                else{
                    if(pieChart != undefined){
                        pieChart.destroy();
                    }

                    $(".chart-container").hide()
                        
                }
                
                

                
                resetTable("table-entry-calls");
                resetElement("operators_in_call");


                if(infocola['agentes'].length > 0){

                    var agents = infocola['agentes'];

                    fillAgentsState(agents, true)
                }



                if(infocola['llamadas'].length > 0){

                    var calls = infocola['llamadas'];
                    fillEntryCalls(calls);
                }

                

                last_response_specific_json = infocola;
            
            } catch (error) {
                console.log(html);
                console.log("ERROR: " + error + "READ HTML ABOVE FOR MORE INFO")
            }
            
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log("ERROR");
            console.log("STATUS: ", xhr.status, ajaxOptions, thrownError);
        }
    })
}


function plotPieChartAbandonadasCompletadas(data){
    // data = [num_abandonadas, num_completadas]
    console.log("Data", data)

    var ctx = $('#chartPie')[0].getContext('2d');

    ctx.height = 500;
    var labels = ["Completadas", "Abandonadas"]
    var colors = [
        'rgb(54, 162, 235)',
        'rgb(255, 99, 132)'
    ]
    var options = {
        
        tooltips: {
            enabled: true
        },
        animation:{
            animateScale: true
        },
        plugins: {
            datalabels: {
                formatter: (value, ctx) => {
                
                    let sum = 0;
                    let dataArr = ctx.chart.data.datasets[0].data;
                    dataArr.map(data => {
                        sum += parseInt(data);
                    });

                    // console.log(value)
                    if(value == 0){
                        return ""
                    }
                    let percentage = (value*100 / sum).toFixed(2)+" %";
                    return percentage;

            
                },
                color: '#fff',
            }
        }


    }


    
    

    return createChart(ctx, "pie", "Llamadas completadas vs abandonadas", labels, data, colors, options)

}

