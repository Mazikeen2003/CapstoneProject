<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class BrevoApiTransport extends AbstractTransport
{
    public function __construct(private readonly string $apiKey)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = $message->getOriginalMessage();

        if (! $email instanceof Email) {
            throw new TransportException('Brevo API transport can only send email messages.');
        }

        $from = $email->getFrom()[0] ?? null;
        if (! $from instanceof Address) {
            throw new TransportException('A sender address is required to send email through Brevo.');
        }

        $payload = [
            'sender' => $this->formatAddress($from),
            'to' => array_map(fn (Address $address) => $this->formatAddress($address), $email->getTo()),
            'subject' => $email->getSubject() ?? '',
        ];

        if ($email->getCc() !== []) {
            $payload['cc'] = array_map(fn (Address $address) => $this->formatAddress($address), $email->getCc());
        }

        if ($email->getBcc() !== []) {
            $payload['bcc'] = array_map(fn (Address $address) => $this->formatAddress($address), $email->getBcc());
        }

        if ($email->getReplyTo() !== []) {
            $payload['replyTo'] = $this->formatAddress($email->getReplyTo()[0]);
        }

        $html = $this->bodyToString($email->getHtmlBody());
        $text = $this->bodyToString($email->getTextBody());

        if ($html !== '') {
            $payload['htmlContent'] = $html;
        }

        if ($text !== '') {
            $payload['textContent'] = $text;
        }

        try {
            Http::acceptJson()
                ->withHeaders(['api-key' => $this->apiKey])
                ->timeout(20)
                ->post('https://api.brevo.com/v3/smtp/email', $payload)
                ->throw();
        } catch (\Throwable $exception) {
            throw new TransportException('Brevo email API request failed: '.$exception->getMessage(), 0, $exception);
        }
    }

    public function __toString(): string
    {
        return 'brevo-api';
    }

    private function formatAddress(Address $address): array
    {
        $formatted = ['email' => $address->getAddress()];

        if ($address->getName() !== '') {
            $formatted['name'] = $address->getName();
        }

        return $formatted;
    }

    private function bodyToString(mixed $body): string
    {
        if (is_resource($body)) {
            return stream_get_contents($body) ?: '';
        }

        return is_string($body) ? $body : '';
    }
}
