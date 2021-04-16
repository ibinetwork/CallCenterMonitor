const tag_type ={
    "Pausado": "danger", 
    "Ocupado": "secondary", 
    "Conectado": "primary", 
    "Offline": "info", 
    "Libre": "success", 
    "Timbrando": "warning"
}



function fillTableStates(data, global_summary, queue_detailed, is_specific_queue){

    for (const [queue, element] of Object.entries(data)){

        // console.log(element)

        if((element['llamactuales'] > 0 || element['llamencola'] > 0) || is_specific_queue){
                
            var t_data = '<tr class="col-xs-1 text-center table-default">';
            t_data += '<th scope="row">' + element['nomcola'] + '</th>';
            t_data += '<td>' + element['agentes'].length + '</td>';
            t_data += '<td>' + element['llamactuales'] + '</td>';
            t_data += '<td>' + element['llamencola'] + '</td>';
            //t_data += '<td>' + element['llamcontestadas'] + '</td>';
            //t_data += '<td>' + element['llamabandonadas'] + '</td>';
           

            if(queue_detailed[""+queue] == undefined || queue_detailed[""+queue] == null){
                // t_data += '<td>' + formatNumber(0) + '</td>';
                t_data += '<td>' + formatNumber(0) + '</td>';
                t_data += '<td>' + formatNumber(0) + '</td>';
            }
            else{
                // t_data += '<td>' + formatNumber(Math.round(parseFloat(queue_detailed[queue]["total_NS_30"]))) + '</td>';
                t_data += '<td>' + formatNumber(Math.round(parseFloat(queue_detailed[queue]["t_prom_duracion"]))) + '</td>';
                t_data += '<td>' + formatNumber(Math.round(parseFloat(queue_detailed[queue]["t_prom_espera"]))) + '</td>';
                   
            }

            

            t_data += '<td>' + element['niveldeservicio'].replace('within', 'en') + '</td>';
            t_data += '</tr>';
            $('#table-queue-state > tbody').append(t_data);

        }
    }
    
    if(!is_specific_queue && global_summary != null){
        var sum_tiempo_total = Math.round(parseFloat(global_summary["t_total_llamada"]));
        var sum_tiempo_total_espera = Math.round(parseFloat(global_summary["t_total_espera"]));
        var completadas = parseInt(global_summary["total_completadas"])
        var abandonadas = parseInt(global_summary["total_abandonadas"])
        var final_nivel_servicio = round(parseInt(global_summary["total_NS_30"])/completadas, 2);

        var sum_total = completadas + abandonadas;

        var t_data = '<tr class="col-xs-1 text-center table-info">';
        t_data += '<td>Total</td>';
        t_data += '<td></td>';
        t_data += '<td></td>';
        t_data += '<td></td>';
        // t_data += '<td>' + final_nivel_servicio+ '</td>';
        t_data += '<td>' + formatNumber(Math.round(sum_tiempo_total/completadas)) + '</td>';
        t_data += '<td>' + formatNumber(Math.round(sum_tiempo_total_espera/sum_total)) + '</td>';
        t_data += '<td></td>';
        t_data += '</tr>';
             
        $('#table-queue-state > tbody').append(t_data);
    }
    
}

function fillAgentsState(agents, is_specific_queue){


    

    agents.forEach(agent => {



        // console.log(agent['callerid'], agent['estado'], agent['callerid'] != undefined, agent['estado'] == "Conectado")

        if((agent['estado'] == "Conectado") || is_specific_queue){

            var element = '<div class="col-md-2" align="center">';
            element += '<span class="badge badge-' + tag_type[agent['estado']] + '">';
            element += ((agent['canal'] == undefined)? "no canal":agent['canal']).replace("@from-queue","");
            element += "<br>" + agent['nombre'].replace("@from-queue","");

            if(agent['callerid'] != undefined){
                element += "<br>Num: " + agent['callerid'];
                element += "<br>Tiempo: " + agent['duracion'];
            }
            element += "</span></div>";

            // console.log(element);

            			
            $("#operators_in_call").append(element);

        }
    });


}

function fillEntryCalls(calls){

    calls.forEach(call => {
        var t_data = '<tr class="table-default">';
        t_data += '<td>' + call['callerid'] + '</td>';
        t_data += '<td>' + (call['canal'].split("-"))[0] + '</td>';
        t_data += '<td>' + call['tespera'] + '</td>';
        t_data += '</tr>';
        
        $('#table-entry-calls > tbody').append(t_data);
    });


}

function resetTable(table_id){

    // $('#' + table_id + ' > thead tr').empty();
    $('#' + table_id + ' > tbody').empty();
}

function resetElement(element_id){
    $('#' + element_id).empty();
}


function existNewDataInTableStatesGeneral(last_response_general_json, response_json){


    if(Object.keys(last_response_general_json).length != Object.keys(response_json).length){
        return true;
    }
    

    for (const [key, element] of Object.entries(response_json)){

        for (const [key_child, element_child] of Object.entries(element)){

            if(last_response_general_json[key][key_child] != response_json[key][key_child] ){
                return true;
            }

        }

        
    }

    return false
}

function existNewDataInTableStatesSpecific(last_response_specific_json, response_json){

    if(Object.keys(last_response_specific_json).length != Object.keys(response_json).length){
        return true;
    }
    

    for (const [key, element] of Object.entries(response_json)){

        if(last_response_specific_json[key] != response_json[key]){
            return true;
        }
    }

    return false
}