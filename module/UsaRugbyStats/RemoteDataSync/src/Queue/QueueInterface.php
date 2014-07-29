<?php
namespace UsaRugbyStats\RemoteDataSync\Queue;

interface QueueInterface
{
    public function enqueue($queue, $class, $args);
    public function getJobStatus($token);
}
