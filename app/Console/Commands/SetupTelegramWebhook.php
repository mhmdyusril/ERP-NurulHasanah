<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupTelegramWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:setup-webhook {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up Telegram Webhook with the given URL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = $this->argument('url');
        $webhookUrl = rtrim($url, '/') . '/api/telegram/webhook';

        $this->info("Setting webhook to: " . $webhookUrl);

        try {
            $response = \Telegram\Bot\Laravel\Facades\Telegram::setWebhook(['url' => $webhookUrl]);
            if ($response) {
                $this->info("Webhook successfully set!");
            } else {
                $this->error("Failed to set webhook.");
            }
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
