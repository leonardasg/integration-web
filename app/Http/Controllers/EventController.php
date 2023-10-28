<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventController extends Controller
{
    public function action()
    {
        if (request('type') == 'add')
        {
            $event = Event::create([
                'title' => request('title'),
                'start' => request('start'),
                'end' => request('end')
            ]);

            return response()->json($event);
        }

        if (request('type') == 'update')
        {
            $event = Event::find(request('id'))->update([
                'title' => request('title'),
                'start' => request('start'),
                'end' => request('end')
            ]);

            return response()->json($event);
        }

        if (request('type') == 'delete')
        {
            $event = Event::find(request('id'))->delete();

            return response()->json($event);
        }
    }
}
