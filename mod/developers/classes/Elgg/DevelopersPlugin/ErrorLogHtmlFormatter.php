<?php

namespace Elgg\DevelopersPlugin;

use Monolog\Formatter\HtmlFormatter;
use Monolog\Logger;

/**
 * HTML error log formatter
 */
class ErrorLogHtmlFormatter extends HtmlFormatter {

	protected $logLevels = [
		Logger::DEBUG => '#cccccc',
		Logger::INFO => '#468847',
		Logger::NOTICE => '#3a87ad',
		Logger::WARNING => '#c09853',
		Logger::ERROR => '#f0ad4e',
		Logger::CRITICAL => '#FF7708',
		Logger::ALERT => '#C12A19',
		Logger::EMERGENCY => '#000000',
	];

	/**
	 * Creates an HTML table row
	 *
	 * @param  string $th       Row header content
	 * @param  string $td       Row standard cell content
	 * @param  bool   $escapeTd false if td content must not be html escaped
	 *
	 * @return string
	 */
	protected function addRow($th, $td = ' ', $escapeTd = true) {
		$th = htmlspecialchars($th, ENT_NOQUOTES, 'UTF-8');
		if ($escapeTd) {
			$td = '<pre>' . htmlspecialchars($td, ENT_NOQUOTES, 'UTF-8') . '</pre>';
		}

		return "<tr class=\"developers-error-log-row\"><th>$th:</th><td>$td</td></tr>";
	}

	/**
	 * Create a HTML h1 tag
	 *
	 * @param  string $title Text to be in the h1
	 * @param  int    $level Error level
	 *
	 * @return string
	 */
	protected function addTitle($title, $level) {
		$title = htmlspecialchars($title, ENT_NOQUOTES, 'UTF-8');

		$level = strtolower(\Elgg\Logger::getLevelName($level));

		return elgg_format_element('h3', [
			'class' => [
				'developers-error-log-title',
				"elgg-state-{$level}",
			]
		], $title);
	}

	/**
	 * Formats a log record.
	 *
	 * @param  array $record A record to format
	 *
	 * @return mixed The formatted record
	 */
	public function format(array $record) {

		$context = elgg_extract('context', $record, []);
		$exception = elgg_extract('exception', $context);

		if ($exception instanceof \Throwable) {
			$timestamp = isset($exception->timestamp) ? (int) $exception->timestamp : time();

			$dt = new \DateTime();
			$dt->setTimestamp($timestamp);
			$record['datetime'] = $dt;

			$eol = PHP_EOL;
			$message = "Exception at time {$timestamp}:{$eol}{$exception}{$eol}";
			$record['message'] = preg_replace('~\R~u', $eol, $message);

			if ($exception instanceof \DatabaseException) {
				$record['context']['sql'] = $exception->getQuery();
				$record['context']['params'] = $exception->getParameters();
			}
		}

		$output = $this->addTitle($record['level_name'], $record['level']);
		$output .= '<table class="elgg-table elgg-table-alt">';

		$output .= $this->addRow('Message', (string) $record['message']);
		$output .= $this->addRow('Time', $record['datetime']->format($this->dateFormat));
		$output .= $this->addRow('Channel', $record['channel']);

		if ($record['context']) {
			foreach ($record['context'] as $key => $value) {
				$output .= $this->addRow($key, $this->convertToString($value));
			}
		}
		if ($record['extra']) {
			foreach ($record['extra'] as $key => $value) {
				$output .= $this->addRow($key, $this->convertToString($value));
			}
		}

		return $output . '</table>';
	}

}