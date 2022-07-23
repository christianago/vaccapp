$(function(){

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   
   var myChart;

    $('#start').datepicker({  
        format: 'dd-mm-yyyy',
        endDate: new Date(),
    }).on('changeDate', prepareDates);


    $('#end').datepicker({  
        format: 'dd-mm-yyyy',
        endDate: new Date(),
    }).on('changeDate', prepareDates);
    

    $('.icon-arrow-left').addClass('fa fa-chevron-left');
    $('.icon-arrow-right').addClass('fa fa-chevron-right');


    function prepareDates(){
        $('#error').text('');
        var startDate = $('#start').val();
        if ( startDate ){
            startDate = moment(startDate, 'DD-MM-YYYY');
            var endDate = $('#end').val();
            if ( endDate ){
                endDate = moment(endDate, 'DD-MM-YYYY');
                if ( startDate > endDate ){
                    $('#error').text('Start date should be older or equal to end date')
                } else{
                    getStats(startDate.format('YYYY-MM-DD'), endDate.format('YYYY-MM-DD'));
                }
            }
        }
    }


    function getStats(startDate, endDate){
        $.ajax({
            type: 'POST',
            url: './stats',
            data: {'start': startDate, 'end': endDate},
            beforeSend: function(){
                $('#error').text('');
                $('#loading').show();
                if( myChart ){
                    myChart.destroy();
                }
            },
            complete: function(res){
                $('#loading').hide();
                console.log(res.responseJSON);
                if ( res.responseJSON.error ){
                    $('#error').text(res.responseJSON.error);
                } 
                else if ( res.responseJSON.chart ){
                    if ( !res.responseJSON.chart.labels ){
                        $('#error').text('No data found based on the conditions');
                        return;
                    }
                    myChart = new Chart(document.getElementById('chart'), {
                        type: 'line',
                        data: {
                            labels: res.responseJSON.chart.labels,
                            datasets: 
                            [
                                {
                                    backgroundColor: 'rgb(0, 191, 255)',
                                    borderColor: 'rgb(0, 191, 255)',
                                    label: 'Total vaccinations per date',
                                    data: res.responseJSON.chart.data1,
                                },
                                {
                                    backgroundColor: 'rgb(139, 0, 139)',
                                    borderColor: 'rgb(139, 0, 139)',
                                    label: 'Dose 1 vaccinations',
                                    data: res.responseJSON.chart.data2,
                                },
                                {
                                    backgroundColor: 'rgb(153, 50, 204)',
                                    borderColor: 'rgb(153, 50, 204)',
                                    label: 'Dose 2 vaccinations',
                                    data: res.responseJSON.chart.data3,
                                },
                                {
                                    backgroundColor: 'rgb(75, 0, 130)',
                                    borderColor: 'rgb(75, 0, 130)',
                                    label: 'Dose 3 vaccinations',
                                    data: res.responseJSON.chart.data4,
                                }
                            ]
                        },
                    });
                } else{
                    $('#error').text('An unknown error occurred client-side');
                }
            }
        });
    }
});