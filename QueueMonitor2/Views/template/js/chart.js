function createChart(ctx, type, title, labels, data, colors, options){
    /*
    type = "pie"
    ctx = $('#chartPie')[0].getContext('2d');
    title = "title"
    labels = ["uno", "dos", "tres"]
    data = [1,2,3]
    colors = [
            'rgb(255, 99, 132)',
            'rgb(54, 162, 235)',
            'rgb(255, 205, 86)'
          ]
    options = {
            radius: "80%",
            animation:{
                animateScale: true
            }
        }
    */

    const data_holder = {
        labels: labels,
        datasets: [{
          label: title,
          data: data,
          backgroundColor: colors,
          hoverOffset: 4
        }]
      };


    var myChart = new Chart(ctx, {
        type: type,
        data: data_holder,
        options: options
    });

    // console.log("CREATED", myChart)

    return myChart
}


