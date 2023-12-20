<?php
class Event
{
    protected Organizer $organizer;
    protected String $type;
    protected int $id;
    protected array $participants = [];
    protected int $upvotes = 0;
    protected array $reviews = [];

    function __construct(Organizer $organizer, String $type, int $id)
    {
        $this->organizer = $organizer;
        $this->type = $type;
        $this->id = $id;
    }

    function get_organizer(): Organizer
    {
        return $this->organizer;
    }

    function get_type(): String
    {
        return $this->type;
    }

    function get_id(): int
    {
        return $this->id;
    }

    function cancel_event(): void
    {
    }

    function add_participant(User $user): void
    {
        $this->participants[] = $user;
    }

    function remove_participant(User $user): void
    {
        $this->participants = array_filter($this->participants, function ($participant) use ($user) {
            return $participant !== $user;
        });
    }

    function set_upvotes(int $count): void
    {
        $this->upvotes = $count;
    }

    function get_upvotes(): int
    {
        return $this->upvotes;
    }

    function add_review(Review $review)
    {
        $this->reviews[] = $review;
    }

    function remove_review(Review $review)
    {
        $this->participants = array_filter($this->participants, function ($participant) use ($review) {
            return $participant !== $review;
        });
    }
}
