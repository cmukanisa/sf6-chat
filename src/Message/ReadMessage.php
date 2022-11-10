<?php

namespace App\Message;

final class ReadMessage
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

    private $thread;

    public function __construct(string $thread)
    {
        $this->thread = $thread;
    }


    /**
     * Get the value of thread
     */
    public function getThread()
    {
        return $this->thread;
    }
}
