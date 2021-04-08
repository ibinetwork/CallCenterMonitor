const re = /(?:<JSON_DATA>)(.*)(?:<\/JSON_DATA>)/g;
const tag_type ={
    "Pausado": "danger", 
    "Ocupado": "secondary", 
    "Conectado": "primary", 
    "Offline": "info", 
    "Libre": "success", 
    "Timbrando": "warning"
}


var last_response_general_json = {"5001": {"a": 0}};
var last_response_specific_json = {"a": 0};

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

function getQueueStates(response_json){
    try {
        
        /*
        if(existNewDataInTableStatesGeneral(last_response_general_json, response_json)){
            
        }
        */
        resetTable("table-queue-state");
        fillTableStates(response_json, false)

        
        resetTable("table-entry-calls");
        resetElement("operators_in_call")


        var agents = [];

        for (const [key, element] of Object.entries(response_json)){
            
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

        for (const [key, element] of Object.entries(response_json)){


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



        

        last_response_general_json = response_json;
    
    } catch (error) {
        console.log(response_json);
        console.log("ERROR: " + error + "READ HTML ABOVE FOR MORE INFO")
    }
}

function getSpecificQueueState(response_json){


            try {
                if(response_json == null){
                    console.log("no se encontraron datos de ese id")
                    return;
                }

               

                /*
                 if(existNewDataInTableStatesSpecific(last_response_specific_json, response_json)){
                    
                }
                */

                resetTable("table-queue-state");
                fillTableStates({queue_val: response_json}, true)
                
                resetTable("table-entry-calls");
                resetElement("operators_in_call");


                if(response_json['agentes'].length > 0){

                    var agents = response_json['agentes'];

                    fillAgentsState(agents, true)
                }



                if(response_json['llamadas'].length > 0){

                    var calls = response_json['llamadas'];
                    fillEntryCalls(calls);
                }

                

                last_response_specific_json = response_json;
            
            } catch (error) {
                console.log(response_json);
                console.log("ERROR: " + error)
            }
            
        
}

function fillTableStates(data, allow_all_states){

    for (const [key, element] of Object.entries(data)){

        // console.log(element)

        if((element['llamactuales'] > 0 || element['llamencola'] > 0) || allow_all_states){
                
            var t_data = '<tr class="col-xs-1 text-center table-default">';
            t_data += '<th scope="row">' + element['nomcola'] + '</th>';
            t_data += '<td>' + element['agentes'].length + '</td>';
            t_data += '<td>' + element['llamactuales'] + '</td>';
            t_data += '<td>' + element['llamencola'] + '</td>';
            t_data += '<td>' + element['llamcontestadas'] + '</td>';
            t_data += '<td>' + element['llamabandonadas'] + '</td>';
            t_data += '<td>' + element['niveldeservicio'].replace('within', 'en') + '</td>';
            t_data += '</tr>';
            $('#table-queue-state > tbody').append(t_data);

        }
    }
    
}

function fillAgentsState(agents, allow_all_states){


    

    agents.forEach(agent => {



        // console.log(agent['callerid'], agent['estado'], agent['callerid'] != undefined, agent['estado'] == "Conectado")

        if((agent['estado'] == "Conectado") || allow_all_states){

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

