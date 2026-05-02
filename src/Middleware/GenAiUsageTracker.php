<?php
namespace Cookbook\Middleware;
use ArrayObject;
use Exception;
use SplFileObject;
use DateTime;
use Cookbook\Services\GenAiConnect;
#[GenAiUsageTracker("Tracks GenAI token usage")]
class GenAiUsageTracker
{
    public const CSV_FN  = __DIR__ . '/../Chapter07/api_call_usage.csv';
    public ?ArrayObject $logInfo = NULL;
    public array $headers    = ['Timestamp','Y','M','D','H','Model','Hate Filtered','Self Harm Filtered','Sexual Filtered','Violence Filtered','Jailbreak Filtered','Jailbreak Detected','Profanity Filtered','Profanity Detected','Prompt Tokens','Completion Tokens','Total Tokens','Audio Tokens','Cached Tokens','Completion Audio Tokens','Completion Reasoning Tokens'];
    public string $log_regex = '!.*?\|(\d+)\|.*?\"model\"\:\"(.*?)\".*?\"hate\"\:\{\"filtered\"\:(.*?)\}\,\"self_harm\"\:\{\"filtered\"\:(.*?)\}\,\"sexual\"\:\{\"filtered\"\:(.*?)\}\,\"violence\"\:\{\"filtered\"\:(.*?)\}\,\"jailbreak\"\:\{\"filtered\"\:(.*?)\,\"detected\"\:(.*?)\}\,\"profanity\"\:\{\"filtered\"\:(.*?)\,\"detected\"\:(.*?)\}\}\}]\,\"usage\"\:\{\"prompt_tokens\"\:(.*?)\,\"completion_tokens\"\:(.*?)\,\"total_tokens\"\:(.*?)\,\"prompt_tokens_details\"\:\{\"audio_tokens\"\:(.*?)\,\"cached_tokens\"\:(.*?)\}\,\"completion_tokens_details\"\:\{\"audio_tokens\"\:(.*?)\,\"reasoning_tokens\"\:(.*?)\}.*!';
    public string $csv_fn    = '';
    public string $log_fn    = '';
    public string $separator = ',';
    public string $enclosure = '"';
    public string $escape    = '\\';
    public function __construct(public array $config = [])
    {
        $this->csv_fn    = $config['csv_fn']    ?? static::CSV_FN;
        $this->log_fn    = $config['call_log']  ?? GenAiConnect::CALL_LOG;
        $this->log_regex = $config['log_regex'] ?? $this->log_regex;
        $this->headers   = $config['headers']   ?? $this->headers;
        $this->separator = $config['separator'] ?? $this->separator;
        $this->enclosure = $config['enclosure'] ?? $this->enclosure;
        $this->escape    = $config['escape']    ?? $this->escape;
    }
    // parses log file info into array
    public function parseLog() : iterable
    {
        $log = new SplFileObject($this->log_fn, 'r');
        $this->logInfo = new ArrayObject();
        $log->rewind();
        while (!$log->eof()) {
            $line = $log->fgets();
            if (preg_match($this->log_regex, $line, $match)) {
                $date = new DateTime('@' . $match[1] ?? '');
                $y = $date->format('Y');
                $m = $date->format('m');
                $d = $date->format('d');
                $h = $date->format('H');
                $this->logInfo[] = [
                    'timestamp' => $match[1],
                    'y' => $y,
                    'm' => $m,
                    'd' => $d,
                    'h' => $h,
                    'model' => $match[2],                
                    'hate_filtered' => $match[3],
                    'self_harm_filtered' => $match[4],
                    'sexual_filtered'    => $match[5],
                    'violence_filtered'  => $match[6],
                    'jailbreak_filtered' => $match[7],
                    'jailbreak_detected' => $match[8],
                    'profanity_filtered' => $match[9],
                    'profanity_detected' => $match[10],
                    'prompt_tokens'      => $match[11],
                    'completion_tokens'  => $match[12],
                    'total_tokens'       => $match[13],
                    'audio_tokens'       => $match[14],
                    'cached_tokens'      => $match[15],
                    'completion_audio_tokens' => $match[16],
                    'completion_reasoning_tokens' => $match[17],
                ];
            }
        }
        return $this->logInfo;
    }
    // adds log entries to spreadsheet
    public function updateCsv(bool $eraseLog = FALSE, bool $appendCsv = TRUE) : int
    {
        if (!$this->parseLog()) {
            $result = 0;
        } else {
            $write_headers = (file_exists($this->csv_fn)) ? FALSE : TRUE;
            $mode = ($appendCsv) ? 'a' : 'w';
            $csv = new SplFileObject($this->csv_fn, $mode);
            if ($write_headers) {
                $csv->fputcsv($this->headers, separator: $this->separator, enclosure: $this->enclosure, escape: $this->escape);
            }
            $count = 0;
            foreach ($this->logInfo as $key => $row) {
                $count++;
                $csv->fputcsv(array_values($row), separator: $this->separator, enclosure: $this->enclosure, escape: $this->escape);
            }
            $result = $count;
            if ($eraseLog) {
                unlink($this->log_fn);
            }
        }
        return $count;
    }
}
