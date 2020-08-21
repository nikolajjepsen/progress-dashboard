<?php
namespace Progress\Domain\Dns;
require_once(__DIR__ . '/../../../../vendor/autoload.php');
use Tracy\Debugger;
use Progress\Domain\Dns\Exceptions\InvalidArgument;

class Dns
{

	private $domain;
	private $dnsRR;
	private $recordTypes = [
		'A',
		'CNAME',
		'NS',
		'MX',
		'TXT'
	];

	public function __construct(string $domain)
	{
		if (empty($domain)) {
			throw InvalidArgument::domainIsMissing();
		}
		$this->domain = $this->sanitizeDomain($domain);

		Debugger::$logSeverity = E_NOTICE | E_WARNING;
		Debugger::enable(Debugger::DETECT, __DIR__ . '/../../../log');
	}

	private function sanitizeDomain($domain): string 
	{
		$domain = str_replace(['http://', 'https://'], '', $domain);
		$domain = strtok($domain, '/');

		return strtolower($domain);
	}

	public function getRecords(string $type): array
	{
		if (! isset($this->dnsRR))
			$this->fetchDnsRR();

		if ($this->dnsRR === false)
			return [];

		$types = $this->determineTypes([$type]);
		foreach ($types as $type) {
			$dnsValue = $this->getRecordsOfType($type);
		}
		return $dnsValue;
	}

	private function fetchDnsRR()
	{
		$rawDns = dns_get_record($this->domain);
		if ($rawDns && is_array($rawDns)) {
			$this->dnsRR = $rawDns;
		}
	}

	private function determineTypes(array $types): array 
	{
		$types = is_array($types[0] ?? null) ? $types[0] : $types;
		$types = array_map('strtoupper', $types);

		foreach ($types as $type) {
			if (! in_array($type, $this->recordTypes)) {
				throw InvalidArgument::filterIsNotAValidRecordType($type, $this->recordTypes);
			}
		}

		return $types;
	}

	public function getRecordsOfType(string $type): array {
		foreach ($this->dnsRR as $dns) {

			switch($type) {
				case 'A':
					$targetParam = 'ip';
					break;
				case 'CNAME':
				case 'NS':
				case 'MX':
					$targetParam = 'target';
					break;
				case 'TXT':
					$targetParam = 'txt';
					break;
				default:
					break;
			}

			if ($dns['type'] == $type) {
				$matchingRecords[] = $dns[$targetParam];
			}
		}
		return isset($matchingRecords) && ! is_null($matchingRecords) ? $matchingRecords : [];
	}

}