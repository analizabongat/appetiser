<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\View;
use App\Models\Events;
use Request;

/*
// Class: CalendarController
// Contains all the function in displaying and posting events to the calendar
*/
class CalendarController extends Controller
{

    /*
    // Function: index
    // Displays the calendar form and listing of events
    */
    public function index(){

    	$days = [];

    	$event = Events::first(); //get event
    	if(!empty($event->event) && !empty($event->days)){
			$event = $event->toArray();	
			$days = Self::getEventDays($event); //get days with events
			$event = $event['event'];
    	}
    	
    	return view('calendar', compact('days','event'));
    }

    /*
    // Function: events
    // Saving the events to the database
    */
    public function events(){
    	
    	$eventText = request('event');
    	$days = request('day');
    	$dateFrom = request('date_from');
    	$dateTo = request('date_to');
    	if(!empty($eventText) && !empty($days) && !empty($dateFrom) && !empty($dateTo)){
    		    	
	    	$explodeDateFrom = explode('-', $dateFrom);
	    	$explodeDateTo = explode('-', $dateTo);

	    	if($explodeDateFrom[2] > $explodeDateTo[2]){
	    		$eventData['error'] = 'Date To should be greater than Date From!';
	    		echo json_encode($eventData);
	    		return;
	    	}

    		Events::query()->truncate(); //remove old records

    		$event = new Events();
    		$event->event = $eventText;
    		$event->days = implode(',',$days);
    		$event->date_from = $dateFrom;
    		$event->date_to = $dateTo;
    		$event->save(); //save new events

    		$event = $event->toArray();

    		$eventData = [
    			'event' => $eventText, //pass on the event title
    			'days' => Self::getEventDays($event) //pass on the days with events
    		];
    		
    	}else{
    		$eventData['error'] = 'All fields are required!';
    	}

    	echo json_encode($eventData); //return json of eventData
    	return;
    }

    /*
    // Function: events
    // Determine the days with events
    */
    public function getEventDays($event){

    	$dateFrom = $event['date_from'];
    	$dateTo = $event['date_to'];
    	$days = explode(',',$event['days']);

    	$explodeDateFrom = explode('-', $dateFrom);
    	$explodeDateTo = explode('-', $dateTo);

    	$dateFromDay = $explodeDateFrom[2]; //get the day from date from
    	$dateTomDay = $explodeDateTo[2]; //get the day from date to

    	$daysToPlot = [];
    	for ($i=$dateFromDay-1; $i <= $dateTomDay; $i++) { 
    		
            $date = $explodeDateFrom[0].'-'.$explodeDateFrom[1].'-'.$i; //set the date
    		$day = date('l', strtotime($date)); //get the day from the week of the specified date

    		if(in_array($day,$days)){
    			$daysToPlot[] = $i; //put to array the day with event
    		}

    	}

    	return $daysToPlot; //return the array of days with event
    }

}
