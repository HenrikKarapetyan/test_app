<?php

namespace App\Message;

use App\Model\StatementModel;

class NewStatementNotification
{
    public function __construct(private readonly StatementModel $statementModel)
    {
    }

    public function getStatementModel(): StatementModel
    {
        return $this->statementModel;
    }
}
