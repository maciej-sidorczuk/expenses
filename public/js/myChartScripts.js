$(document).ready(function(){
  function generatePieChart(htmlId, xdata, ydata) {
    var ctx = document.getElementById(htmlId).getContext('2d');
    var myChart = new Chart(ctx, {
			type: 'pie',
			data: {
				datasets: [{
					data: ydata,
					backgroundColor: [
						'rgb(255, 99, 132)',
						'rgb(255, 159, 64)',
						'rgb(255, 205, 86)',
						'rgb(75, 192, 192)',
						'rgb(54, 162, 235)',
					],
					label: 'Dataset 1'
				}],
				labels: xdata
			},
			options: {
				responsive: true
			}
		});
  }
  $(document).on('expenseTableReady', function(){
    if(data_for_chart.size > 0) {
      $(".mainChartContainer .chart-title").css("display", "block");
      var keys = Array.from(data_for_chart.keys());
      keys.sort();
      var x_data = [];
      var y_data = [];
      var current_sum = 0;
      for (i = 0; i < keys.length; i++) {
        key = keys[i];
        value = data_for_chart.get(key);
        current_sum += parseFloat(value);
        x_data.push(key);
        y_data.push(current_sum);
      }
      var ctx = document.getElementById('mainChart').getContext('2d');
      var myChart = new Chart(ctx, {
          type: 'line',
          data: {
              labels: x_data,
              datasets: [{
                  label: 'Total expenses in selected period',
                  data: y_data,
                  backgroundColor: [
                      'rgba(255, 99, 132, 0.2)',
                      'rgba(54, 162, 235, 0.2)',
                      'rgba(255, 206, 86, 0.2)',
                      'rgba(75, 192, 192, 0.2)',
                      'rgba(153, 102, 255, 0.2)',
                      'rgba(255, 159, 64, 0.2)'
                  ],
                  borderColor: [
                      'rgba(255, 99, 132, 1)',
                      'rgba(54, 162, 235, 1)',
                      'rgba(255, 206, 86, 1)',
                      'rgba(75, 192, 192, 1)',
                      'rgba(153, 102, 255, 1)',
                      'rgba(255, 159, 64, 1)'
                  ],
                  borderWidth: 1
              }]
          },
          options: {
              scales: {
                  yAxes: [{
                      ticks: {
                          beginAtZero: true
                      }
                  }]
              }
          }
      });
    } else {
      var parent = $('#mainChart').parent();
      parent.find(".chart-title").css("display", "none");
      $('#mainChart').remove();
      parent.append("<canvas id=\"mainChart\"></canvas>");
    }
    if(data_for_category_chart.size > 0) {
      var keys = Array.from(data_for_category_chart.keys());
      var values = Array.from(data_for_category_chart.values());
      generatePieChart("categoryChart", keys, values);
      $("#categoryChart").parent().find(".chart-title").css("display", "block");
    } else {
      var parent = $('#categoryChart').parent();
      parent.find(".chart-title").css("display", "none");
      $('#categoryChart').remove();
      parent.append("<canvas id=\"categoryChart\"></canvas>");
    }
    if(data_for_place_chart.size > 0) {
      var keys = Array.from(data_for_place_chart.keys());
      var values = Array.from(data_for_place_chart.values());
      generatePieChart("placeChart", keys, values);
      $("#placeChart").parent().find(".chart-title").css("display", "block");
    } else {
      var parent = $('#placeChart').parent();
      parent.find(".chart-title").css("display", "none");
      $('#placeChart').remove();
      parent.append("<canvas id=\"placeChart\"></canvas>");
    }
    if(data_for_typeOfExpense_chart.size > 0) {
      var keys = Array.from(data_for_typeOfExpense_chart.keys());
      var values = Array.from(data_for_typeOfExpense_chart.values());
      generatePieChart("typeOfExpenseChart", keys, values);
      $("#typeOfExpenseChart").parent().find(".chart-title").css("display", "block");
    } else {
      var parent = $('#typeOfExpenseChart').parent();
      parent.find(".chart-title").css("display", "none");
      $('#typeOfExpenseChart').remove();
      parent.append("<canvas id=\"typeOfExpenseChart\"></canvas>");
    }
    if(data_for_paymentMethod_chart.size > 0) {
      var keys = Array.from(data_for_paymentMethod_chart.keys());
      var values = Array.from(data_for_paymentMethod_chart.values());
      generatePieChart("paymentMethodChart", keys, values);
      $("#paymentMethodChart").parent().find(".chart-title").css("display", "block");
    } else {
      var parent = $('#paymentMethodChart').parent();
      parent.find(".chart-title").css("display", "none");
      $('#paymentMethodChart').remove();
      parent.append("<canvas id=\"paymentMethodChart\"></canvas>");
    }
  });
});
