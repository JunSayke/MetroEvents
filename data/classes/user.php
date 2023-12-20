<?php
class User
{
    protected String $username;
    protected int $id;

    function __construct(String $username, int $id)
    {
        $this->username = $username;
        $this->id = $id;
    }

    function get_username(): String
    {
        return $this->username;
    }

    function get_id(): int
    {
        return $this->id;
    }

    function join_event(Event $event): void
    {
    }

    function leave_event(Event $event): void
    {
    }

    private function become_organizer(): void
    {
    }
}
