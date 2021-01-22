<?php
declare (strict_types = 1);

namespace command\jpush;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Jpush extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('jpush')
            ->setDescription('the jpush command');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('jpush');
    }
}
