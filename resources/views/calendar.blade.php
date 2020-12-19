<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Calendar App</title>
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

        <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">

        <link href="{{ asset('/css/style.css') }}" rel="stylesheet" >
    </head>
    
    <body>
        <div id="app">
            <div class="container-fluid mt-4">
                <div class="card">
                    <div class="card-body">                    	
                        <h4 class="card-title">Calendar</h4>
                    	<div class="row">
            	        	<div class="col-md-4">
            	        		<form action="" method="post" id="event-form">

                                    @csrf
            	        			<div class="form-group">
                                        <label for="event">Event</label>
                                        <input type="text" name="event" class="form-control" required />
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="event">From</label>
                                                <input type="text" name="date_from" id="date_from" class="form-control" required />
                                            </div>
                                            <div class="col-md-6">
                                                <label for="to">To</label>
                                                <input type="text" name="date_to" id="date_to" class="form-control" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="checkbox" name="day[]" value="Monday" /> Mon 
                                        <input type="checkbox" name="day[]" value="Tuesday" /> Tue 
                                        <input type="checkbox" name="day[]" value="Wednesday" /> Wed 
                                        <input type="checkbox" name="day[]" value="Thursday" /> Thu 
                                        <input type="checkbox" name="day[]" value="Friday" /> Fri 
                                        <input type="checkbox" name="day[]" value="Saturday" /> Sat 
                                        <input type="checkbox" name="day[]" value="Sunday" /> Sun 

                                    </div>

                                    <button type="button" id="save" class="btn btn-primary">Save</button>
            	        		</form>
            	        	</div>
            	        	<div class="col-md-8">
            	        		
                                <h2>Jul 2018</h2>

                                <table class="table">
                                    <tbody>
                                        @php
                                            $date = new DateTime('2018-07-01');
                                            for($i=1; $i<=31; $i++){
                                                echo '<tr class="tr-'.$i.' date-row ';

                                                if(in_array($i,$days)){
                                                    echo 'lightgreen';
                                                }

                                                echo '"><td class="day">'.$i.' '.$date->format('D').'</td>
                                                    <td>';

                                                if(in_array($i,$days)){
                                                    echo $event;
                                                }

                                                echo '</td>
                                                    </tr>';
                                                $date->modify('+1 day');
                                            }
                                        @endphp
                                    </tbody>
                                </table>    

            	        	</div>
                    	</div>
                	
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>  

        <script src="{{ asset('libs/bootstrap-notify-3.1.3/bootstrap-notify.min.js') }}"></script>

        <script type="text/javascript">
            
            $(function(){
                let minDateVal = new Date(2018, 7 - 1, 1);
                let maxDateVal = new Date(2018, 7 - 1, 31);
                
                $( "#date_from,#date_to" ).datepicker(
                    { 
                        dateFormat: 'yy-mm-dd', 
                        minDate: minDateVal,
                        maxDate: maxDateVal 
                    }
                );

                $('#save').click(function(){
                    
                    $.ajax({
                        url: '{{ url("/events") }}',
                        method: 'POST',
                        data: $('#event-form').serialize(),
                        dataType: 'json',
                        success: function(data){

                            $('.date-row').removeClass('lightgreen');
                            $('.date-row').find("td:eq(1)").text('');

                            if(data.event && data.days){
                                $.each(data.days,function(ndx,val){

                                    $('.tr-'+val).addClass('lightgreen');
                                    $('.tr-'+val).find("td:eq(1)").text(data.event);

                                });

                                $.notify({
                                    message: '&check; Event Successfully Saved' 
                                },{
                                    allow_dismiss: false,
                                    timer: 500,
                                    onShow: function() {
                                        this.css({'background':'#5cb85c','color':'#FFFFFF','width':'auto','height':'auto'});
                                    }
                                });
                            }

                            if(data.error){
                                $.notify({
                                    message: '&check; ' + data.error 
                                },{
                                    allow_dismiss: false,
                                    timer: 500,
                                    onShow: function() {
                                        this.css({'background':'#d9534f','color':'#FFFFFF','width':'auto','height':'auto'});
                                    }
                                });
                            }

                        }

                    });

                });

                
            });
            
        </script>

    </body>
</html>


