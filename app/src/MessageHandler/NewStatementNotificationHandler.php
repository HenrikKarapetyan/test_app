<?php

namespace App\MessageHandler;

use App\Message\NewStatementNotification;
use App\Services\StatementService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class NewStatementNotificationHandler
{
    public function __construct(private StatementService $statementService)
    {
    }

    public function __invoke(NewStatementNotification $newStatementNotification): void
    {
        $statementModel = $newStatementNotification->getStatementModel();
        $this->statementService->new($statementModel);
    }
}
