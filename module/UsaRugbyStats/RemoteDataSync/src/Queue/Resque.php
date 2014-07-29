<?php
namespace UsaRugbyStats\RemoteDataSync\Queue;

class Resque implements QueueInterface
{

    public function enqueue($queue, $class, $args)
    {
        $token = \Resque::enqueue($queue, $class, $args);
        \Resque_Job_Status::create($token);

        return $token;
    }

    public function getJobStatus($token)
    {
        $jobStatus = new \Resque_Job_Status($token);
        $result = $jobStatus->get();

        return $result;
    }

    public function __call($method, $args)
    {
        return call_user_func_array("Resque::{$method}", $args);
    }
}
