<?php

class Options{
    /** @var string */
    protected $command = "";
    protected $options = [];

    public function __construct(string $command) {
        $this->command = $command;
    }

    /**
     * @param array $argValues
     * @return $this
     */
    static public function format(array $argValues)
    {
        $options = new Options(array_pop($argValues));
        // $argValues

        return $options;
    }

    public function setOptions(string $key, string $value): void
    {
        $this->options[$key] = $value;
    }

    public function getOption(string $key): string
    {
        if(array_key_exists($key, $this->options)) {
            echo "option -$key is required.";
            exit(1);
        }

        return $this->options[$key];
    }
}
