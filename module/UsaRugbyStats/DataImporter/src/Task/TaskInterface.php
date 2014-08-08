<?php
namespace UsaRugbyStats\DataImporter\Task;

interface TaskInterface
{
    public function execute(array $data);
}
