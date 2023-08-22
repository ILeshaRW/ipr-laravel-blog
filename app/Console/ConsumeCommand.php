<?php

declare(strict_types=1);

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Psr\Log\LoggerInterface;

class ConsumeCommand extends Command
{
    use DispatchesJobs;

    protected $signature = 'rabbitmq:consume';

    protected $description = 'Runs a AMQP consumer that defers work to the Laravel queue worker';

    public function handle(LoggerInterface $logger): bool
    {
        $logger->info('Listening for messages...');

        \Amqp::consume('queue', function ($message, $resolver) {

            var_dump($message->body);

            $resolver->acknowledge($message);
        });

        $logger->info('Consumer exited.');

        return true;
    }
}
