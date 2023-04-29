<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventsController extends BaseController
{
    public function getWarmupEvents() {
        return Event::all();
    }

    /* TODO: complete getEventsWithWorkshops so that it returns all events including the workshops
     Requirements:
    - maximum 2 sql queries
    - Don't post process query result in PHP
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints:
    - partial or not working answers also get graded so make sure you commit what you have

    Sample response on GET /events:
    ```json
    [
        {
            "id": 1,
            "name": "Laravel convention 2020",
            "created_at": "2021-04-25T09:32:27.000000Z",
            "updated_at": "2021-04-25T09:32:27.000000Z",
            "workshops": [
                {
                    "id": 1,
                    "start": "2020-02-21 10:00:00",
                    "end": "2020-02-21 16:00:00",
                    "event_id": 1,
                    "name": "Illuminate your knowledge of the laravel code base",
                    "created_at": "2021-04-25T09:32:27.000000Z",
                    "updated_at": "2021-04-25T09:32:27.000000Z"
                }
            ]
        },
        {
            "id": 2,
            "name": "Laravel convention 2021",
            "created_at": "2021-04-25T09:32:27.000000Z",
            "updated_at": "2021-04-25T09:32:27.000000Z",
            "workshops": [
                {
                    "id": 2,
                    "start": "2021-10-21 10:00:00",
                    "end": "2021-10-21 18:00:00",
                    "event_id": 2,
                    "name": "The new Eloquent - load more with less",
                    "created_at": "2021-04-25T09:32:27.000000Z",
                    "updated_at": "2021-04-25T09:32:27.000000Z"
                },
                {
                    "id": 3,
                    "start": "2021-11-21 09:00:00",
                    "end": "2021-11-21 17:00:00",
                    "event_id": 2,
                    "name": "AutoEx - handles exceptions 100% automatic",
                    "created_at": "2021-04-25T09:32:27.000000Z",
                    "updated_at": "2021-04-25T09:32:27.000000Z"
                }
            ]
        },
        {
            "id": 3,
            "name": "React convention 2021",
            "created_at": "2021-04-25T09:32:27.000000Z",
            "updated_at": "2021-04-25T09:32:27.000000Z",
            "workshops": [
                {
                    "id": 4,
                    "start": "2021-08-21 10:00:00",
                    "end": "2021-08-21 18:00:00",
                    "event_id": 3,
                    "name": "#NoClass pure functional programming",
                    "created_at": "2021-04-25T09:32:27.000000Z",
                    "updated_at": "2021-04-25T09:32:27.000000Z"
                },
                {
                    "id": 5,
                    "start": "2021-08-21 09:00:00",
                    "end": "2021-08-21 17:00:00",
                    "event_id": 3,
                    "name": "Navigating the function jungle",
                    "created_at": "2021-04-25T09:32:27.000000Z",
                    "updated_at": "2021-04-25T09:32:27.000000Z"
                }
            ]
        }
    ]
     */

    public function getEventsWithWorkshops() {
        // $events = DB::table('events')
        // ->leftJoin('workshops', 'events.id', '=', 'workshops.event_id')
        // ->groupBy('events.id')
        // ->select('events.id', 'events.name', 'events.created_at', 'events.updated_at',
        //     DB::raw("json_group_array(
        //         JSON_OBJECT(
        //             'id', workshops.id,
        //             'start', workshops.start,
        //             'end', workshops.end,
        //             'event_id', workshops.event_id,
        //             'name', workshops.name,
        //             'created_at', workshops.created_at,
        //             'updated_at', workshops.updated_at
        //         )
        //     ) AS workshops")
        // )
        // ->get();

        $events = Event::select('events.id', 'events.name', 'events.created_at', 'events.updated_at')
        ->with('workshops')
        ->whereHas('workshops', function ($query) {
            $query->orderBy('start', 'asc');
        })
        ->get();
        return response()->json($events);

        /**I can also get this data using ORM and using Laravel Resource/Collections with relation ship but
         * due limited time get using this raw query, To be honest i search from internet to get json format data
         * from sqlite i'm using sqlite first time.
         */

       /**
        * Comment After Unit Testing First thing your unit dir is missing
        *  After Fixing Unit dir and running unit test its throw error as below commented
        *  so i write the query in Eloquent by creating relation
        *
        */

        //   ✓ future events
        //     ⨯ menu

        //     ---

        //     • Tests\Feature\ExampleTest > events
        //     Failed asserting that null is identical to 'Illuminate your knowledge of the laravel code base'.

        //     at C:\Users\DELL\Desktop\laravel-test\tests\Feature\ExampleTest.php:31
        //         27▕         $response = $this->get('/events');
        //         28▕         $response->assertStatus(200)
        //         29▕             ->assertJsonCount(3)
        //         30▕             ->assertJsonPath('0.name', 'Laravel convention '.$datePast->year)
        //     ➜  31▕             ->assertJsonPath('0.workshops.0.name', 'Illuminate your knowledge of the laravel code base')
        //         32▕             ->assertJsonPath('1.name', 'Laravel convention '.$dateFuture->year)
        //         33▕             ->assertJsonPath('1.workshops.0.name', 'The new Eloquent - load more with less')
        //         34▕             ->assertJsonPath('1.workshops.1.name', 'AutoEx - handles exceptions 100% automatic')
        //         35▕             ->assertJsonPath('2.name', 'React convention '.$dateFuture->year)

        //     1   C:\Users\DELL\Desktop\laravel-test\vendor\phpunit\phpunit\phpunit:107
        //         PHPUnit\TextUI\Command::main()

    }


    /* TODO: complete getFutureEventWithWorkshops so that it returns events with workshops, that have not yet started
    Requirements:
    - only events that have not yet started should be included
    - the event starting time is determined by the first workshop of the event
    - the eloquent expressions should result in maximum 3 SQL queries, no matter the amount of events
    - Don't post process query result in PHP
    - verify your solution with `php artisan test`
    - do a `git commit && git push` after you are done or when the time limit is over

    Hints:
    - partial or not working answers also get graded so make sure you commit what you have
    - join, whereIn, min, groupBy, havingRaw might be helpful
    - in the sample data set  the event with id 1 is already in the past and should therefore be excluded

    Sample response on GET /futureevents:
    ```json
    [
        {
            "id": 2,
            "name": "Laravel convention 2021",
            "created_at": "2021-04-20T07:01:14.000000Z",
            "updated_at": "2021-04-20T07:01:14.000000Z",
            "workshops": [
                {
                    "id": 2,
                    "start": "2021-10-21 10:00:00",
                    "end": "2021-10-21 18:00:00",
                    "event_id": 2,
                    "name": "The new Eloquent - load more with less",
                    "created_at": "2021-04-20T07:01:14.000000Z",
                    "updated_at": "2021-04-20T07:01:14.000000Z"
                },
                {
                    "id": 3,
                    "start": "2021-11-21 09:00:00",
                    "end": "2021-11-21 17:00:00",
                    "event_id": 2,
                    "name": "AutoEx - handles exceptions 100% automatic",
                    "created_at": "2021-04-20T07:01:14.000000Z",
                    "updated_at": "2021-04-20T07:01:14.000000Z"
                }
            ]
        },
        {
            "id": 3,
            "name": "React convention 2021",
            "created_at": "2021-04-20T07:01:14.000000Z",
            "updated_at": "2021-04-20T07:01:14.000000Z",
            "workshops": [
                {
                    "id": 4,
                    "start": "2021-08-21 10:00:00",
                    "end": "2021-08-21 18:00:00",
                    "event_id": 3,
                    "name": "#NoClass pure functional programming",
                    "created_at": "2021-04-20T07:01:14.000000Z",
                    "updated_at": "2021-04-20T07:01:14.000000Z"
                },
                {
                    "id": 5,
                    "start": "2021-08-21 09:00:00",
                    "end": "2021-08-21 17:00:00",
                    "event_id": 3,
                    "name": "Navigating the function jungle",
                    "created_at": "2021-04-20T07:01:14.000000Z",
                    "updated_at": "2021-04-20T07:01:14.000000Z"
                }
            ]
        }
    ]
    ```
     */

    public function getFutureEventsWithWorkshops() {
        $events = Event::select('events.id', 'events.name', 'events.created_at', 'events.updated_at')
        ->with('workshops')
        ->whereHas('workshops', function ($query) {
            $query->where('start', '>', now());
            $query->orderBy('start', 'asc');
        })
        ->get();
        return response()->json($events);

    }
}
