function exportTableToExcel(tableSelect, filename){
   

    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
   
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}

function formatNumber(input_seconds){
  var seconds = input_seconds;
  var minutes = 0;
  var hours = 0;

  while(seconds >= 60){
    minutes += 1;
    seconds -= 60;
  }

  while(minutes >= 60){
    hours += 1;
    minutes -= 60;
  }

  if(seconds < 10){
    seconds = "0"+seconds;
  }
  if(minutes < 10){
    minutes = "0"+minutes;
  }
  if(hours < 10){
    hours = "0"+hours;
  }

  return hours+":"+minutes+":"+seconds
}

function showSnackbar(msg){
  var x = $("#snackbar");

  // Add the "show" class to DIV
  $(x).attr("class", "show");
  $(x).text(msg);
  // After 3 seconds, remove the show class from DIV
  setTimeout(function(){ $(x).attr("class", ""); }, 1500);
}

function extractJSONData(html){
  const re = /(?:<JSON_DATA>)(.*)(?:<\/JSON_DATA>)/g;
  return JSON.parse(html.match(re)[0].replace("<JSON_DATA>", "").replace("</JSON_DATA>", ""))
}

function round(number, decimals){
  return parseInt(number*(10 ** (2+decimals)))/100
}

function dateAIsAfterDateB(date_a, date_b){

  if(date_a.getFullYear() < date_b.getFullYear()){
    return false;
  }
  else if(date_a.getFullYear() == date_b.getFullYear()){
    if(date_a.getMonth() < date_b.getMonth()){
      return false;
    }
    else if(date_a.getMonth() == date_b.getMonth()){
      if(date_a.getDate() < date_b.getDate() || date_a.getDate() == date_b.getDate()){
        return false;
      }
    }
  }

  return true;
}