
Chart.plugins.register(ChartDataLabels);


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

        v2-9-4
        Registrar:Chart.plugins.register(ChartDataLabels);

         var data = [{
          data: [50, 55, 60, 33],
          labels: ["India", "China", "US", "Canada"],
          backgroundColor: [
              "#4b77a9",
              "#5f255f",
              "#d21243",
              "#B27200"
          ],
          borderColor: "#fff"
      }];
      
         var options = {
         tooltips: {
       enabled: false
  },
           plugins: {
          datalabels: {
              formatter: (value, ctx) => {
              
                let sum = 0;
                let dataArr = ctx.chart.data.datasets[0].data;
                dataArr.map(data => {
                    sum += data;
                });
                let percentage = (value*100 / sum).toFixed(2)+"%";
                return percentage;

            
              },
              color: '#fff',
                   }
      }
  };

    */

  var data_in = [{
      data: data,
      backgroundColor: colors,
      borderColor: "#fff"
  }];
  
    
    
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: labels, 
          datasets: data_in
        },
        options: options
    });
      
      



    
    
    // console.log("CREATED", myChart)

    return myChart
}


