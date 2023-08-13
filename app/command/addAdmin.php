<?php
declare (strict_types = 1);

namespace app\command;

use app\admin\model\AdminModel;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\db\exception\DbException;

class addAdmin extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('addAdmin')
            ->addOption('username', 'u', Option::VALUE_REQUIRED, 'username')
            ->addOption('password', 'p', Option::VALUE_OPTIONAL, 'password')
            ->setDescription('add admin');
    }

    protected function execute(Input $input, Output $output)
    {
        $username = $input->getOption('username');
        if ($input->hasOption('password'))
        {
            $password = $input->getOption('password');
        }
        else
        {
            $tmp = sha1(strval(time()));
            $password = substr($tmp, 0, 6);
        }
        try {
            $admin = AdminModel::where('username', '=', $username)->findOrEmpty();
            if ($admin->isEmpty())
            {
                AdminModel::create([
                    'username' => $username,
                    'password' => password_hash(sha1($password), PASSWORD_BCRYPT),
                ]);
            }
            else
            {
                $output->writeln('The username already exists');
                $output->writeln('Please enter a different username');
                return;
            }
        }
        catch (DbException $exception)
        {
            $output->writeln('Error: ' . $exception->getMessage());
            return;
        }
        $output->writeln('init admin successful');
        $output->writeln('username: ' . $username);
        $output->writeln('password: ' . $password);
    }
}
