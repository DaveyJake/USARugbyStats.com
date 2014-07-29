<?php
namespace UsaRugbyStats\RemoteDataSync\Queue;

class Resque implements QueueInterface
{

    public function enqueue($queue, $class, $args)
    {
        return \Resque::enqueue($queue, $class, $args);
    }

    public function getJobStatus($token)
    {
        return (new \Resque_Job_Status($token))->get();
    }

    public function __call($method, $args)
    {
        return call_user_func_array("Resque::{$method}", $args);
    }
}
