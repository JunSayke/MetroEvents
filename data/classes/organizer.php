<?php
class Organizer extends User
{
    protected array $events;

    function create_event(Event $event): void
    {
    }

    function accept_join_request(User $user, Event $event): void
    {
    }

    function leave_join_request(User $user, Event $event): void
    {
    }
}
