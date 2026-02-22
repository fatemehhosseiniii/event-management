<?php 

return [


    'events'=>[
        'title'=>'Title',
        'description'=>'Description',
        'capacity'=>'Capacity',
        'free_capacity'=>'Free Capacity',
        'start_date'=>'Start Date',
        'end_date'=>'End Date',

        'not_found'=>'Event not found',
    ],
    'reservs'=>[
        'event'=>'Event',

        'title'=>'Title',
        'is_available'=>'Is Available',

        'is_confirmed'=>'Is Confirmed',
        'confirmed'=>[
            'pending'=>'Pending',
            'confirmed'=>'Confirmed',
            'rejected_pending'=>'Rejected Pending',
            'rejected_confirmed'=>'Rejected Confirmed',
        ],

        'already_reserved'=>'You have already reserved this event',
        'event_full'=>'This event is full',
        'not_found'=>'Reservation not found',
        'event_not_found'=>'Event not found',
        'invalid_confirm_type'=>'Invalid confirm type',
    ]

];